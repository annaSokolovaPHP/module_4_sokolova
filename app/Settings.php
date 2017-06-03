<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\UploadFilesController;
class Settings extends Model
{
    protected $table = 'settings';
    public $timestamps = false;
    protected $fillable = [
        'menu_color',
        'background_color',
        'user_top_menu',
        'admin_top_menu',
        'admin_left_menu',
        'default_user_role',
        'admin_role',
        'menu_border_color'
    ];

    protected $enum = ['YES', 'NO',];

    public function rules($id = null)
    {
        return [
            'user_top_menu' => 'required|exists:menus,id',
            'admin_top_menu' => 'required|exists:menus,id',
            'admin_left_menu' => 'required|exists:menus,id',
            'default_user_role' => 'required|exists:menus,id',
            'admin_role' => 'required|exists:menus,id',
        ];
    }
    public function roles()
    {
        return $this->hasOne('App\Roles', 'id', 'default_user_role');
    }

    public function adminRoles()
    {
        return $this->hasOne('App\Roles', 'id', 'admin_role');
    }

    public function userTopMenu()
    {
        return $this->hasOne('App\Menus', 'id', 'user_top_menu');
    }

    public function adminTopMenu()
    {
        return $this->hasOne('App\Menus', 'id', 'admin_top_menu');
    }

    public function adminLeftMenu()
    {
        return $this->hasOne('App\Menus', 'id', 'admin_left_menu');
    }

}
