<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingRoomList;
use App\Models\Room;
use App\Models\RoomBookedDate;
use App\Models\RoomNumber;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Stripe\Stripe;
use Stripe\Charge;

class BookingController extends Controller
{
    public function Checkout(){
        if (Session::has('book_date')) {
            $book_data = Session::get('book_date');
            $room = Room::find($book_data['room_id']);

            $fromDate = Carbon::parse($book_data['check_in']);
            $toDate = Carbon::parse($book_data['check_out']);
            $nights = $fromDate->diffInDays($toDate);

            return view('frontend.checkout.checkout',compact('book_data','room','nights'));
        } else {
            $notification = array(
                'message' => 'No booking data found.',
                'alert-type' => 'error'
            );
    
            return redirect('/')->with($notification);
        }

        
    }

    public function StoreBooking(Request $request){
        $validateData = $request->validate([
            'check_in' => 'required',
            'check_out' => 'required',
            'person' => 'required',
            'number_of_rooms' => 'required',
        ], [
            'required' => 'The :attribute field is required.',
        ]);

        if($request->available_room < $request->number_of_rooms){
            $notification = array(
                'message' => 'The requested number of rooms is not available.',
                'alert-type' => 'error'
            );
    
            return redirect()->back()->with($notification);
        }

        Session::forget('book_date');

        $data = array();
        $data['number_of_rooms'] = $request->number_of_rooms;
        $data['available_room'] = $request->available_room;
        $data['person'] = $request->person;
        $data['check_in'] = date('Y-m-d',strtotime($request->check_in));
        $data['check_out'] =  date('Y-m-d',strtotime($request->check_out));
        $data['room_id'] = $request->room_id;

        Session::put('book_date',$data);

        return redirect()->route('checkout');
    }

    public function StoreCheckout(Request $request){
        dd(env('STRIPE_SECRET'));

        $this->validate($request,[
            'name' => 'required',
            'email' => 'required',
            'country' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            'payment_method' => 'required'
        ]);

        $book_data = Session::get('book_date');
        $fromDate = Carbon::parse($book_data['check_in']);
        $toDate = Carbon::parse($book_data['check_out']);
        $nights = $fromDate->diffInDays($toDate);

        $room = Room::find($book_data['room_id']);
        $subtotal = $room->price * $nights * $book_data['number_of_rooms'];
        $discount = ($room->discount / 100) * $subtotal;
        $total = $subtotal - $discount;
        
        $code = rand(000000000,999999999);


        if($request->payment_method == 'stripe')
        {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $s_pay = Charge::create([
                "amount" => $total * 100,
                "currency" => "EUR",
                "source" => $request->stripeToken,
                "description" => "Payment For Booking. Booking No " . $code,
            ]);

            if($s_pay['status'] == 'succeeded') {
                $payment_status = 1;
                $transaction_id = $s_pay->id;
            } else {
                $notification = array(
                    'message' => 'Sorry Payment Field.',
                    'alert-type' => 'error'
                );
        
                return redirect('/')->with($notification);
            }
        } else {
            $payment_status = 0;
            $transaction_id = '';
        }


        $data = new Booking();
        $data->rooms_id = $room->id;
        $data->user_id = Auth::user()->id;
        $data->check_in = date('Y-m-d',strtotime($book_data['check_in']));
        $data->check_out = date('Y-m-d',strtotime($book_data['check_out']));
        $data->person = $book_data['person'];
        $data->number_of_rooms = $book_data['number_of_rooms'];
        $data->total_night = $nights;
        $data->actual_price = $room->price;
        $data->subtotal = $subtotal;
        $data->discount = $discount;
        $data->total_price = $total;
        $data->payment_method = $request->payment_method;
        $data->transition_id = $transaction_id;
        $data->payment_status = $payment_status;
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

        $sDate = date('Y-m-d',strtotime($book_data['check_in']));
        $eDate = date('Y-m-d',strtotime($book_data['check_out']));
        $elDate =Carbon::create($eDate)->subDay();
        $d_period = CarbonPeriod::create($sDate,$elDate);

        foreach($d_period as $period) {
            $booked_dates = new RoomBookedDate();
            $booked_dates->booking_id = $data->id;
            $booked_dates->room_id = $room->id;
            $booked_dates->book_date = date('Y-m-d',strtotime($period));
            $booked_dates->save();
        }

        Session::forget('book_date');

        $notification = array(
            'message' => 'Booking Added Successfully',
            'alert-type' => 'success'
        );


        return redirect('/')->with($notification);
    }

