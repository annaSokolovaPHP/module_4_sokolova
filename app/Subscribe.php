<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscribe extends Model
{
    protected $table = 'subscribe';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'email',

    ];

    public function rules($id = null)
    {
        return [
            'name'       => 'required',
            'email'        => 'required',
        ];
    }
}
