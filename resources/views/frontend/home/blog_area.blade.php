@php
    $blog_posts = App\Models\BlogPost::latest()->limit(3)->get();
@endphp

<div class="blog-area pt-100 pb-70">
    <div class="container">
        <div class="section-title text-center">
            <span class="sp-color">BLOGS</span>
            <h2>Our Latest Blogs to the Intranational Journal at a Glance</h2>
        </div>
        <div class="row pt-45">
            @foreach ($blog_posts as $blog_post)
                <div class="col-lg-4 col-md-6 offset-lg-0 offset-md-3">
                    <div class="blog-item">
                        <a href="{{ url('blog/details/' . $blog_post->post_slug) }}">
                            <img src="{{ asset($blog_post->post_image) }}" alt="Images">
                        </a>
                        <div class="content">
                            <ul>
                                <li>{{ $blog_post->created_at->format('M d Y') }}</li>
                                <li><i class='bx bx-user'></i>29K</li>
                                <li><i class='bx bx-message-alt-dots'></i>15K</li>
                            </ul>
                            <h3>
                                <a href="{{ url('blog/details/' . $blog_post->post_slug) }}">{{ $blog_post->post_title }}</a>
                            </h3>
                            <p>{{ $blog_post->short_desc }}</p>
                            <a href="{{ url('blog/details/' . $blog_post->post_slug) }}" class="read-btn">
                                Read More
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
