<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomBookedDate;
use App\Models\RoomNumber;
use App\Models\RoomType;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomListController extends Controller
{
    public function RoomList(){
        $room_number_list = RoomNumber::with(['room_type','last_booking.booking:id,check_in,check_out,status,code,name,phone'])
        ->orderBy('room_type_id','asc')
        ->leftJoin('room_types','room_types.id','room_numbers.room_type_id')
        ->leftJoin('booking_room_lists','booking_room_lists.room_number_id','room_numbers.id')
        ->leftJoin('bookings','bookings.id','booking_room_lists.booking_id')
        ->select(
            'room_numbers.*',
            'room_numbers.id as id',
            'room_types.name as type_name',
            'bookings.id as booking_id',
            'bookings.check_in as booking_check_in',
            'bookings.check_out as booking_check_out',
            'bookings.status as booking_status',
            'bookings.code as booking_no',
            'bookings.name as customer_name',
            'bookings.phone as customer_phone',
        )
        ->orderBy('room_types.id','asc')
        ->orderBy('bookings.id','asc')
        ->get();

        return view('backend.allRoom.roomList.room_list',compact('room_number_list'));
    }

    public function AddRoomList(){
        $roomType = RoomType::all();
        return view('backend.allRoom.roomList.add_room_list',compact('roomType'));
    }

    public function StoreRoomList(Request $request){

        if ($request->check_in >= $request->check_out) {
            $request->flash();
            $notification = array(
                'message' => 'You Enter Wrong date',
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification);
        }

        if($request->available_room < $request->number_of_rooms)
        {
            $request->flash();
            $notification = array(
                'message' => 'You Enter Maximum Number of Rooms!',
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification);
        }

        $room = Room::find($request['room_id']);
        if ($room->room_capacity < $request->person) {
            $request->flash();
            $notification = array(
                'message' => 'You Enter Maximum Number of Guest!',
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification);
        }

        
        $fromDate = Carbon::parse($request['check_in']);
        $toDate = Carbon::parse($request['check_out']);
        $nights = $fromDate->diffInDays($toDate);

        $subtotal = $room->price * $nights * $request->number_of_rooms;
        $discount = ($room->discount / 100) * $subtotal;
        $total = $subtotal - $discount;
        
        $code = rand(000000000,999999999);

        $data = new Booking();
        $data->rooms_id = $room->id;
        $data->user_id = Auth::user()->id;
        $data->check_in = date('Y-m-d',strtotime($request['check_in']));
        $data->check_out = date('Y-m-d',strtotime($request['check_out']));
        $data->person = $request->person;
        $data->number_of_rooms = $request->number_of_rooms;
        $data->total_night = $nights;

        $data->actual_price = $room->price;
        $data->subtotal = $subtotal;
        $data->discount = $discount;
        $data->total_price = $total;
        $data->payment_method = 'COD';
        $data->payment_status = 0;

        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->country = $request->country;
        $data->state = $request->state;
        $data->zip_code = $request->zip_code;
        $data->address = $request->address;
        $data->code = $code;
        $data->status = 0;
        $data->created_at = Carbon::now();
        $data->save();

        $sDate = date('Y-m-d',strtotime($request['check_in']));
        $eDate = date('Y-m-d',strtotime($request['check_out']));
        $elDate =Carbon::create($eDate)->subDay();
        $d_period = CarbonPeriod::create($sDate,$elDate);

        foreach($d_period as $period) {
            $booked_dates = new RoomBookedDate();
            $booked_dates->booking_id = $data->id;
            $booked_dates->room_id = $room->id;
            $booked_dates->book_date = date('Y-m-d',strtotime($period));
            $booked_dates->save();
        }

        $notification = array(
            'message' => 'Booking Added Successfully',
            'alert-type' => 'success'
        );


        return redirect()->back()->with($notification);
    }
}
