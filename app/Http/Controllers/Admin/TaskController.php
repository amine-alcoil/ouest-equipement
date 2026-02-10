<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    private function getUserId(Request $request)
    {
        // Try to get ID from multiple sources
        if (auth()->guard('admin')->check()) {
            return auth()->guard('admin')->id();
        }
        return session('admin_user.id') ?? $request->user()?->id;
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
            ]);

            $userId = $this->getUserId($request);
            Log::info('Task store attempt', ['userId' => $userId]);

            if (is_null($userId)) {
                return response()->json(['message' => 'Non autorisé : Utilisateur non identifié.'], 401);
            }

            $task = Task::create([
                'title' => $validated['title'],
                'status' => false,
                'user_id' => $userId,
            ]);

            return response()->json($task, 201);
        } catch (\Exception $e) {
            Log::error('Task store error: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $userId = $this->getUserId($request);
        if ($task->user_id !== $userId) {
            return abort(403);
        }

        $task->update($validated);

        return response()->json($task);
    }

    public function destroy(Request $request, Task $task)
    {
        $userId = $this->getUserId($request);
        if ($task->user_id !== $userId) {
            return abort(403);
        }

        $task->delete();
        return response()->json(null, 204);
    }

    public function toggle(Request $request, Task $task)
    {
        $userId = $this->getUserId($request);
        if ($task->user_id !== $userId) {
            return abort(403);
        }

        $task->update(['status' => !$task->status]);
        return response()->json(['id' => $task->id, 'status' => $task->status]);
    }
}