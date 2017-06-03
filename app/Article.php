<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\UploadFilesController;
use Illuminate\Support\Facades\Auth;
use App\Teg;
class Article extends Model
{
    protected $table = 'articles';
    protected $fillable = [
        'title',
        'body',
        'slug',
        'category_id',
        'image',
        'count_read',
        'id',
        'analytics',
        'up_like',
        'down_like',
        'created_at'

    ];
    protected $enum = ['PUBLISHED','DRAFT','PENDING'];

    public function rules($id = null)
    {
        return [
            'title'       => 'required|min:3|max:255|unique:articles,title,'.$id,
            'body'        => 'required',
            'slug'        => 'required|max:20|unique:articles,slug,'.$id,
            'category_id' => 'required|exists:categoriesArticles,id',
            'image'       => 'ext:jpg,jpeg,image/jpeg',
        ];
    }

    public function uploadFile($formData){
        if (!empty($formData['delete_image']) && empty($formData['image'])) {
            UploadFilesController::deleteFile('image', $this);
        }
        if (!empty($formData['image'])) {
            UploadFilesController::uploadFile('image',
                $formData['image']->getClientOriginalExtension(),
                $formData['image'],
                $this,
                400);
        }
    }
    public function getEnum(){
        return $this->enum;
    }

    public function comments()
    {
        return $this->hasMany('App\CommentsArticle','article_id','id');
    }

    public function categories()
    {
        return $this->hasOne('App\CategoriesArticles', 'id','category_id');
        //return $this->belongsTo('App\CategoriesArticles');
    }

    static function findBySlug($slug)
    {
        return Article::where('slug', $slug)->First();
    }

    static function findByStatus($status)
    {
        return Article::all();
    }

    public function tegs()
    {
        return $this->belongsToMany('App\Teg');
    }

}
