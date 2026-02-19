<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Download;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function index()
    {
        $downloads = Download::orderBy('created_at', 'desc')->get();
        return view('admin.downloads.index', compact('downloads'));
    }

    public function create()
    {
        return view('admin.downloads.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,zip|max:20480', // 20MB max
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->only(['title', 'description', 'category', 'status']);

        if ($request->hasFile('file')) {
            try {
                $path = $request->file('file')->store('downloads', 'public');
                if (!$path) {
                    throw new \Exception('File upload failed - store returned false');
                }
                $data['file_path'] = $path;
                \Illuminate\Support\Facades\Log::info('File uploaded successfully: ' . $path);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('File upload error: ' . $e->getMessage());
                return back()->withErrors(['file' => 'Erreur lors du téléchargement du fichier: ' . $e->getMessage()])->withInput();
            }
        }

        if ($request->hasFile('image')) {
            try {
                $path = $request->file('image')->store('downloads/images', 'public');
                $data['image_path'] = $path;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Image upload error: ' . $e->getMessage());
                // Continue without image if it fails, or handle as error
            }
        }

        Download::create($data);

        return redirect()->route('admin.downloads.index')->with('success', 'Document ajouté avec succès.');
    }

    public function edit($id)
    {
        $download = Download::findOrFail($id);
        return view('admin.downloads.edit', compact('download'));
    }

    public function update(Request $request, $id)
    {
        $download = Download::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,zip|max:20480',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->only(['title', 'description', 'category', 'status']);

        if ($request->hasFile('file')) {
            // Delete old file
            if ($download->file_path && Storage::disk('public')->exists($download->file_path)) {
                Storage::disk('public')->delete($download->file_path);
            }
            $path = $request->file('file')->store('downloads', 'public');
            $data['file_path'] = $path;
        }

        if ($request->hasFile('image')) {
            // Delete old image
            if ($download->image_path && Storage::disk('public')->exists($download->image_path)) {
                Storage::disk('public')->delete($download->image_path);
            }
            $path = $request->file('image')->store('downloads/images', 'public');
            $data['image_path'] = $path;
        }

        $download->update($data);

        return redirect()->route('admin.downloads.index')->with('success', 'Document mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $download = Download::findOrFail($id);
        
        if ($download->file_path && Storage::disk('public')->exists($download->file_path)) {
            Storage::disk('public')->delete($download->file_path);
        }
        if ($download->image_path && Storage::disk('public')->exists($download->image_path)) {
            Storage::disk('public')->delete($download->image_path);
        }

        $download->delete();

        return redirect()->route('admin.downloads.index')->with('success', 'Document supprimé.');
    }
}