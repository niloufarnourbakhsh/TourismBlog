@include('includes.header')
<body id="admin">

    <div class="container-fluid pt-4 ">
        <div class="row m-2">
            <div class="col-2 d-flex flex-column bg-black  mb-3">
                <div class="py-4 border-bottom" id="admin-nav">
                    <ul class="nav flex-column justify-content-center">
                        <li class="nav-item pt-4 pb-2" >
                            <a href="{{route('main')}}" class="fw-bolder p-2 text-white">

                                Tourism
                                <i class="fa-solid fa-person-walking-luggage text-white"></i>
                                blog
                            </a>
                        </li>
                        <li class="nav-item pt-4 pb-2" >
                            <a href="{{route('posts.index')}}" class="text-white fw-semibold">
                                <i class="fa-solid fa-house"></i>
                                صفحه ی اصلی
                            </a>
                        </li>
                        <li class="nav-item pt-4 pb-2" >
                            <a href="{{route('notifications')}}"  class="text-white fw-semibold">
                                <i class="fa-regular fa-bell"></i>
                                اعلانها
                            </a>
                        </li>
                        <li class="nav-item pt-4 pb-2" >

                            <a href="{{route('posts.create')}}" class="text-white fw-semibold">
                                <i class="fa-regular fa-newspaper"></i>
                                افزودن مطلب جدید</a>
                        </li>
                        <li class="nav-item pt-4 pb-2" >
                            <a href="{{route('categories.index')}}" class="text-white fw-semibold" >
                                <i class="fa-solid fa-list"></i>
                                افزودن دسته بندی جدید
                            </a>
                        </li>
                        <li class="nav-item pt-4 pb-2" >
                            <a href="{{route('users')}}" class="text-white fw-semibold">
                                <i class="fa-solid fa-users"></i>
                                مدیریت اعضا</a>
                        </li>
                    </ul>

                </div>
            </div>

            <div class="col-10 bg-white-admin rounded mb-3" id="admin-content" >
                <div class="d-flex justify-content-between pt-5" id="admin-nav-2">
                    <span class="px-3">
                        <a href="" class="text-black">
                            مدیریت
                        </a>
                    </span>
                    <span class="px-3">
                        <form action="{{route('logout')}}" method="post">
                            @csrf
                            <button class="btn btn-link text-black" type="submit">
                                <i class="fas fa-sign-out-alt fa-2x" ></i>
                            </button>
                        </form>
                    </span>
                </div>
                <div class="admin-hr bg-black m-auto mt-3"></div>
                <div id="content">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript" src="{{asset('css\tinymce\tinymce.min.js')}}"></script>
<script>tinymce.init({selector: 'textarea'});</script>
</body>
</html>



