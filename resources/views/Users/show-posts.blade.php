@extends('Includes.header')
@section('title',$post->city->name)
@section('body')
    @include('layouts.navBar-2')
    <div class="container">
        <div class="row mb-5">
            <div class="col-4">
                <div class="row row-cols-1 p-3 m-3">
                    @foreach($post->photos as $photo)
                        <div class="col">
                            <div class="card toChoose">
                                <img src="{{url('/storage/'.$photo->path)}}" alt="" class="my-3 img-style img"
                                     style="max-height: 200px">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-8">
                <img src="{{url('/storage/'.$post->photos()->first()->path)}}" id="bigImage">
                <div class="bg-light-green p-3 my-4">
                    <h5 class="fw-bolder ">{{$post->city->name}}</h5>
                </div>
                <div class="bg-light-green p-3 my-4 fw-semibold">
                    {!! $post->body !!}
                </div>
                @if($post->food)
                    <div class="bg-light-green p-3 my-4 fw-semibold">
                        <h5 class="fw-bolder ">غذاهای سنتی</h5>
                        {{$post->food}}
                    </div>
                @endif
                @if($post->touristAttraction)
                    <div class="bg-light-green p-3 my-4 fw-semibold">
                        <h5 class="fw-bolder "> مکانهای دیدنی</h5>
                        {{$post->touristAttraction}}
                    </div>
                @endif
                <div class="bg-light-green d-flex flex-row p-2">
                    <div class="mx-4 pt-2"><i class="fa-solid fa-ellipsis fa-2x"></i></div>
                    <div class="d-flex flex-row mx-4">
                        <div class="pt-2"><i class="fa-regular fa-eye fa-2x text-secondary"></i></div>
                        <div class="pt-2 mx-2"><p class="fw-bold ">{{$view}}</div>
                    </div>
                    <div>
                        @if(\Illuminate\Support\Facades\Auth::check())
                            <span><form action="{{route('like.post',[$post->id])}}" method="post">
                            @csrf
                                    @if($is_liked)
                                        <button class="btn btn-link" type="submit"><span> <i
                                                    class="fa-solid fa-heart fa-2x text-danger"></i></span></button>
                                    @else
                                        <button class="btn btn-link" type="submit" ><span><i
                                                    class="fa-regular fa-heart fa-2x text-secondary"></i></span></button>
                                    @endif
                        </form>
                        @else
                           <div class="pt-2"><span><i class="fa-regular fa-heart fa-2x"></i></span></div>
                        @endif

                    </div>
                    <div class="pt-2 fw-bolder mx-1">{{ $post->likes()->count() }}</div>
                </div>
                <div class="my-4">
                    <div class="row">
                        <div class="col-2 bg-cream p-2 fw-semibold text-center rounded text-white"><span>نظرات</span>
                        </div>
                        <div class="col-10 top-border p-2 ">
                            <p class="text-center">ارسال نظر فقط برای اعضای پیج امکان پذیر است</p>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="row">
                        <div class="col-2"></div>
                        <div class="col-8">
                            <form action="{{route('comment.store',[$post->id])}}" method="post">
                                @csrf
                                @auth
                                    <textarea name="body" class="form-control"></textarea>

                                @else
                                    <textarea name="body" class="form-control" disabled></textarea>
                                @endauth
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-block bg-brown mx-2 my-4 text-white">ارسال
                                        نظر
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-2"></div>
                    </div>
                </div>
                <div>
                    @foreach($post->comments as $comment)
                        <div class="bg-light-green row">
                            <div class="col-11">
                                <p class="p-2">
                                    <span class="fw-semibold">{{$comment->user->name}}</span>
                                    {{$comment->body}}
                                </p>
                            </div>
                            <div class="col-1">
                                @if(\Illuminate\Support\Facades\Auth::check())
                                    <div class="d-flex flex-row">
                                        <span class="mt-2">{{$comment->likes()->count()}}</span>
                                        <form action="{{route('like.comment',[$comment->id])}}" method="post">
                                            @csrf
                                            <button class="btn" type="submit"><span>
                                                    <i class="fa-regular fa-heart"></i>
                                                    </span></button>
                                        </form>
                                    </div>

                                    @can('delete_comment',$comment)
                                        <form action="{{route('comment.delete',$comment->id)}}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-link" type="submit">
                                                <i class="fa-regular fa-square-minus "></i>
                                            </button>
                                        </form>
                                    @endcan
                                @endif

                            </div>
                            @endforeach

                        </div>
                </div>

            </div>
        </div>

    </div>
    @include('layouts.footer')
@endsection

@section('extraJs')
    <script>
        (function () {
            const BigImage = document.querySelector('#bigImage');
            let images = document.querySelectorAll('.toChoose');
            images.forEach((image) => image.addEventListener('click', function () {
                BigImage.src = this.querySelector('img').src;
            }));
        })();
    </script>
@endsection

