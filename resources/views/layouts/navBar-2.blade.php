
<nav class="navbar flex-row justify-content-between navbar-color border-bottom mb-5 ">
    <div class="container">

    <ul class="nav my-nav-style pr-3 pl-3" >
        @can('is_admin')
            <li class="nav-item"><a href="{{route('posts.index')}}" class="nav-link text_red" >  مدیریت</a></li>
        @endcan
        <li class="nav-item"><a href="{{route('gallery')}}" class="nav-link text_red"> گالری </a></li>
        <li class="nav-item"><a href="{{route('contact.us')}}" class="nav-link text_red">تماس با ما</a></li>
        <li class="nav-item"><a href="{{route('about.us')}}" class="nav-link text_red"> درباره ی ما </a></li>
        <li>
            @if(\Illuminate\Support\Facades\Auth::check())
                <form action="{{route('logout')}}" method="post">
                    @csrf
                    <button type="submit"  class="btn btn-link text_red" >
                        <i class="fas fa-sign-out-alt fa-2x text_red"></i>
                    </button>
                </form>
            @else
                <ul class="dropdown nav-item text_red">

                    <li class="nav-item">
                        <a class="nav-link dropdown-toggle text_red" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-regular fa-user"></i>
                        </a>
                        <ul class="dropdown-menu" id="my-dropdown-style">
                            <li>
                                <a href="{{route('register')}}" class="dropdown-item">
                                    عضویت در وبسایت
                                </a>

                            </li>
                            <li>
                                <a href="{{route('login')}}" class="dropdown-item">
                                    ورود
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            @endif
        </li>
    </ul>
    <div class="navbar-brand ">



    </div>
        <div class="navbar-brand pl-2 pr-2">
            <a href="{{route('main')}}" class="text_red px-3 fw-bold">
                Tourism
                <i class="fa-solid fa-person-walking-luggage text_red"></i>
                blog
            </a>
        </div>
    </div>
</nav>


