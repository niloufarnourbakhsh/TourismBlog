<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    //
    public function __invoke(Post $post,Photo $photo)
    {
        if (Photo::where('post_id',$post->id)->count() >1 ){

            Storage::disk('public')->delete($photo->path);
            $photo->delete();
            Session::flash('photo_deleted','تصویر مورد نظر پاک شد');
            return redirect()->back();
        }
        Session::flash('photo_deleted', 'متاسفانه تعداد عکسای موجود فقط یکی است و شما قادر به پاک کردن تصویر نیستید');
        return redirect()->back();

    }
}
