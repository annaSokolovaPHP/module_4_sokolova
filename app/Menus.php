<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menus extends Model
{
    protected $table    = 'menus';
    protected $fillable = ['name'];

    public function items(){
        return $this->belongsTo('App\MenuItem', 'menu_id', 'id');
    }

    public function rules($id = null){
        return [
            'name' => 'required|max:20',
        ];
    }
}
