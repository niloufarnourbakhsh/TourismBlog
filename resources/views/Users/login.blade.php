@extends('Includes.header')
@section('title','ورود به وبسایت')
@section('body')
    @include('layouts.navBar-2')
    <div class="clr mt-3"></div>
    <div class="container">
        <div class="row">
            <div class="col-4"></div>
            <div class="col-4 p-5 form-border rounded">
                <h5 class="my-2 text-center bg-for-headers p-2 rounded">ورود به وبسایت</h5>
                <div class="form-group">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div>
                            <label for="name">نام کاربری:</label>
                            <input id="name" type="text" name="name" class="form-control my-2 @error('name') is-invalid @enderror"  value="{{ old('name') }}" required autocomplete="name" autofocus>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div>
                            <label for="password"> رمز عبور</label>
                            <input type="password" class="form-control my-2" name="password" required autocomplete="current-password" id="password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="d-grid gap-2 mb-3">

                            <button class="btn btn-lg my-2 bg-dark-red text-white" type="submit" name="login">ورود به وبسایت</button>
                        </div>
                    </form>
                    <div class="my-2 text-center">
                        <a class="text-black" href="{{route('register')}}">عضویت در وبسایت</a>
                    </div>
                </div>

            </div>
            <div class="col-4"></div>
        </div>
    </div>
    @include('layouts.footer')
@endsection
