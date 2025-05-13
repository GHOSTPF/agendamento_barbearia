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
    
    public function scopeByDate($query, $date)
    {
        if ($date) {
            return $query->whereDate('created_at', \Carbon\Carbon::createFromFormat('d/m/Y', $date));
        }
        return $query;
    }
    
    public function scopeByBarber($query, $barberName)
    {
        if ($barberName && $barberName !== 'Todos') {
            return $query->where('barber', $barberName);
        }
        return $query;
    }
    
    public function scopeByMonth($query, $month, $year)
    {
        return $query->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year);
    }
}