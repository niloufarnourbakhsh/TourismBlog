@extends('Includes.header')
@section('title','گالری تصاویر')
@section('body')

    @include('layouts.navBar-2')
    <div class="clr mt-3"></div>
    <div class="mt-3 mb-4 container d-flex flex-column " id="image-section">
        <h5 class="p-2 pt-3 fw-bolder mb-3">گالری تصاویر</h5>
        <div class="mb-3 p-2 px-4 ">
            <ul class="d-flex flex-row align-content- ">
                <li><a href="{{route('gallery')}}" class="bg-dark-red text-white px-3 py-1 mx-2 rounded"> تمامی تصاویر</a></li>
                @foreach($categories as $category)
                    <li><a href="{{route('gallery',['category'=>$category->name])}}" class="bg-dark-red text-white px-2 py-1 mx-2 rounded">{{$category->name}}</a></li>
                @endforeach
            </ul>
        </div>
        <div class=" row row-cols-4 px-4">
            @foreach($posts as $post)
                <div class="col">
                    <div class="card">
                        <a href="{{route('posts.show',[$post->slug])}}">
                            <img src="{{url('storage/'.$post->photos()->first()->path)}}" class="img-size rounded" alt="" >
                            <p class="card-img-overlay text-center justify-content-center">
                                <span class="img-overlay p-2 mt-5 text-white px-5"> {{$post->city->name}}</span>

                            </p>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-3 row">
            <div class="col-5"></div>
            <div class="col-2">
                <p>
                    {{$posts->links()}}
                </p>
            </div>
            <div class="col-5"></div>

        </div>
    </div>

    @include('layouts.footer')
@endsection
