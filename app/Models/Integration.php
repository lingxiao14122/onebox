<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'platform_name',
        'access_token',
        'expires_in',
        'refresh_token',
        'refresh_expires_in',
        'account_email',
        'is_sync_enabled',
    ];
}
