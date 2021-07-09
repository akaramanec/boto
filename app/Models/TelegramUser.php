<?php

namespace App\Models;

use \Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TelegramUser extends Eloquent
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
