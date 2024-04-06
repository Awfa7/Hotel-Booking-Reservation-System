@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>

    

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Add Blog Post</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Add Blog Post</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="container">
            <div class="main-body">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body p-4">
                                <form class="row g-3" action="{{ route('store.blog.post') }}" id="myForm" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="col-md-6">
                                        <label for="input7" class="form-label">Blog Category</label>
                                        <select name="blog_category_id" id="input7" class="form-select">
                                            <option selected="">Select Category...</option>
                                            @foreach ($blogCategory as $item)
                                                <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="input1" class="form-label">Post Title</label>
                                        <input name="post_title" type="text" class="form-control" id="input1">
                                    </div>
                                    <div class="col-md-12">
                                        <label for="input11" class="form-label">Short Description</label>
                                        <textarea name="short_desc" class="form-control" id="input11" rows="3"></textarea>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="input11" class="form-label">Post Description</label>
                                        <textarea name="long_desc" class="form-control" id="myeditor"></textarea>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="input1" class="form-label">Post Image</label>
                                        <input type="file" name="post_image" id="post_image" class="form-control" />
                                    </div>

                                    <div class="col-md-6">

                                        <img id="showImage" src="{{ url('upload/no_image.jpg') }}" alt="Admin"
                                            class="rounded-circle p-1 bg-primary" height="80" width="80">
                                    </div>



                                    <div class="col-md-12">
                                        <div class="d-md-flex d-grid align-items-center gap-3">
                                            <button type="submit" class="btn btn-primary px-4">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .ck-editor__editable[role="textbox"] {
                /* Editing area */
                min-height: 200px;
            }
    </style>

    <script>
        ClassicEditor
            .create( document.querySelector( '#myeditor' ) )
            .catch( error => {
                console.error( error );
            } );
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#post_image').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#showImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            })
        });
    </script>
@endsection
