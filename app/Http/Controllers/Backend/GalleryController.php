<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class GalleryController extends Controller
{
    public function AllGalleryPhoto(){
        $photos = Gallery::latest()->get();
        return view('backend.gallery.all_photo',compact('photos'));
    }

    public function AddGalleryPhoto(){
        return view('backend.gallery.add_photo');
    }

    public function StoreGalleryPhoto(Request $request) {
        $images = $request->file('photo_name');

        foreach ($images as $img) {
            $name_gen = hexdec(uniqid()) . '.' . $img->getClientOriginalExtension();
            Image::make($img)->resize(550,550)->save('upload/gallery/' . $name_gen);
            $save_url = 'upload/gallery/' . $name_gen;

            Gallery::insert([
                'photo_name' => $save_url,
                'created_at' => Carbon::now()
            ]);
        }

        $notification = array(
            'message' => 'Gallery Photos Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.gallery.photo')->with($notification);
    }

    public function EditGalleryPhoto($id){

        $photo = Gallery::find($id);
        return view('backend.gallery.edit_photo',compact('photo'));

    }// End Method 

    public function UpdateGalleryPhoto(Request $request){

        $photo_id = $request->id;

        $item = Gallery::findOrFail($photo_id);
        $img = $item->photo_name;
        unlink($img);

        $img = $request->file('photo_name');

        $name_gen = hexdec(uniqid()).'.'.$img->getClientOriginalExtension();
        Image::make($img)->resize(550,550)->save('upload/gallery/'.$name_gen);
        $save_url = 'upload/gallery/'.$name_gen;

        Gallery::find($photo_id)->update([
            'photo_name' => $save_url, 
        ]); 

        $notification = array(
            'message' => 'Gallery Photo Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.gallery.photo')->with($notification);  

    }// End Method 

    public function DeleteGalleryPhoto($id){

        $item = Gallery::findOrFail($id);
        $img = $item->photo_name;
        unlink($img);

        Gallery::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Gallery Photo Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);


    }  

    public function DeleteMultipleGalleryPhoto(Request $request){

        $selectedItems = $request->input('selectedItem', []);

        foreach ($selectedItems as $itemId) {
            $item = Gallery::find($itemId);
            $img = $item->photo_name;
            unlink($img);
            $item->delete();
        }

        $notification = array(
            'message' => 'Selected Photos Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }
}
