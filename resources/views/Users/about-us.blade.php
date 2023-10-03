@extends('Includes.header')
@section('title','درباره ی ما')
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
                    <p>
                        سلام
                    </p>
                    <p>
                        من نیلوفر هستم
                    </p>
                    <p>
                        شهر به شهر ایران رو میگردم و خاطرات و تجربیاتمو با شما به اشتراک میذارم
                    </p>
                    <p>
                        خوشحال میشم که عضو وبسایتم بشید و تجربیات خودتونم با من به اشتراک بذارید.
                    </p>
                    <p>
                        برای شرکت در تورهای گردشگری ما میتونید از طریق صفحه ی تماس با ما با ما ارتباط برقرار کنید
                    </p>
                    <p>
                        عکسهای موجود در وبسایت توسط تیم ما گرفته شده و استفاده از اون با ذکر نام مشکلی ندارد
                    </p>
                    <p> امیدواریم که اطلاعات این پبسایت بتونه بهتون کمک کنه</p>
                    <p>
                        ممنونیم که از وبسایت ما دیدن کردید
                    </p>
                    <p>

                    </p>


                </div>
            </div>
            <div class="col-3"></div>
        </div>
    </div>
    @include('layouts.footer')
@endsection
