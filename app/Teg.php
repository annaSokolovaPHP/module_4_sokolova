<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Article;

class Teg extends Model
{
    protected $table    = 'tegs';
    protected $fillable = [
        'name',
        'id'
    ];

    public function rules($id = null){
        return [
            'name' => 'required|unique'
        ];
    }


    public function articles(){
        return $this->belongsToMany('App\Article');
    }

}
