<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function ListRoomType() {
        $all_data = RoomType::orderBy('id','desc')->get();
        return view('backend.allRoom.roomType.view_room_type',compact('all_data'));
    }

    public function AddRoomType() {
        return view('backend.allRoom.roomType.add_room_type');
    }

    public function StoreRoomType(Request $request) {
        $room_type_id = RoomType::insertGetId([
            'name' => $request->name,
            'created_at' => Carbon::now()
        ]);

        Room::insert([
            'room_type_id' => $room_type_id
        ]);

        $notification = array(
            'message' => 'Room Type Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('list.room.type')->with($notification);
    }
}
