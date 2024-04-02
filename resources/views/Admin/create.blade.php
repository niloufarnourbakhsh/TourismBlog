@extends('includes.admin-base')
@section('title','پست جدید')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-6 align-self-end d-flex flex-column p-4 my-3">
                <h6 class="bg-orange text-white p-2 rounded mb-3 mx-2"> افزودن مطلب جدید</h6>
                <form action="{{route('posts.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="title">عنوان</label>
                        <input class="form-control" name="title" id="title">
                        @if($errors->first('title'))
                            <p class="text-danger fw-bolder">{{$errors->first('title')}}</p>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="city">شهر</label>
                        <input class="form-control" name="city" id="city">
                        @if($errors->first('city'))
                            <p class="fw-bolder">{{$errors->first('city')}}</p>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="city"> دسته بندی</label>
                        <select name="category_id" class="form-control">
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                        @if($errors->first('category_id'))
                            <p class="fw-bolder">{{$errors->first('category_id')}}</p>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="body">متن پیام</label>
                        <textarea id="body" name="body" class="form-control">{{old('body')}}</textarea>
                        @if($errors->first('body'))
                            <p class="text-danger fw-bolder">{{$errors->first('body')}}</p>
                        @endif
                    </div>
                    <div @class(['form-group'])>

                        <label for="food" )> غذاهای سنتی</label>
                        <input type="text" class="form-control" name="food"
                               id="food" value="{{old('food')}}">
                    </div>
                    <div @class(['form-group'])>
                        <label id="touristAttraction">مکانهای دیدنی</label>
                        <input type="text" class="form-control" name="touristAttraction"
                               id="touristAttraction" value="{{old('touristAttraction')}}">
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
            <div class="col-6"></div>
        </div>
    </div>

@endsection
