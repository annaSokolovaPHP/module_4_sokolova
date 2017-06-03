<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Event;
use App\Events\CommentsArticleUpdPublicFieldEvent;
use App\Events\CommentsArticleDeleteEvent;

class CommentsArticle extends Model
{
    protected $table = "commentsArticle";
    protected $fillable = [
        'content',
        'article_id',
        'author',
        'public',
        'parent_id',
        'level',
        'path',
        'order_parent',
        'down_like',
        'up_like',
        'total_like'
    ];

    protected $enum = ['YES','NO'];

    public function rules($id = null)
    {
        return [
            'article_id' => 'exists:articles,id',
            'author'     => 'exists:users,id',
            'public'     => 'required|in:YES,NO',
            'content'    => 'required'
        ];
    }

    public function getEnum(){
        return $this->enum;
    }

    public function article(){
        return $this->belongsTo('App\Article');
    }

    public function user(){
        return $this->hasOne('App\User', 'id', 'author');
    }

    public function update(array $attributes = [], array $options = [])
    {
        $newValue = isset($attributes['public']) ? $attributes['public'] : null;
        $oldValue = $this->public;
        $ret = parent::update($attributes, $options);
        //Если свойство public изменяется на NO, то для все дочерних комментариев свойство public тоже изменяется на NO
        if($newValue)
            event(new CommentsArticleUpdPublicFieldEvent($this, $newValue, $oldValue));
        return $ret;
    }


    public function getChildPath(){
        $hasChild = CommentsArticle::where('parent_id', $this->id)
            ->orderBy('path', 'desc')
            ->first();
        if(!empty($hasChild)){
            $pathArr           = explode('/', $hasChild->path);
            $lastKey           = count($pathArr) - 1;
            $pathArr[$lastKey] += 1;
            $pathArr[$lastKey] = str_pad($pathArr[$lastKey], 3, "0", STR_PAD_LEFT);
            $newPath           = implode($pathArr, '/');
            return $newPath;
        }else{
            return $this->path."/001";
        }
    }

    static function getParentPath($article_id){
        $hasComments = CommentsArticle::where('article_id', $article_id)
            ->orderBy('path', 'desc')
            ->first();
        if(!empty($hasComments)){
            $newPath = $hasComments->path +1;
            return str_pad($newPath, 3, "0", STR_PAD_LEFT);
        }else{
            return "001";
        }
    }
}
