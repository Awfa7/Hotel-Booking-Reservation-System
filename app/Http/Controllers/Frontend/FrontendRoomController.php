<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\MultiImage;
use App\Models\Room;
use App\Models\RoomBookedDate;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class FrontendRoomController extends Controller
{
    public function AllFrontendRoomList(){
        $rooms = Room::latest()->get();
        return view('frontend.room.all_rooms',compact('rooms'));
    }

    public function RoomDetailsPage($id){
        $roomDetails = Room::find($id);
        $multiImage = MultiImage::where('rooms_id',$id)->get();
        $facility = Facility::where('rooms_id',$id)->get();

        $otherRooms = Room::where('id' ,'!=',$id)->orderBy('id', 'DESC')->limit(2)->get();
        return view('frontend.room.room_details',compact('roomDetails','multiImage','facility', 'otherRooms'));
    }

    public function BookingSearch(Request $request){
        $request->flash();

        if($request->check_in == $request->check_out)
        {
            $notification = array(
                'message' => 'Something went to wrong',
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification);
        }

        $sDate = date('Y-m-d',strtotime($request->check_in));
        $eDate = date('Y-m-d',strtotime($request->check_out));
        $allDate = Carbon::create($eDate)->subDay();
        $d_period = CarbonPeriod::create($sDate,$allDate);
        $dt_array = [];

        foreach($d_period as $period) {
            array_push($dt_array, date('Y-m-d',strtotime($period)));
        }

        $check_date_booking_ids = RoomBookedDate::whereIn('book_date', $dt_array)->distinct()->pluck('booking_id')->toArray();

        $rooms = Room::withCount('room_numbers')->where('status',1)->get();

        return view('frontend.room.search_room',compact('rooms','check_date_booking_ids'));
    }
}
