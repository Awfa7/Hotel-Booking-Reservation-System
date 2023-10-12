<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\MultiImage;
use App\Models\Room;
use App\Models\RoomNumber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class RoomController extends Controller
{
    public function EditRoom($id) {
        $basic_facility = Facility::where('rooms_id',$id)->get();
        $multi_imgs = MultiImage::where('rooms_id',$id)->get();
        $editData = Room::find($id);
        $allRoomNo = RoomNumber::where('rooms_id',$id)->get();
        return view('backend.allRoom.rooms.edit_room', compact('editData','basic_facility','multi_imgs','allRoomNo'));
    }

    public function UpdateRoom(Request $request, $id){

        $room  = Room::find($id);
        // $room->room_type_id = $room->room_type_id;
        $room->total_adult = $request->total_adult;
        $room->total_child = $request->total_child;
        $room->room_capacity = $request->room_capacity;
        $room->price = $request->price;

        $room->size = $request->size;
        $room->view = $request->view;
        $room->bed_style = $request->bed_style;
        $room->discount = $request->discount;
        $room->short_desc = $request->short_desc;
        $room->description = $request->description; 

        /// Update Single Image 

        if($request->file('image')){

        $image = $request->file('image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(550,850)->save('upload/room_img/'.$name_gen);
        $room['image'] = $name_gen; 
        }

        $room->save();

        // Update for Facility Table

        if($request->facility_name == Null){
            $notification = array(
                'message' => 'Sorry! Not any Basic Facility Select',
                'alert-type' => 'error'
            );
    
            return redirect()->back()->with($notification);
        } else {
            Facility::where('rooms_id',$id)->delete();
            $facilities = Count($request->facility_name);
            for($i = 0; $i < $facilities; $i++){
                $facility_count = new Facility();
                $facility_count->rooms_id = $room->id;
                $facility_count->facility_name = $request->facility_name[$i];
                $facility_count->save();
            }
        }

        // Update Multi Image 

        if($room->save()){
            $files = $request->multi_img;
            if(!empty($files)){
                $sub_image = MultiImage::where('rooms_id',$id)->get()->toArray();
                MultiImage::where('rooms_id',$id)->delete();

            }
            if(!empty($files)){
                foreach($files as $file){
                    $imgName = date('YmdHi').$file->getClientOriginalName();
                    $file->move('upload/room_img/multi_img/',$imgName);
                    $sub_image['multi_img'] = $imgName;

                    $sub_image = new MultiImage();
                    $sub_image->rooms_id = $room->id;
                    $sub_image->multi_img = $imgName;
                    $sub_image->save();
                }

            }
        } // end if

        $notification = array(
            'message' => 'Room Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 

    }//End Method 

    public function MultiImageDelete($id) {
        $delete_data = MultiImage::where('id',$id)->first();

        if($delete_data){
            $imagePath = $delete_data->multi_img;

            // Check if the file exists
            if(file_exists($imagePath)){
                unlink($imagePath);
                echo "Image Unlinked Successfully";
            } else {
                echo "Image does not exist";
            }

            // delete the record from db
            MultiImage::Where('id',$id)->delete();
        }

        $notification = array(
            'message' => 'Multi Image Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function StoreRoomNumber(Request $request,$id) {
        $data = new RoomNumber();
        $data->rooms_id = $id;
        $data->room_type_id = $request->room_type_id;
        $data->room_no = $request->room_no;
        $data->status = $request->status;
        $data->save();

        $notification = array(
            'message' => 'Room Number Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function EditRoomNumber($id) {
        $edit_room_no = RoomNumber::find($id);
        return view('backend.allRoom.rooms.edit_room_number',compact('edit_room_no'));
    }

    public function UpdateRoomNumber(Request $request, $id) {
        $data = RoomNumber::find($id);
        $data->room_no = $request->room_no;
        $data->status = $request->status;
        $data->save();

        $notification = array(
            'message' => 'Room Number Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('list.room.type')->with($notification);
    }

    public function DeleteRoomNumber($id) {
        RoomNumber::find($id)->delete();

        $notification = array(
            'message' => 'Room Number Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('list.room.type')->with($notification);
    }
}
