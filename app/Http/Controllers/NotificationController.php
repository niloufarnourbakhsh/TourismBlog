<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;

class NotificationController extends Controller
{
    public function notification()
    {
        $notifications=auth()->user()->notifications;

        return view('Admin.notifications')->with('notifications',$notifications);
    }

    public function markNotification($id)
    {

        auth()->user()
            ->unreadNotifications()
            ->where(['id'=>$id])
            ->update(['read_at'=>now()]);
        return redirect()->back();
    }
}
