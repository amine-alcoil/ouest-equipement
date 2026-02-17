<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    protected $table = 'devis';
    
    protected $fillable = [
        'ref_id', 'client_id', 'name', 'email', 'phone', 'company',
        'type', 'product', 'quantity', 'message', 
        'requirements', 'status', 'date',
        'type_exchangeur','cuivre_diametre','pas_ailette',
        'hauteur_mm','largeur_mm','longueur_mm','longueur_totale_mm',
        'collecteur1', 'collecteur2',
        'collecteur1_diametre','collecteur2_diametre',
        'nombre_tubes','geometrie_x_mm','geometrie_y_mm','collecteur1_nb','collecteur2_nb',
        'attachments'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'cuivre_diametre' => 'decimal:2',
        'pas_ailette' => 'decimal:2',
        'hauteur_mm' => 'decimal:2',
        'largeur_mm' => 'decimal:2',
        'longueur_mm' => 'decimal:2',
        'longueur_totale_mm' => 'decimal:2',
        'collecteur1' => 'decimal:2',
        'collecteur2' => 'decimal:2',
        'collecteur1_diametre' => 'decimal:2',
        'collecteur2_diametre' => 'decimal:2',
        'geometrie_x_mm' => 'decimal:2',
        'geometrie_y_mm' => 'decimal:2',
        'nombre_tubes' => 'integer',
        'collecteur1_nb' => 'decimal:2',
        'collecteur2_nb' => 'decimal:2',
        'attachments' => 'array',
        'date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship with Client model
     */
    public function clientRelation()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Accessor for backward compatibility with views expecting 'id' field as ref_id
     */
    public function getIdAttribute()
    {
        return $this->attributes['ref_id'] ?? $this->attributes['id'] ?? null;
    }
}