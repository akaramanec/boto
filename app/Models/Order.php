<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const STATUS_OPEN = 1;
    public const STATUS_CLOSE = 0;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(TelegramUser::class, 'user_id', 'id');
    }
}
