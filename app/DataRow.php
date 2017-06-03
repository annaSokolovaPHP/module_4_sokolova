<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataRow extends Model
{
    public $timestamps = false;

    protected $table = 'data_rows';

        protected $fillable = [
            'data_type_id',
            'field',
            'type',
            'display_name',
            'browse',
            'edit',
            'read',
            'add',
            'textarea',
            'order'
        ];

    protected $enum = [
        'text',
        'name',
        'password',
        'checkbox',
        'select',
        'image',
        'date',
        'email',
        'radio',
        'textarea'
    ];

    public function getEnum(){
        return $this->enum;
    }

    public function dataType(){
        return $this->belongsTo('App\DataType');
    }

     public function rules($id = null){
        return [
            'data_type_id' => 'required|exists:data_types,id',
            'field'        => 'required|max:50',
            'type'         => 'required',
            'display_name' => 'required|max:30',
            'order'        => 'required|numeric',
        ];
    }
}
