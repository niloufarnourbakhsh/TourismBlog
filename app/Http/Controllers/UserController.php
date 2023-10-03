<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()->where(['role_id' => 2])->paginate(5);
        return view('admin.users')->with('users', $users);

    }

    public function delete(User $user)
    {
     $user->delete();
     Session::flash('users-delete', 'کاربر مورد نظر حذف شد');
     return back();
    }
}
