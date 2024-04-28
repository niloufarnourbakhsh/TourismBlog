<?php

namespace App\Listeners;

use App\Events\DeleteNotificationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Psy\Readline\Hoa\Event;

class DeleteNotificationListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DeleteNotificationEvent $event): void
    {
        $post=$event->getPost();
        $like=$event->getLike();
        foreach ($post->user->notifications as $notification){
            $notification->where('data','like',"%$like->id%")->delete();
        }
    }
}
