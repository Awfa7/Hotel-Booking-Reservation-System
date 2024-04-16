@php
    $setting = App\Models\SiteSetting::find(1);
@endphp

<div class="navbar-area">
    <!-- Menu For Mobile Device -->
    <div class="mobile-nav">
        <a href="{{ url('/') }}" class="logo">
            <img src="{{ asset($setting->logo) }}" class="logo-one" alt="Logo">
            <img src="{{ asset($setting->logo) }}" class="logo-two" alt="Logo">
        </a>
    </div>

    <!-- Menu For Desktop Device -->
    <div class="main-nav">
        <div class="container">
            <nav class="navbar navbar-expand-md navbar-light ">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset($setting->logo) }}" class="logo-one" alt="Logo">
                    <img src="{{ asset($setting->logo) }}" class="logo-two"
                        alt="Logo">
                </a>

                <div class="collapse navbar-collapse mean-menu" id="navbarSupportedContent">
                    <ul class="navbar-nav m-auto">
                        <li class="nav-item">
                            <a href="{{ url('/') }}" class="nav-link @if (Request::is('/')) active @endif">
                                Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('show.about.us') }}" class="nav-link @if (Request::is('about/us/show')) active @endif">
                                About
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('show.gallery.photo') }}" class="nav-link @if (Request::is('gallery/photo/show')) active @endif">
                                Gallery
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('blog.post.list') }}" class="nav-link @if (Request::is('blog/post/list')) active @endif">
                                Blog
                            </a>
                        </li>

                        @php
                            $rooms = App\Models\Room::latest()->get();
                        @endphp

                        <li class="nav-item">
                            <a href="{{ route('fRoom.all') }}" class="nav-link @if (Request::is('rooms')) active @endif">
                                All Rooms
                                <i class='bx bx-chevron-down'></i>
                            </a>
                            <ul class="dropdown-menu">
                                @foreach ($rooms as $room)
                                    <li class="nav-item">
                                        <a href="{{ url('room/details/' . $room->id) }}" class="nav-link @if (Request::is('room/details/' . $room->id)) active @endif">
                                            {{ $room['type']['name'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('show.contact.us') }}" class="nav-link @if (Request::is('contact/us/show')) active @endif">
                                Contact Us
                            </a>
                        </li>

                        
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div>
