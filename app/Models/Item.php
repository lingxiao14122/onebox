<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'sku',
        'image',
        'description',
        'purchase_price',
        'selling_price',
        'minimum_stock',
        'stock_count',
        'lead_time',
    ];

    /**
     * Item can have many transaction
     */
    public function transaction()
    {
        return $this->belongsToMany(Transaction::class)
            ->using(ItemTransaction::class)
            ->withPivot('quantity', 'from_count', 'to_count', 'id');
    }
}
