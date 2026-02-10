<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku'         => 'nullable|string|max:40|unique:products,sku',
            'name'        => 'required|string|max:160',
            'category'    => 'required|string|max:80',
            'subcategory' => 'nullable|string|max:80',
            'description' => 'nullable|string|max:5000',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'status'      => 'nullable|string|in:actif,inactif,rupture',
            'tags'        => 'array',
            'tags.*'      => 'string|max:40',
            'images'      => 'array',
            'images.*'    => 'nullable|image|max:4096',
            'pdf'         => 'nullable|file|mimes:pdf|max:8192',
        ], [
            'name.required' => 'Please enter a product name.',
            'category.required' => 'Please select a category.',
            'price.required' => 'Please provide a price.',
            'stock.required' => 'Please provide a stock quantity.',
            'images.array' => 'Please select one or more image files.',
            'images.*.image' => 'Each file must be a valid image.',
            'images.*.max' => 'Each image must be at most 4 MB.',
            'pdf.mimes' => 'The technical sheet must be a PDF file.',
            'pdf.max' => 'The PDF must be at most 8 MB.'
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            Storage::disk('public')->makeDirectory('products');
            foreach ((array) $request->file('images') as $file) {
                if ($file) {
                    $raw = @file_get_contents($file->getRealPath());
                    $src = ($raw !== false && function_exists('imagecreatefromstring')) ? @imagecreatefromstring($raw) : false;
                    if ($src) {
                        $sw = imagesx($src); $sh = imagesy($src);
                        $mw = 1400; $mh = 1400; $r = min($mw / max(1,$sw), $mh / max(1,$sh), 1);
                        $nw = (int)floor($sw * $r); $nh = (int)floor($sh * $r);
                        $dst = imagecreatetruecolor($nw, $nh);
                        imagealphablending($dst, false); imagesavealpha($dst, true);
                        imagecopyresampled($dst, $src, 0,0,0,0, $nw,$nh, $sw,$sh);
                        @imagedestroy($src);
                        $name = (string) Str::uuid();
                        $dir = 'products';
                        $webpRel = $dir . '/' . $name . '.webp';
                        $jpgRel  = $dir . '/' . $name . '.jpg';
                        $webpAbs = storage_path('app/public/' . $webpRel);
                        $jpgAbs  = storage_path('app/public/' . $jpgRel);
                        if (function_exists('imagewebp')) { @imagewebp($dst, $webpAbs, 70); }
                        imageinterlace($dst, true);
                        @imagejpeg($dst, $jpgAbs, 75);
                        @imagedestroy($dst);
                        $finalRel = file_exists($webpAbs) ? $webpRel : $jpgRel;
                        $images[] = Storage::url($finalRel);
                    } else {
                        $path = $file->store('products', 'public');
                        $images[] = Storage::url($path);
                    }
                }
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
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'status' => ((int)$validated['stock'] === 0) ? 'rupture' : ($validated['status'] ?? 'actif'),
        ]);

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

        $validated = $request->validate([
            'name'        => 'required|string|max:160',
            'category'    => 'required|string|max:80',
            'subcategory' => 'nullable|string|max:80',
            'description' => 'nullable|string|max:5000',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'status'      => 'nullable|string|in:actif,inactif,rupture',
            'sku'         => 'nullable|string|max:40|unique:products,sku,' . $product,
            'tags'        => 'array',
            'tags.*'      => 'string|max:40',
            'images'      => 'array',
            'images.*'    => 'nullable|image|max:4096',
            'pdf'         => 'nullable|file|mimes:pdf|max:8192',
        ], [
            'name.required' => 'Please enter a product name.',
            'category.required' => 'Please select a category.',
            'price.required' => 'Please provide a price.',
            'stock.required' => 'Please provide a stock quantity.',
            'images.array' => 'Please select one or more image files.',
            'images.*.image' => 'Each file must be a valid image.',
            'images.*.max' => 'Each image must be at most 4 MB.',
            'pdf.mimes' => 'The technical sheet must be a PDF file.',
            'pdf.max' => 'The PDF must be at most 8 MB.'
        ]);

        $productModel->name = $validated['name'];
        $productModel->category = $validated['category'];
        $productModel->subcate = $validated['subcategory'] ?? $productModel->subcate;
        $productModel->description = $validated['description'] ?? $productModel->description;
        $productModel->price = $validated['price'];
        $productModel->stock = $validated['stock'];
        $productModel->sku = $validated['sku'] ?? $productModel->sku;
        $productModel->tags = $validated['tags'] ?? $productModel->tags;
        

        if ($request->hasFile('images')) {
            Storage::disk('public')->makeDirectory('products');
            $imgs = [];
            foreach ((array) $request->file('images') as $file) {
                if ($file) {
                    $raw = @file_get_contents($file->getRealPath());
                    $src = ($raw !== false && function_exists('imagecreatefromstring')) ? @imagecreatefromstring($raw) : false;
                    if ($src) {
                        $sw = imagesx($src); $sh = imagesy($src);
                        $mw = 1400; $mh = 1400; $r = min($mw / max(1,$sw), $mh / max(1,$sh), 1);
                        $nw = (int)floor($sw * $r); $nh = (int)floor($sh * $r);
                        $dst = imagecreatetruecolor($nw, $nh);
                        imagealphablending($dst, false); imagesavealpha($dst, true);
                        imagecopyresampled($dst, $src, 0,0,0,0, $nw,$nh, $sw,$sh);
                        @imagedestroy($src);
                        $name = (string) Str::uuid();
                        $dir = 'products';
                        $webpRel = $dir . '/' . $name . '.webp';
                        $jpgRel  = $dir . '/' . $name . '.jpg';
                        $webpAbs = storage_path('app/public/' . $webpRel);
                        $jpgAbs  = storage_path('app/public/' . $jpgRel);
                        if (function_exists('imagewebp')) { @imagewebp($dst, $webpAbs, 70); }
                        imageinterlace($dst, true);
                        @imagejpeg($dst, $jpgAbs, 75);
                        @imagedestroy($dst);
                        $finalRel = file_exists($webpAbs) ? $webpRel : $jpgRel;
                        $imgs[] = Storage::url($finalRel);
                    } else {
                        $imgs[] = Storage::url($file->store('products', 'public'));
                    }
                }
            }
            $existing = is_array($productModel->images) ? $productModel->images : [];
            $productModel->images = array_values(array_unique(array_merge($existing, $imgs)));
        }
        
        if ($request->hasFile('pdf')) {
            $productModel->pdf = Storage::url($request->file('pdf')->store('products', 'public'));
        }

        $productModel->status = ((int)$productModel->stock === 0) ? 'rupture' : ($validated['status'] ?? $productModel->status);
        $productModel->save();

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