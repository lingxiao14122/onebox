<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
    ];

    /**
     * Transaction must have a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Transaction can have many item
     */
    public function items()
    {
        return $this->belongsToMany(Item::class)->using(ItemTransaction::class);
    }
}
