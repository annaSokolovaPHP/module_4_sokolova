<?php

namespace App\Listeners;

use App\Events\CommentsArticleUpdPublicFieldEvent;
use App\Events\CommentsArticleDeleteEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\CommentsArticle;

class CommentsArticleListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CommentsArticleEvent  $event
     * @return void
     */
    public function updatePublicField(CommentsArticleUpdPublicFieldEvent $event)
    {
        if(isset($event->newValue) && $event->newValue == 'NO' && $event->newValue != $event->oldValue){
            CommentsArticle::where('parent_id', $event->commentsArticle->id)->get()->each(function($commentArticle){
                $commentArticle->update(['public'=>'NO']);
            });

        }
    }
}
