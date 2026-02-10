<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        
        $query = Tag::query();
        
        if ($q !== '') {
            $query->where(function($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('slug', 'like', "%{$q}%");
            });
        }

        $tags = $query->orderBy('id')->get();

        return response()->json(['ok' => true, 'tags' => $tags, 'q' => $q]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:40|unique:tags,name',
            'slug' => 'nullable|string|max:80|unique:tags,slug',
        ]);

        $tag = Tag::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?? null, // Auto-generated in model boot
        ]);

        return response()->json(['ok' => true, 'tag' => $tag]);
    }

    public function edit(string $tag)
    {
        $tagModel = Tag::find($tag);
        
        if (!$tagModel) {
            return response()->json(['ok' => false, 'message' => 'Tag introuvable.'], 404);
        }
        
        return response()->json(['ok' => true, 'tag' => $tagModel]);
    }

    public function update(Request $request, string $tag)
    {
        $tagModel = Tag::find($tag);
        
        if (!$tagModel) {
            return response()->json(['ok' => false, 'message' => 'Tag introuvable pour mise à jour.'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:40|unique:tags,name,' . $tag,
            'slug' => 'nullable|string|max:80|unique:tags,slug,' . $tag,
        ]);

        $tagModel->name = $validated['name'];
        if (isset($validated['slug'])) {
            $tagModel->slug = $validated['slug'];
        }
        $tagModel->save();

        return response()->json(['ok' => true, 'tag' => $tagModel]);
    }

    public function destroy(Request $request, string $tag)
    {
        $tagModel = Tag::find($tag);
        
        if (!$tagModel) {
            return response()->json(['ok' => false, 'message' => 'Tag introuvable pour suppression.'], 404);
        }

        $tagModel->delete();
        
        return response()->json(['ok' => true]);
    }
}