<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\CommentsArticle;

class CommentsArticleUpdPublicFieldEvent
{
    use InteractsWithSockets, SerializesModels;

    public $commentsArticle;
    public $newValue;
    public $oldValue;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(CommentsArticle $commentsArticle, $newValue, $oldValue)
    {
        $this->commentsArticle = $commentsArticle;
        $this->newValue        = $newValue;
        $this->oldValue        = $oldValue;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        //return new PrivateChannel('channel-name');
        return [];
    }
}
