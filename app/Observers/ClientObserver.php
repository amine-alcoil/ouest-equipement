<?php

namespace App\Observers;

use App\Models\Client;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class ClientObserver
{
    public function created(Client $client)
    {
        if (Auth::check()) {
            Activity::create([
                'user_id' => Auth::id(),
                'description' => 'Nouveau client ajouté : ' . $client->name,
            ]);
        }
    }

    public function updated(Client $client)
    {
        if (Auth::check()) {
            Activity::create([
                'user_id' => Auth::id(),
                'description' => 'Client mis à jour : ' . $client->name,
            ]);
        }
    }

    public function deleted(Client $client)
    {
        if (Auth::check()) {
            Activity::create([
                'user_id' => Auth::id(),
                'description' => 'Client supprimé : ' . $client->name,
            ]);
        }
    }
}