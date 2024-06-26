<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset('backend/assets/images/logo-icon.png') }}" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">Hotel</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <div class="parent-icon"><i class='bx bx-home-alt'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>
        @if (Auth::user()->can('team.menu'))
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="lni lni-users"></i>
                    </div>
                    <div class="menu-title">Manage Teams</div>
                </a>
                <ul>
                    @if (Auth::user()->can('team.all'))
                        <li> <a href="{{ route('all.team') }}"><i class='bx bx-radio-circle'></i>All Team</a>
                        </li>
                    @endif
                    @if (Auth::user()->can('team.add'))
                        <li> <a href="{{ route('add.team') }}"><i class='bx bx-radio-circle'></i>Add Team Member</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        @if (Auth::user()->can('bookarea.menu'))
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="lni lni-list"></i>
                    </div>
                    <div class="menu-title">Manage Book Area</div>
                </a>
                <ul>
                    @if (Auth::user()->can('bookarea.update'))
                        <li> <a href="{{ route('edit.book.area') }}"><i class='bx bx-radio-circle'></i>Edit Book
                                Area</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="lni lni-list"></i>
                </div>
                <div class="menu-title">Manage Testimonial</div>
            </a>
            <ul>
                <li> <a href="{{ route('all.testimonial') }}"><i class='bx bx-radio-circle'></i>All Testimonial</a>
                </li>
                <li> <a href="{{ route('add.testimonial') }}"><i class='bx bx-radio-circle'></i>Add Testimonial</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="lni lni-list"></i>
                </div>
                <div class="menu-title">Manage Blog</div>
            </a>
            <ul>
                <li> <a href="{{ route('blog.category') }}"><i class='bx bx-radio-circle'></i>Blog Category</a>
                </li>
                <li> <a href="{{ route('all.blog.post') }}"><i class='bx bx-radio-circle'></i>All Blog Post</a>
                </li>
                <li> <a href="{{ route('all.comment.post') }}"><i class='bx bx-radio-circle'></i>All Comment</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="lni lni-gallery"></i>
                </div>
                <div class="menu-title">Manage Gallery</div>
            </a>
            <ul>
                <li> <a href="{{ route('all.gallery.photo') }}"><i class='bx bx-radio-circle'></i>All Gallery Photo</a>
                </li>
                <li> <a href="{{ route('add.gallery.photo') }}"><i class='bx bx-radio-circle'></i>Add Gallery Photo</a>
                </li>
            </ul>
        </li>
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class='bx bx-support'></i>
                </div>
                <div class="menu-title">Contact Us</div>
            </a>
            <ul>
                <li> <a href="{{ route('admin.contact.message') }}"><i class='bx bx-radio-circle'></i>Contact Us
                        Messages </a>
                </li>

            </ul>
        </li>
        <li class="menu-label">Booking Manage</li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='lni lni-list'></i>
                </div>
                <div class="menu-title">Booking</div>
            </a>
            <ul>
                <li> <a href="{{ route('booking.list') }}"><i class='bx bx-radio-circle'></i>Booking List </a>
                </li>
                <li> <a href="{{ route('add.room.list') }}"><i class='bx bx-radio-circle'></i>Add Booking</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='lni lni-list'></i>
                </div>
                <div class="menu-title">Booking Report</div>
            </a>
            <ul>
                <li> <a href="{{ route('booking.report') }}"><i class='bx bx-radio-circle'></i>Booking Report </a>
                </li>
            </ul>
        </li>
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class='lni lni-list'></i>
                </div>
                <div class="menu-title">Manage Room List</div>
            </a>
            <ul>
                <li> <a href="{{ route('room.list') }}"><i class='bx bx-radio-circle'></i>Room List</a>
                </li>

            </ul>
        </li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="lni lni-list"></i>
                </div>
                <div class="menu-title">Manage Room Type</div>
            </a>
            <ul>
                <li> <a href="{{ route('list.room.type') }}"><i class='bx bx-radio-circle'></i>List Room Type</a>
                </li>
                <li> <a href="{{ route('add.room.type') }}"><i class='bx bx-radio-circle'></i>Add Room Type</a>
                </li>
            </ul>
        </li>

        <li class="menu-label">Manage Setting</li>

        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class='bx bx-bookmark-heart'></i>
                </div>
                <div class="menu-title">Role & Permisssion</div>
            </a>
            <ul>
                <li> <a href="{{ route('all.permission') }}"><i class='bx bx-radio-circle'></i>All Permisssion</a>
                </li>
                <li> <a href="{{ route('all.role') }}"><i class='bx bx-radio-circle'></i>All Roles</a>
                </li>

                <li> <a href="{{ route('add.role.permission') }}"><i class='bx bx-radio-circle'></i>Add Roles In
                        Permission</a>
                </li>

                <li> <a href="{{ route('all.role.permission') }}"><i class='bx bx-radio-circle'></i>All Roles In
                        Permission</a>
                </li>
            </ul>
        </li>
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class='lni lni-user'></i>
                </div>
                <div class="menu-title">Manage Admin User</div>
            </a>
            <ul>
                <li> <a href="{{ route('all.admin.user') }}"><i class='bx bx-radio-circle'></i>All Admin</a>
                </li>
                <li> <a href="{{ route('add.admin.user') }}"><i class='bx bx-radio-circle'></i>Add Admin</a>
                </li>
            </ul>
        </li>
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class='lni lni-cog'></i>
                </div>
                <div class="menu-title">Setting</div>
            </a>
            <ul>
                <li> <a href="{{ route('smtp.setting') }}"><i class='bx bx-radio-circle'></i>SMTP Setting</a>
                </li>
                <li> <a href="{{ route('site.setting') }}"><i class='bx bx-radio-circle'></i>Site Setting</a>
                </li>
            </ul>
        </li>
    </ul>
    <!--end navigation-->
</div>
