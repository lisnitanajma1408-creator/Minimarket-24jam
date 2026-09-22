<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'customer_name', 'role_label', 'rating', 'comment', 'photo', 'is_published'
    ];
}