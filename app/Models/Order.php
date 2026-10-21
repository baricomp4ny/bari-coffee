<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public const STATUSES = [
        'baru' => 'Baru',
        'diproses' => 'Diproses',
        'selesai' => 'Selesai',
    ];

    public const PICKUP_TIMES = ['Secepatnya', '15 menit lagi', '30 menit lagi', '1 jam lagi'];

    protected $fillable = ['order_number', 'customer_name', 'pickup_time', 'total', 'status'];

    protected $casts = [
        'total' => 'integer',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
