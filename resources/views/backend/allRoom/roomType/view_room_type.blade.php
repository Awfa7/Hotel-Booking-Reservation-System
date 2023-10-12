@extends('admin.admin_dashboard')
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Room Type List </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Room Type List </li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('add.room.type') }}" class="btn btn-outline-primary px-5 radius-30">Add Room Type</a>
            </div>
        </div>
        <!--end breadcrumb-->
        <hr />
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all_data as $key => $item)
                                @php
                                    $rooms = App\Models\Room::where('room_type_id', $item->id)->get();
                                @endphp
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td><img src="{{ !empty($item->room->image) ? url('upload/room_img/' . $item->room->image) : url('upload/no_image.jpg') }}"
                                            alt="" style="width: 50px; height: 50px;"></td>
                                    <td>{{ $item->name }}</td>

                                    @foreach ($rooms as $room)
                                    <td><a href="{{ route('edit.room', $room->id) }}"
                                        class="btn btn-warning px-3 radius-30">Edit</a>
                                    <a href="#"
                                        class="btn btn-danger px-3 radius-30" id="delete">Delete</a>
                                </td>
                                    @endforeach

                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <hr />

    </div>
@endsection
