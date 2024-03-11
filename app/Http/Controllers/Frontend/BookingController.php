<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
}
