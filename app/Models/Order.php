<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'orderCode',
        'productId',
        'quantity',
        'address',
        'shippingDate'
    ];

    public $timestamps = FALSE;
}
