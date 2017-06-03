<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advertising extends Model
{
    public $timestamps = false;
    protected $table = 'advertising';
    protected $fillable = [
        'product_name',
        'price',
        'seller',
        'order'

    ];

    public function rules($id = null)
    {
        return [
            'product_name'  => 'required|max:50',
            'price'         => 'required|max:50',
            'seller'        => 'required|max:500',
            'order'         => 'required|numeric',
        ];
    }
}
