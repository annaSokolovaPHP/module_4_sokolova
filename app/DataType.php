<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataType extends Model
{
    protected $table = 'data_types';

    protected $fillable = [
        'name',
        'slug',
        'display_name_singular',
        'display_name_plural',
        'icon',
        'model_name',
    ];
    public function rows()
    {
        return $this->hasMany('App\DataRow');
    }

    public function rules($id = null)
    {
        return [
            'name'                  => 'required',
            'slug'                  => 'required|unique:data_types,slug,'.$id,
            'display_name_singular' => 'required|min:3|max:30',
            'display_name_plural'   => 'required|min:3|max:30',
            'model_name'            => 'required',
        ];
    }

    public function browseRows(){
        return $this->rows()->where('browse', 1)->orderBy('order');
    }

    public function readRows(){
        return $this->rows()->where('read', 1)->orderBy('order');
    }

    public function editRows(){
        return $this->rows()->where('edit', 1)->orderBy('order');
    }

    public function addRows(){
        return $this->rows()->where('add', 1)->orderBy('order');
    }
}
