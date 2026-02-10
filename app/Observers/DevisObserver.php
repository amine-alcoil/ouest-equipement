<?php

namespace App\Observers;

use App\Models\Devis;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class DevisObserver
{
    public function created(Devis $devis)
    {
        if (Auth::check()) {
            Activity::create([
                'user_id' => Auth::id(),
                'description' => 'Nouveau devis créé : ' . ($devis->ref_id ?? $devis->id),
            ]);
        }
    }

    public function updated(Devis $devis)
    {
        if (Auth::check()) {
            if ($devis->isDirty('status')) {
                Activity::create([
                    'user_id' => Auth::id(),
                    'description' => 'Statut du devis #' . ($devis->ref_id ?? $devis->id) . ' changé en ' . $devis->status,
                ]);
            } else {
                Activity::create([
                    'user_id' => Auth::id(),
                    'description' => 'Devis mis à jour : ' . ($devis->ref_id ?? $devis->id),
                ]);
            }
        }
    }

    public function deleted(Devis $devis)
    {
        if (Auth::check()) {
            Activity::create([
                'user_id' => Auth::id(),
                'description' => 'Devis supprimé : ' . ($devis->ref_id ?? $devis->id),
            ]);
        }
    }
}