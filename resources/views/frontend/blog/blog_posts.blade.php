@extends('frontend.main_master')
@section('main')
    <!-- Inner Banner -->
    <div class="inner-banner inner-bg5">
        <div class="container">
            <div class="inner-title">
                <ul>
                    <li>
                        <a href="index.html">Home</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>All Posts</li>
                </ul>
                <h3>All Posts</h3>
            </div>
        </div>
    </div>
    <!-- Inner Banner End -->

    <!-- Blog Area -->
    <div class="blog-area pt-100 pb-70">
        <div class="container">
            <div class="section-title text-center">
                <span class="sp-color">BLOGS</span>
                <h2>Our Latest Blogs to the Intranational Journal at a Glance</h2>
            </div>
            <div class="row pt-45">
                @foreach ($posts as $post)
                    <div class="col-lg-4 col-md-6">
                        <div class="blog-item">
                            <a href="{{ url('blog/details/' . $post->post_slug) }}">
                                <img src="{{ asset($post->post_image) }}" alt="Images">
                            </a>
                            <div class="content">
                                <ul>
                                    <li>{{ $post->created_at->format('M d Y') }}</li>
                                    <li><i class='bx bx-user'></i>29K</li>
                                    <li><i class='bx bx-message-alt-dots'></i>15K</li>
                                </ul>
                                <h3>
                                    <a href="{{ url('blog/details/' . $post->post_slug) }}">{{ $post->post_title }}</a>
                                </h3>
                                <p>{{ $post->short_desc }}</p>
                                <a href="{{ url('blog/details/' . $post->post_slug) }}" class="read-btn">
                                    Read More
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="col-lg-12 col-md-12">
                    <div class="pagination-area">
                        {{ $posts->links('vendor.pagination.custom') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Blog Area End -->
@endsection
