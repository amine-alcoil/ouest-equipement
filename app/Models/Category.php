<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'status', 'subcate'];
    
    protected $casts = [
        'subcate' => 'array', // Auto JSON encode/decode
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category', 'name');
    }
}