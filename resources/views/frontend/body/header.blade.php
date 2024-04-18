@php
    $setting = App\Models\SiteSetting::find(1);
@endphp

<header class="top-header top-header-bg">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-10">
                <div class="header-left">
                    <ul>
                        <li>
                            <a href="#">{{ $setting->address }}</a>
                            <i class='bx bx-home-alt'></i>
                        </li>
                        <li>
                            <a href="tel:{{ $setting->phone }}">{{ $setting->phone }}</a>
                            <i class='bx bx-phone-call'></i>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-6 col-md-10">
                <div class="header-right">
                    <ul>

                        @auth
                            <li>
                                <i class='bx bxs-user-pin'></i>
                                <a href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li>
                                <i class='bx bxs-user-rectangle'></i>
                                <a href="{{ route('user.logout') }}">Logout</a>
                            </li>
                        @else
                            <li>
                                <i class='bx bxs-user-pin'></i>
                                <a href="{{ route('login') }}">Login</a>
                            </li>
                            <li>
                                <i class='bx bxs-user-rectangle'></i>
                                <a href="{{ route('register') }}">Register</a>
                            </li>
                        @endauth

                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
