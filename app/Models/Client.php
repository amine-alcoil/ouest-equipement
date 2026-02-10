<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'ref_id', 'name', 'company', 'logo', 'type', 'email', 'phone', 
        'siteweb', 'address', 'city', 'notes', 'status'
    ];

    public function devis()
    {
        return $this->hasMany(Devis::class);
    }
}