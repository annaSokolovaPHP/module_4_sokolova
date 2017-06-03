<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table    = 'roles';
    protected $fillable = [
        'name',
        'display_name',
    ];
    public function user(){
        return $this->belongsTo('App\User', 'role_id', 'id');
    }

    public function rules($id = null){
        return [
            'name'         => 'required|unique:users,name,'.$id,
            'display_name' => 'required|unique:roles,display_name,'.$id,
        ];
    }
}
