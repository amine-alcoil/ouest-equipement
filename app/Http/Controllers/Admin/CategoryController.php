<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        
        $query = Category::query();

        if ($q !== '') {
            $query->where(function($query) use ($q) {
                $query->where('name', 'like', "%{$q}%");
            });
        }

        $cats = $query->orderBy('id')->get()->map(function($cat) {
            $arr = $cat->toArray();
            // Add 'children' alias for 'subcate' for view compatibility
            $arr['children'] = $arr['subcate'] ?? [];
            return $arr;
        })->toArray();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'categories' => $cats, 'q' => $q]);
        }

        return view('admin.category', ['categories' => $cats, 'q' => $q]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:120|unique:categories,name',
            'status' => 'nullable|string|in:Actif,Inactif',
            'children' => 'array',
            'children.*' => 'string|max:120',
        ]);

        $children = $this->normalizeChildren($request->input('children', []));

        $cat = Category::create([
            'name' => $validated['name'],
            'status' => $validated['status'] ?? 'Actif',
            'subcate' => $children,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            $arr = $cat->toArray();
            $arr['children'] = $arr['subcate'] ?? [];
            return response()->json(['ok' => true, 'category' => $arr]);
        }

        return back()->with('success', 'Catégorie ajoutée: ' . $cat->name);
    }

    public function edit(string $category)
    {
        $cat = Category::find($category);
        
        if (!$cat) {
            return response()->json(['ok' => false, 'message' => 'Catégorie introuvable.'], 404);
        }

        $catArray = $cat->toArray();
        // Add 'children' alias for 'subcate' for view compatibility
        $catArray['children'] = $catArray['subcate'] ?? [];

        return response()->json(['ok' => true, 'category' => $catArray]);
    }

    public function update(Request $request, string $category)
    {
        $catModel = Category::find($category);
        
        if (!$catModel) {
            return response()->json(['ok' => false, 'message' => 'Catégorie introuvable pour mise à jour.'], 404);
        }

        $validated = $request->validate([
            'name'   => 'required|string|max:120|unique:categories,name,' . $category,
            'status' => 'nullable|string|in:Actif,Inactif',
            'children' => 'array',
            'children.*' => 'string|max:120',
        ]);

        $catModel->name = $validated['name'];
        $catModel->status = $validated['status'] ?? ($catModel->status ?? 'Actif');
        $catModel->subcate = $this->normalizeChildren($request->input('children', $catModel->subcate ?? []));
        $catModel->save();

        if ($request->expectsJson() || $request->ajax()) {
            $arr = $catModel->toArray();
            $arr['children'] = $arr['subcate'] ?? [];
            return response()->json(['ok' => true, 'category' => $arr]);
        }

        return back()->with('success', 'Catégorie mise à jour: ' . $catModel->name);
    }

    public function destroy(Request $request, string $category)
    {
        $catModel = Category::find($category);
        
        if (!$catModel) {
            return response()->json(['ok' => false, 'message' => 'Catégorie introuvable pour suppression.'], 404);
        }

        $catModel->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('success', 'Catégorie supprimée.');
    }

    private function normalizeChildren($children): array
    {
        if (!is_array($children)) {
            return [];
        }
        
        $norm = [];
        $nextId = 1;
        
        foreach ($children as $child) {
            if (is_string($child)) {
                $name = trim($child);
                if ($name === '') {
                    continue;
                }
                $norm[] = [
                    'id' => $nextId++,
                    'name' => $name,
                    'slug' => Str::slug($name),
                ];
                continue;
            }
            
            if (!is_array($child)) {
                continue;
            }
            
            $name = trim((string)($child['name'] ?? ''));
            if ($name === '') {
                continue;
            }
            
            $slug = trim((string)($child['slug'] ?? ''));
            
            $norm[] = [
                'id' => $nextId++,
                'name' => $name,
                'slug' => $slug !== '' ? $slug : Str::slug($name),
            ];
        }
        
        return $norm;
    }

    public function storeSubcategory(Request $request, string $category)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:120',
            'slug'   => 'nullable|string|max:160',
        ]);

        $catModel = Category::find($category);
        
        if (!$catModel) {
            return response()->json(['ok' => false, 'message' => 'Catégorie parente introuvable.'], 404);
        }

        $children = $catModel->subcate ?? [];
        $nextId = empty($children) ? 1 : (max(array_map(fn($x) => (int)($x['id'] ?? 0), $children)) + 1);
        
        $child = [
            'id' => $nextId,
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?? Str::slug($validated['name']),
        ];
        
        $children[] = $child;
        $catModel->subcate = $children;
        $catModel->save();
        
        return response()->json(['ok' => true, 'subcategory' => $child]);
    }

    public function editSubcategory(string $category, string $sub)
    {
        $catModel = Category::find($category);
        
        if (!$catModel) {
            return response()->json(['ok' => false, 'message' => 'Catégorie introuvable.'], 404);
        }

        foreach (($catModel->subcate ?? []) as $child) {
            if ((int)($child['id'] ?? 0) === (int)$sub) {
                return response()->json(['ok' => true, 'subcategory' => $child]);
            }
        }
        
        return response()->json(['ok' => false, 'message' => 'Sous-catégorie introuvable.'], 404);
    }

    public function updateSubcategory(Request $request, string $category, string $sub)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:120',
            'slug'   => 'nullable|string|max:160',
        ]);

        $catModel = Category::find($category);
        
        if (!$catModel) {
            return response()->json(['ok' => false, 'message' => 'Catégorie introuvable.'], 404);
        }

        $children = $catModel->subcate ?? [];
        $found = false;
        
        foreach ($children as &$child) {
            if ((int)($child['id'] ?? 0) === (int)$sub) {
                $child['name'] = $validated['name'];
                $child['slug'] = $validated['slug'] ?? ($child['slug'] ?? Str::slug($validated['name']));
                $found = true;
                $updatedChild = $child;
                break;
            }
        }
        unset($child);
        
        if (!$found) {
            return response()->json(['ok' => false, 'message' => 'Sous-catégorie introuvable pour mise à jour.'], 404);
        }
        
        $catModel->subcate = $children;
        $catModel->save();
        
        return response()->json(['ok' => true, 'subcategory' => $updatedChild]);
    }

    public function destroySubcategory(Request $request, string $category, string $sub)
    {
        $catModel = Category::find($category);
        
        if (!$catModel) {
            return response()->json(['ok' => false, 'message' => 'Catégorie introuvable.'], 404);
        }

        $children = array_values(array_filter(
            $catModel->subcate ?? [], 
            fn($child) => (int)($child['id'] ?? 0) !== (int)$sub
        ));
        
        $catModel->subcate = $children;
        $catModel->save();
        
        return response()->json(['ok' => true]);
    }
}