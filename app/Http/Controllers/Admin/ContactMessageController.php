<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage; // Assuming you have a ContactMessage model

class ContactMessageController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(10); // Uses ContactMessage model
        return view('admin.contact-messages', compact('messages'));
    }

    public function show(ContactMessage $message) // Uses ContactMessage model (Route Model Binding)
    {
        return view('admin.contact-messages-show', compact('message'));
    }

    /**
     * Update the status of the specified contact message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ContactMessage  $message
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, ContactMessage $message)
    {
        $request->validate([
            'status' => 'required|in:nouveau,en_cours,traite,ferme',
        ]);

        $message->status = $request->input('status');
        $message->save();

        return back()->with('success', 'Statut du message mis à jour avec succès.');
    }

    /**
     * Remove the specified contact message from storage.
     *
     * @param  \App\Models\ContactMessage  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return back()->with('success', 'Message supprimé avec succès.');
    }
}