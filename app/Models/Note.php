<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = ['title', 'barber', 'haircut', 'price', 'paid'];
    
    public function scopeFromToday($query)
    {
        return $query->whereDate('created_at', today());
    }
}