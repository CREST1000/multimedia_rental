<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id', 'customer_name', 'customer_email', 'customer_phone',
        'start_date', 'end_date', 'quantity', 'total_price', 'status', 'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    // Calculate number of days
    public function getDaysAttribute()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    // Format total price
    public function getFormattedTotalPriceAttribute()
    {
        return '₱' . number_format($this->total_price, 2);
    }
}