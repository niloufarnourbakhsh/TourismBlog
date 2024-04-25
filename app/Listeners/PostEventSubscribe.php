<?php

namespace App\Listeners;

use App\Events\DeletePhoto;
use App\Events\InsertPhoto;
use App\Models\Photo;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Storage;

class PostEventSubscribe
{
    public function subscribe(Dispatcher $event)
    {
        $event->listen(InsertPhoto::class, [PostEventSubscribe::class, 'createImagePart']);
        $event->listen(DeletePhoto::class, [PostEventSubscribe::class, 'deleteImagePart']);

    }

    public function createImagePart(InsertPhoto $event)
    {
        foreach ($event->images as $image) {
            $path = Storage::disk('public')->put('/images', $image);
            Photo::create(['path' => $path, 'post_id' => $event->getPostId()]);
        }
    }
    public function deleteImagePart(DeletePhoto $event)
    {
        $post = $event->getPost();
        foreach ($post->photos as $photo) {
            $fileName = $photo->path;
            Storage::disk('public')->delete($fileName);
        }
    }
}
