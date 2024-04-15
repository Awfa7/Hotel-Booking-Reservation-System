@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>



    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Import Permission</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Import Permission</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto"><a href="{{ route('export.permission') }}"
                    class="btn btn-outline-warning px-5 radius-30">Export Xlsx</a></div>
        </div>
        <!--end breadcrumb-->
        <div class="container">
            <div class="main-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body p-4">
                                <form class="row g-3" action="{{ route('import.permission') }}" id="myForm" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="col-md-6">
                                        <label for="input1" class="form-label">Xlsx File Import</label>
                                        <input name="import_file" type="file" class="form-control">
                                    </div>

                                    <div class="col-md-12">
                                        <div class="d-md-flex d-grid align-items-center gap-3">
                                            <button type="submit" class="btn btn-primary px-4">Upload</button>
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
@endsection