    public function BookingList() {
        $allData = Booking::orderBy('id','desc')->get();
        return view('backend.booking.booking_list',compact('allData'));
    }

    public function EditBooking($id){
        $editData = Booking::with('room')->find($id);
        return view('backend.booking.edit_booking',compact('editData'));
    }

    public function UpdateBookingStatus(Request $request, $id) {
        $booking = Booking::find($id);
        $booking->payment_status = $request->payment_status;
        $booking->status = $request->status;
        $booking->save();

        $notification = array(
            'message' => 'Information Updated Successfully',
            'alert-type' => 'success'
        );


        return redirect()->back()->with($notification);
    }

    public function UpdateBooking(Request $request, $id) {
        if($request->available_room < $request->number_of_rooms)
        {
            $notification = array(
                'message' => 'This Number Of Rooms Not Available.',
                'alert-type' => 'error'
            );
    
    
            return redirect()->back()->with($notification);
        } 

        $data = Booking::find($id);

        $data->number_of_rooms = $request->number_of_rooms;
        $data->check_in = date('Y-m-d',strtotime($request->check_in));
        $data->check_out = date('Y-m-d',strtotime($request->check_out));

        $data->save();

        BookingRoomList::where('booking_id', $id)->delete(); 
        RoomBookedDate::where('booking_id',$id)->delete();

        $sDate = date('Y-m-d',strtotime($request->check_in));
        $eDate = date('Y-m-d',strtotime($request->check_out));
        $elDate =Carbon::create($eDate)->subDay();
        $d_period = CarbonPeriod::create($sDate,$elDate);

        foreach($d_period as $period) {
            $booked_dates = new RoomBookedDate();
            $booked_dates->booking_id = $data->id;
            $booked_dates->room_id = $data->rooms_id;
            $booked_dates->book_date = date('Y-m-d',strtotime($period));
            $booked_dates->save();
        }

        $notification = array(
            'message' => 'Booking Updated Successfully.',
            'alert-type' => 'success'
        );


        return redirect()->back()->with($notification);
    }

    public function AssignRoom($booking_id) {
        
        $booking = Booking::find($booking_id);

        $booking_date_array = RoomBookedDate::where('booking_id',$booking_id)->pluck('book_date')->toArray();

        $check_date_booking_ids = RoomBookedDate::whereIn('book_date',$booking_date_array)->where('room_id',$booking->rooms_id)->distinct()->pluck('booking_id')->toArray();

        $booking_ids = Booking::whereIn('id',$check_date_booking_ids)->pluck('id')->toArray();

        $assign_room_ids = BookingRoomList::whereIn('booking_id',$booking_ids)->pluck('room_number_id')->toArray();

        $room_numbers = RoomNumber::where('rooms_id',$booking->rooms_id)->whereNotIn('id',$assign_room_ids)->where('status','Active')->get();

        return view('backend.booking.assign_room',compact('booking','room_numbers'));
    }

    public function StoreAssignRoom($booking_id, $room_number_id){
        $booking = Booking::find($booking_id);
        $check_data = BookingRoomList::where('booking_id',$booking_id)->count();

        if($check_data < $booking->number_of_rooms){
            $assign_data = new BookingRoomList();
            $assign_data->booking_id = $booking_id;
            $assign_data->room_id = $booking->rooms_id;
            $assign_data->room_number_id = $room_number_id;
            $assign_data->save();

            $notification = array(
                'message' => 'Room Assign Successfully',
                'alert-type' => 'success'
            ); 

            return redirect()->back()->with($notification);   
        } else {

            $notification = array(
                'message' => 'Room Already Assign',
                'alert-type' => 'error'
            ); 
            return redirect()->back()->with($notification);   

        }
    }

    public function DeleteAssignRoom($id){

        $assign_room = BookingRoomList::find($id);
        $assign_room->delete();

        $notification = array(
            'message' => 'Assign Room Deleted Successfully',
            'alert-type' => 'success'
        ); 
        return redirect()->back()->with($notification); 

    }
}


