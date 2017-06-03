<?php
namespace App\Observers;

use App\CommentsArticle;

class CommentsArticleObserver
{
    public function deleted(CommentsArticle $commentsArticle)
    {
        CommentsArticle::where('parent_id', $commentsArticle->id)->get()->each(function($commentArticle){
            $commentArticle->delete();
        });
    }
}