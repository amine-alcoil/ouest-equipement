<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        
        $query = Product::query();
        
        if ($q !== '') {
            $query->where(function($query) use ($q) {
                $query->where('sku', 'like', "%{$q}%")
                      ->orWhere('name', 'like', "%{$q}%")
                      ->orWhere('category', 'like', "%{$q}%");
            });
        }
        
        $products = $query->orderBy('id')->get();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'products' => $products, 'q' => $q]);
        }

        return view('admin.products', ['products' => $products, 'q' => $q]);
    }

    /**
     * Process and compress product images for optimal performance.
     */
    private function processImage($file): ?string
    {
        if (!$file) return null;

        $raw = @file_get_contents($file->getRealPath());
        $src = ($raw !== false && function_exists('imagecreatefromstring')) ? @imagecreatefromstring($raw) : false;
        
        if (!$src) {
            return Storage::url($file->store('products', 'public'));
        }

        $sw = imagesx($src); 
        $sh = imagesy($src);
        
        // Drastically reduce dimensions for maximum compatibility and speed
        $mw = 800; 
        $mh = 800; 
        $r = min($mw / max(1,$sw), $mh / max(1,$sh), 1);
        
        $nw = (int)floor($sw * $r); 
        $nh = (int)floor($sh * $r);
        
        $dst = imagecreatetruecolor($nw, $nh);
        imagealphablending($dst, false); 
        imagesavealpha($dst, true);
        imagecopyresampled($dst, $src, 0,0,0,0, $nw,$nh, $sw,$sh);
        @imagedestroy($src);

        $name = (string) Str::uuid();
        $dir = 'products';
        Storage::disk('public')->makeDirectory($dir);
        
        $webpRel = $dir . '/' . $name . '.webp';
        $jpgRel  = $dir . '/' . $name . '.jpg';
        $webpAbs = storage_path('app/public/' . $webpRel);
        $jpgAbs  = storage_path('app/public/' . $jpgRel);

        // High compression for small file size
        if (function_exists('imagewebp')) { 
            @imagewebp($dst, $webpAbs, 50); 
        }
        
        imageinterlace($dst, true);
        @imagejpeg($dst, $jpgAbs, 55);
        @imagedestroy($dst);

        $finalRel = file_exists($webpAbs) ? $webpRel : $jpgRel;
        return Storage::url($finalRel);
    }

    private function syncTags(array $tagNames)
    {
        foreach ($tagNames as $name) {
            Tag::firstOrCreate(['name' => $name]);
        }
    }

    public function store(Request $request)
    {
        $isSpecific = strtolower($request->input('category')) === 'spécifique';

        $validated = $request->validate([
            'sku'         => 'nullable|string|max:40|unique:products,sku',
            'name'        => 'required|string|max:160',
            'category'    => 'required|string|max:80',
            'subcategory' => 'nullable|string|max:80',
            'description' => 'nullable|string|max:5000',
            'price'       => ($isSpecific ? 'nullable' : 'required') . '|numeric|min:0',
            'stock'       => ($isSpecific ? 'nullable' : 'required') . '|integer|min:0',
            'status'      => 'nullable|string|in:actif,inactif,rupture',
            'tags'        => 'array',
            'tags.*'      => 'string|max:40',
            'images'      => 'array',
            'images.*'    => 'nullable|image|max:10240',
            'pdf'         => 'nullable|file|mimes:pdf|max:12288',
        ], [
            'name.required' => 'Please enter a product name.',
            'category.required' => 'Please select a category.',
            'price.required' => 'Please provide a price.',
            'stock.required' => 'Please provide a stock quantity.',
            'images.array' => 'Please select one or more image files.',
            'images.*.image' => 'Each file must be a valid image.',
            'images.*.max' => 'Each image must be at most 10 MB.',
            'pdf.mimes' => 'The technical sheet must be a PDF file.',
            'pdf.max' => 'The PDF must be at most 12 MB.'
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            foreach ((array) $request->file('images') as $file) {
                $url = $this->processImage($file);
                if ($url) $images[] = $url;
            }
        }
        
        $pdfUrl = null;
        if ($request->hasFile('pdf')) {
            $pdfPath = $request->file('pdf')->store('products', 'public');
            $pdfUrl = Storage::url($pdfPath);
        }

        if (empty($validated['sku'])) {
            $validated['sku'] = 'PRD-' . strtoupper(Str::random(6));
        }

        $product = Product::create([
            'sku' => $validated['sku'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'],
            'subcate' => $validated['subcategory'] ?? null,
            'tags' => $validated['tags'] ?? [],
            'images' => $images,
            'pdf' => $pdfUrl,
            'price' => $validated['price'] ?? 0,
            'stock' => $validated['stock'] ?? 0,
            'status' => ((int)($validated['stock'] ?? 0) === 0) ? 'rupture' : ($validated['status'] ?? 'actif'),
        ]);

        if (!empty($validated['tags'])) {
            $this->syncTags($validated['tags']);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'product' => $product]);
        }

        return back()->with('success', 'Produit ajouté: ' . $product->sku . ' — ' . $product->name);
    }

    public function edit(string $product)
    {
        $product = Product::find($product);
        
        if (!$product) {
            return response()->json(['ok' => false, 'message' => 'Produit introuvable.'], 404);
        }

        return response()->json(['ok' => true, 'product' => $product]);
    }

    public function update(Request $request, string $product)
    {
        $productModel = Product::find($product);
        
        if (!$productModel) {
            return response()->json(['ok' => false, 'message' => 'Produit introuvable pour mise à jour.'], 404);
        }

        $isSpecific = strtolower($request->input('category')) === 'spécifique';

        $validated = $request->validate([
            'name'        => 'required|string|max:160',
            'category'    => 'required|string|max:80',
            'subcategory' => 'nullable|string|max:80',
            'description' => 'nullable|string|max:5000',
            'price'       => ($isSpecific ? 'nullable' : 'required') . '|numeric|min:0',
            'stock'       => ($isSpecific ? 'nullable' : 'required') . '|integer|min:0',
            'status'      => 'nullable|string|in:actif,inactif,rupture',
            'sku'         => 'nullable|string|max:40|unique:products,sku,' . $product,
            'tags'        => 'array',
            'tags.*'      => 'string|max:40',
            'images'      => 'array',
            'images.*'    => 'nullable|image|max:10240',
            'pdf'         => 'nullable|file|mimes:pdf|max:12288',
        ], [
            'name.required' => 'Please enter a product name.',
            'category.required' => 'Please select a category.',
            'price.required' => 'Please provide a price.',
            'stock.required' => 'Please provide a stock quantity.',
            'images.array' => 'Please select one or more image files.',
            'images.*.image' => 'Each file must be a valid image.',
            'images.*.max' => 'Each image must be at most 10 MB.',
            'pdf.mimes' => 'The technical sheet must be a PDF file.',
            'pdf.max' => 'The PDF must be at most 12 MB.'
        ]);

        $productModel->name = $validated['name'];
        $productModel->category = $validated['category'];
        $productModel->subcate = $validated['subcategory'] ?? $productModel->subcate;
        $productModel->description = $validated['description'] ?? $productModel->description;
        $productModel->price = $validated['price'] ?? ($isSpecific ? 0 : $productModel->price);
        $productModel->stock = $validated['stock'] ?? ($isSpecific ? 0 : $productModel->stock);
        $productModel->sku = $validated['sku'] ?? $productModel->sku;
        $productModel->tags = $validated['tags'] ?? $productModel->tags;
        

        if ($request->hasFile('images')) {
            $imgs = [];
            foreach ((array) $request->file('images') as $file) {
                $url = $this->processImage($file);
                if ($url) $imgs[] = $url;
            }
            $existing = is_array($productModel->images) ? $productModel->images : [];
            $productModel->images = array_values(array_unique(array_merge($existing, $imgs)));
        }
        
        if ($request->hasFile('pdf')) {
            // Delete old PDF if exists
            if ($productModel->pdf) {
                try {
                    $oldPath = str_replace('/storage/', '', $productModel->pdf);
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($oldPath)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                    }
                } catch (\Throwable $e) {
                    // Log error but continue
                    \Illuminate\Support\Facades\Log::error('Failed to delete old PDF: ' . $e->getMessage());
                }
            }
            $productModel->pdf = Storage::url($request->file('pdf')->store('products', 'public'));
        } elseif ($request->input('delete_pdf') == '1') {
            // Manual deletion of PDF
            if ($productModel->pdf) {
                try {
                    $rel = str_replace('/storage/', '', $productModel->pdf);
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($rel)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($rel);
                    }
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('Failed to manually delete PDF: ' . $e->getMessage());
                }
                $productModel->pdf = null;
            }
        }

        // Allow manual status override, otherwise default to 'rupture' if stock is 0
        $newStatus = $validated['status'] ?? $productModel->status;
        if ((int)$productModel->stock === 0 && $newStatus !== 'inactif') {
            $productModel->status = 'rupture';
        } else {
            $productModel->status = $newStatus;
        }

        $productModel->save();

        if (!empty($validated['tags'])) {
            $this->syncTags($validated['tags']);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'product' => $productModel]);
        }

        return back()->with('success', 'Produit mis à jour: ' . $productModel->sku);
    }

    public function destroy(Request $request, string $product)
    {
        $productModel = Product::find($product);
        
        if (!$productModel) {
            return response()->json(['ok' => false, 'message' => 'Produit introuvable pour suppression.'], 404);
        }

        $productModel->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('success', 'Produit supprimé.');
    }

    public function deleteImage(Request $request, string $product)
    {
        $productModel = Product::find($product);
        if (!$productModel) {
            return response()->json(['ok' => false, 'message' => 'Produit introuvable.'], 404);
        }
        $url = (string) $request->input('url', '');
        if ($url === '') {
            return response()->json(['ok' => false, 'message' => 'URL image requise.'], 422);
        }
        $images = is_array($productModel->images) ? $productModel->images : [];
        $new = array_values(array_filter($images, fn($u) => $u !== $url));
        if (count($new) === count($images)) {
            return response()->json(['ok' => false, 'message' => 'Image non trouvée.'], 404);
        }
        $productModel->images = $new;
        $productModel->save();
        try {
            $rel = preg_replace('#^/storage/#', '', $url);
            if ($rel && \Illuminate\Support\Facades\Storage::disk('public')->exists($rel)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($rel);
            }
        } catch (\Throwable $e) {}
        return response()->json(['ok' => true, 'images' => $new]);
    }
}