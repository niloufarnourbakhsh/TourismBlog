@extends('Includes.header')
@section('title',' تماس با ما')
@section('body')

    @include('layouts.navBar-2')
    <div class=" container">
        <div class="row mb-3">
            <div class="col-3">
                @if(\Illuminate\Support\Facades\Session::has('contact-us'))
                    <p class="bg-dark-red text-white">
                        {{Session('contact-us')}}
                    </p>
                @endif
            </div>
            <div class="col-6">
                <div class="py-4 px-4 border">
                    <form action="{{route('contact.us.submit')}}" method="post">
                        @csrf
                        <label>نام و نام خانوادگی:</label>
                        <input type="name" class="form-control my-3" name="name">
                        <label>ایمیل:</label>
                        <input type="email" class="form-control my-3" name="email">
                        <label>متن پیام:</label>
                        <textarea class="form-control my-3" name="body"></textarea>
                        <div class="d-grid gap-2 mt-5">
                            <button class="btn bg-dark-red text-white">ارسال پیام</button>
                        </div>

                    </form>
                </div>
            </div>
            <div class="col-3"></div>
        </div>
    </div>
    @include('layouts.footer')
@endsection
