@extends('Includes.admin-base')
@section('title','پست ها')
@section('content')
    <div class="mt-4 mx-2">
        <div class="container-fluid">
            <div class="row">
                <h6 class=" col-3 bg-green text-white p-2 rounded mb-3 mx-2"> مطالب منتشر شده</h6>
                <div class="col-3"></div>
                <div class="col-5">
                    @if(\Illuminate\Support\Facades\Session::has('post-deletion'))
                        <p class="bg-dark-red text-white p-2">
                            {{Session('post-deletion')}}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="table-style ">

            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>اسم شهر</th>
                    <th>متن</th>
                    <th>فعالسازی/غیرفعالسازی</th>
                    <th>نمایش</th>
                    <th>ویرایش</th>
                    <th>حذف</th>
                    <th>تاریخ انتشار پست</th>
                </tr>
                </thead>
                <tbody>

                @foreach($posts as $post)
                    <tr>
                        <td>{{$post->id}}</td>
                        <td>{{$post->city->name}}</td>
                        <td>{{\Illuminate\Support\Str::limit($post->body,115)}}</td>
                        <td>
                            @if($post->is_active===1)
                                <form action="{{route('posts.active',[$post->id])}}" method="post">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" value="{{0}}" name="is_active">
                                    <button class="btn bg-green text-white" type="submit">عدم نمایش</button>
                                </form>
                            @else
                                <form action="{{route('posts.active',[$post->id])}}" method="post">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" value="{{1}}" name="is_active">
                                    <button class="btn bg-green text-white" type="submit"> نمایش</button>
                                </form>
                            @endif
                        </td>
                        <td><a href="{{route('posts.show',[$post->slug])}}">
                                <i class="fa-regular fa-eye text-show py-2"></i>
                            </a></td>
                        <td><a href="{{route('posts.edit',[$post->id])}}">
                                <i class="fa-regular fa-pen-to-square text-edition py-2"></i>
                            </a></td>
                        <td>
                            <form action="{{route('posts.destroy',[$post->id])}}" method="post">
                                @csrf
                                @method('delete')
                                <button class="btn" type="submit"><i class="fa-regular fa-trash-can text-delete"></i>
                                </button>
                            </form>
                        </td>
                        <td>{{$post->created_at}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="number-part text-center my-1">
            <p>
                {{$posts->links()}}
            </p>
        </div>
    </div>
@endsection
