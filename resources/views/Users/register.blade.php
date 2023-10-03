@extends('Includes.header')
@section('title','عضویت در وبسایت')
@section('body')
    @include('layouts.navBar-2')
    <div class="clr mt-3"></div>
    <div class="container">
        <div class="row">
            <div class="col-4"></div>
            <div class="col-4 p-3 form-border rounded">
                <h5 class="my-2 text-center bg-for-headers p-2 rounded">عضویت به وبسایت</h5>
                <div class="form-group">
                    <form method="POST" action="{{ route('register') }}">
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
                            <label for="email"> ایمیل:</label>
                            <input id="email" type="text" name="email" class="form-control my-2 @error('email') is-invalid @enderror"  value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
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
                        <div>
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">تکرار رمز عبور</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">

                        </div>
                        <div class="d-grid gap-2">

                            <button class="btn btn-lg my-2 bg-dark-red text-white" type="submit" name="login">عضویت در وبسایت</button>
                        </div>
                    </form>
                    <div class="my-2 text-center">
                        <a class="text-black" href="{{route('login')}}"> ورود به وبسایت </a>
                    </div>
                </div>

            </div>
            <div class="col-4"></div>
        </div>
    </div>
    @include('layouts.footer')
@endsection
