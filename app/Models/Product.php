<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'sku', 'name', 'description', 'category', 'subcate',
        'tags', 'images', 'ratings', 'pdf', 'price', 'stock', 'status'
    ];

    protected $casts = [
        'tags' => 'array',
        'images' => 'array',
        'ratings' => 'array',
        'price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category', 'name');
    }

    public function tagRelations()
    {
        return $this->belongsToMany(Tag::class);
    }
}