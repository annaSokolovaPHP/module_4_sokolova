<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoriesArticles extends Model
{
    protected $table = 'categoriesArticles';
    protected $fillable = [
        'name',
        'slug',
        'moderation'
    ];
    static function findBySlug($slug)
    {
        return CategoriesArticles::where('slug', $slug)->First();
    }

    public function rules($id = null)
    {
        return [
            'name' => 'required|max:30|unique:categoriesArticles,name,'.$id,
            'slug' => 'required|max:15|unique:categoriesArticles,slug,'.$id,
        ];
    }
    public function article(){
        //return $this->belongsTo('App\Article','id','category_id');
        return $this->hasMany('App\Article','category_id','id');
    }

    public function getTop5Articles(){
        return $this->article()->orderBy('id', 'desc')->limit(5);
    }
}
