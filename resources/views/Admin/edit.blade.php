@extends('includes.admin-base')
@section('title',' ویرایش')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <h6 class="col-6 bg-orange p-2 text-white">ویرایش مطلب</h6>
            @if(\Illuminate\Support\Facades\Session::has("post-edition"))
                <p class="bg-dark-red text-white p-2 col-6">
                    {{Session('post-edition')}}
                </p>
            @endif
        </div>
        <div class="row">
            <div class="col-6 align-self-end d-flex flex-column p-4">
                <form action="{{route('posts.update',[$post->id])}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="title">عنوان</label>
                        <input class="form-control" name="title" id="title" value="{{$post->title}}">
                        @if($errors->first('title'))
                            <p class="text-danger fw-bolder">{{$errors->first('title')}}</p>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="city">شهر</label>
                        <input class="form-control" name="city" id="city" value="{{$post->city->name}}">
                        @if($errors->first('city'))
                            <p class="fw-bolder">{{$errors->first('city')}}</p>
                        @endif
                        <input type="hidden" value="{{$post->city->id}}" name="cityId">
                    </div>
                    <div class="form-group">
                        <label for="category_id"> دسته بندی</label>
                        <select name="category_id" class="form-control">
                            @foreach($categories as $category)
                                <option value="{{$category->id}}"
                                        @if($category->id===$post->categoty_id) selected @endif>{{$category->name}}</option>
                            @endforeach
                        </select>
                        @if($errors->first('category_id'))
                            <p class="fw-bolder">{{$errors->first('category_id')}}</p>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="body">متن پیام</label>
                        <textarea id="body" name="body" class="form-control">{{$post->body}}</textarea>
                        @if($errors->first('body'))
                            <p class="text-danger fw-bolder">{{$errors->first('body')}}</p>
                        @endif
                    </div>
                    <div @class(['form-group'])>

                        <label for="food"> غذاهای سنتی</label>
                        <input type="text" class="form-control" name="food"
                               id="food" value="{{$post->food}}">
                    </div>
                    <div @class(['form-group'])>
                        <label id="touristAttraction">مکانهای دیدنی</label>
                        <input type="text" class="form-control" name="touristAttraction"
                               id="touristAttraction" value="{{$post->touristAttraction}}">
                    </div>

                    <div class="form-control">
                        <label for="photo">تصاویر</label>
                        <input type="file" name="file[]" id="photo" accept="image/*"
                               multiple class="form-control ">
                        @if($errors->first('file'))
                            <p class="fw-bolder text-danger">{{$errors->first('file')}}</p>
                        @endif
                    </div>
                    <div class="d-grid gap-2 ">
                        <button class="btn bg-orange text-white" type="submit">ذخیره</button>
                    </div>

                </form>

            </div>
            <div class="col-6 mt-3">
                <div class=" row row-cols-2">
                    @foreach($post->photos as $photo)
                        <div class="col my-1">
                            <div class="card">
                                <img src="{{url('/storage/'.$photo->path)}}" class="card-img-top" alt="..."
                                     style="max-height: 200px">
                                <div class="card-footer bg-delete">
                                    <form action="{{route('photo',[$post->id,$photo->id])}}" method="post">
                                        @method('DELETE')
                                        @csrf
                                        <div class="d-grid gap-2">
                                            <button class="btn text-white">delete</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>

@endsection
