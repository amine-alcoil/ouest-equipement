<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contact_messages'; // or 'contacts' if renamed
    
    protected $fillable = [
        'name', 'email', 'phone', 'message', 'status'
    ];
}