@extends('Includes.admin-base')
@section('title',' کاربرها')
@section('content')
    <div class="mt-4 mx-2">
        <div class="row">
            <h6 class="col-3 bg-orange text-white p-2 rounded mb-3 mx-2">لیست اعضا</h6>
            <div class="col-3">
            @if(\Illuminate\Support\Facades\Session::has('users-delete'))
                <p class="bg-dark-red text-white col-6">
                    {{Session('users-delete')}}
                </p>
            @endif
            </div>
        </div>
        <div class="table-style">
            <table class="table">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>نام و نام خانوادگی</th>
                    <th>ایمیل</th>
                    <th>حذف</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{$user->id}}</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>
                            <form action="{{route('user.delete',[$user->id])}}" method="post">
                                @csrf
                                <input type="hidden" value="DELETE" name="_method">
                                <button class="btn " type="submit">
                                    <i class="fa-solid fa-trash-can red-color"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="number-part text-center my-1">
            <p>
                {{$users->links()}}
            </p>
        </div>
    </div>

@endsection
