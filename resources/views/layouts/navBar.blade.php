
<nav class="navbar p-2 flex-row justify-content-between">
    <div class="navbar-brand p-2">
        <a href="{{route('main')}}" class="text-white p-5">

            Tourism
            <i class="fa-solid fa-person-walking-luggage text-white"></i>
            blog
        </a>
    </div>
    <ul class="nav my-nav-style p-3" >
        @if(\Illuminate\Support\Facades\Auth::check())
            @if(\Illuminate\Support\Facades\Auth::user()->IsAdmin())
                <li class="nav-item"><a href="{{route('posts.index')}}" class="nav-link text-white">  مدیریت</a></li>
            @endif
        @endif
        <li class="nav-item"><a href="{{route('gallery')}}" class="nav-link text-white"> گالری </a></li>
        <li class="nav-item"><a href="{{route('contact.us')}}" class="nav-link text-white">تماس با ما</a></li>
        <li class="nav-item"><a href="{{route('about.us')}}" class="nav-link text-white"> درباره ی ما </a></li>
    </ul>
    <div class="navbar-brand  p-2">
        @if(\Illuminate\Support\Facades\Auth::check())
            <form action="{{route('logout')}}" method="post">
                @csrf
                <button type="submit" @class(['btn','btn-link','text-white'])>
                    <i @class(['fas','fa-sign-out-alt','fa-2x'])></i>
                </button>
            </form>
        @else
            <ul class="dropdown nav-item">

                <li class="nav-item">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
    </div>

</nav>


