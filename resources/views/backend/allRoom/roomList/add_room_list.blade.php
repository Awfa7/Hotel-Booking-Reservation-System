@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>



    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Add Room List</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Add Room List</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="container">
            <div class="main-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body p-4">
                                <form method="POST" action="{{ route('store.room.list') }}" class="row g-3">
                                    @csrf
                                    <div class="col-md-4">
                                        <label for="roomtype_id" class="form-label">Room Type</label>
                                        <select name="room_id" id="room_id" class="form-select">
                                            <option selected="">Select Room Type...</option>
                                            @foreach ($roomType as $item)
                                                <option value="{{ $item->room->id }}"
                                                    {{ collect(old('roomtype_id'))->contains($item->id) ? 'selected' : '' }}>
                                                    {{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="check_in" class="form-label">Check In</label>
                                        <input type="date" name="check_in" class="form-control" id="check_in">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="check_out" class="form-label">Check Out</label>
                                        <input type="date" name="check_out" class="form-control" id="check_out">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="" class="form-label">Room</label>
                                        <input type="number" name="number_of_rooms" class="form-control">

                                        <input type="hidden" name="available_room" id="available_room"
                                            class="form-control">
                                        <div class="mt-2">
                                            <label for="">Availability <span
                                                    class="text-success availability"></span></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="person" class="form-label">Guest</label>
                                        <input type="text" name="person" class="form-control" id="person">
                                    </div>

                                    <h3 class="mt-3 mb-5 text-center">Customer Information</h3>

                                    <div class="col-md-4">
                                        <label for="" class="form-label">Name</label>
                                        <input type="text" class="form-control" name="name"
                                            value="{{ old('name') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="" class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email"
                                            value="{{ old('email') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="" class="form-label">Phone</label>
                                        <input type="text" class="form-control" name="phone"
                                            value="{{ old('phone') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="" class="form-label">Country</label>
                                        <input type="text" class="form-control" name="country"
                                            value="{{ old('country') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="" class="form-label">Zip Code</label>
                                        <input type="text" class="form-control" name="zip_code"
                                            value="{{ old('zip_code') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="" class="form-label">State</label>
                                        <input type="text" class="form-control" name="state"
                                            value="{{ old('state') }}">
                                    </div>
                                    <div class="col-md-12">
                                        <label for="" class="form-label">Address</label>
                                        <textarea class="form-control" name="address" rows="3">{{ old('address') }}</textarea>
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

    <script>
        $(document).ready(function() {
            $("#room_id").on('change', function() {
                $("#check_in").val('');
                $("#check_out").val('');
                $(".availability").text(0);
                $("#available_room").val(0);
            });

            $('#check_out').on('change', function() {
                getAvaility();
            })
        });

        function getAvaility() {
            var check_in = $('#check_in').val();
            var check_out = $('#check_out').val();
            var room_id = $('#room_id').val();

            var startDate = new Date(check_in);
            var endDate = new Date(check_out);

            if (startDate > endDate) {
                alert('Invalid Date');
                $("#check_out").val('');
                return false;
            }

            if (check_in != '' && check_out != '' && room_id != '') {
                $.ajax({
                    url: "{{ route('check.room.availability') }}",
                    data: {
                        room_id: room_id,
                        check_in: check_in,
                        check_out: check_out
                    },
                    success: function(data) {
                        $(".availability").html('<span class="text-success">' + data[
                            'available_room'] + ' Rooms</span>');
                        $("#available_room").val(data['available_room']);
                    }
                });
            } else {
                alert('Field must not be empty');
            }

        }
    </script>
@endsection
