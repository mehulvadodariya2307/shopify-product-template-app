<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'user_id',
        'product_handle',
        'title',
        'body_html',
        'price',
        'products_json'
    ];
}
