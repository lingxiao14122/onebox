<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * Transaction must have a user
     */
    public function user()
    {
        return $this->hasOne(User::class);
    }

    /**
     * Transaction can have many item
     */
    public function items()
    {
        return $this->belongsToMany(Item::class);
    }
}
