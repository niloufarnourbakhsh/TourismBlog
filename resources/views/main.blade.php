@extends('Includes.header')
@section('title',' وبسایت گردشگری')
@section('body')
<section id="showcase">
    <div class="cover">
        <div class="container">
            @include('layouts.navBar')
            <div class=" content d-flex flex-column text-white justify-content-end align-items-center pb-5">
                <h1>به وبسایت گردشگری من خوش آمدید</h1>
                <p class="h5">
                    ما اینجا با هم به جاهای مختلف ایران سفر میکنیم و تجربیاتمونو به اشتراک میذاریم
                </p>
            </div>
        </div>
    </div>
</section>

@endsection
