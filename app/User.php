<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\UploadFilesController;
use Illuminate\Support\Facades\Storage;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token',];

    public function roles(){
        return $this->hasOne('App\Roles', 'id', 'role_id');
    }

    public function comments(){
        return $this->belongsTo('App\CommentsArticle');
    }

    public function requestUser(){
        return $this->belongsTo('App\RequestUser');
    }

    public function rules($id = null){
        return [
            'name'      => 'required|min:3|max:30|unique:users,name,'.$id,
            'email'     => 'required|email|unique:users,email,'.$id,
            'password'  => 'required',
            'role_id'   => 'required|exists:roles,id'
        ];
    }


    public function isAdmin($roleId){
        //foreach ($this->roles()->get() as $role)
        //{
            if ($this->roles->id == $roleId)
            {
                return true;
            }
       //}

        return false;
    }

    public function save(array $options = []){

        $this->role_id = $this->role_id ? $this->role_id : Settings::first()->default_user_role;
        parent::save();

    }
}
