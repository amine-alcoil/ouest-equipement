<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class ProductObserver
{
    public function created(Product $product)
    {
        if (Auth::check()) {
            Activity::create([
                'user_id' => Auth::id(),
                'description' => 'Nouveau produit ajouté : ' . $product->name,
            ]);
        }
    }

    public function updated(Product $product)
    {
        if (Auth::check()) {
            Activity::create([
                'user_id' => Auth::id(),
                'description' => 'Produit mis à jour : ' . $product->name,
            ]);
        }
    }

    public function deleted(Product $product)
    {
        if (Auth::check()) {
            Activity::create([
                'user_id' => Auth::id(),
                'description' => 'Produit supprimé : ' . $product->name,
            ]);
        }
    }
}