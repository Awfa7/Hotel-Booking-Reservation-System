<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class TestimonialController extends Controller
{
    public function AllTestimonial() {
        $testimonial = Testimonial::latest()->get();

        return view('backend.testimonial.all_testimonial',compact('testimonial'));
    }

    public function AddTestimonial() {
        return view('backend.testimonial.add_testimonial');
    }

    public function StoreTestimonial(Request $request) {

        $image = $request->file('image');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        Image::make($image)->resize(50,50)->save('upload/testimonial/' . $name_gen);
        $save_url = 'upload/testimonial/' . $name_gen;

        Testimonial::insert([
            'name' => $request->name,
            'city' => $request->city,
            'message' => $request->message,
            'image' => $save_url, 
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Testimonial Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.testimonial')->with($notification);
    }

    public function EditTestimonial($id){
        $testimonial = Testimonial::findOrFail($id);

        return view('backend.testimonial.edit_testimonial',compact('testimonial'));
    }

    public function UpdateTestimonial(Request $request) {
        $testimonial_id = $request->id;

        if($request->file('image')){
            $image = $request->file('image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(50,50)->save('upload/testimonial/' . $name_gen);
            $save_url = 'upload/testimonial/' . $name_gen;
    
            Testimonial::findOrFail($testimonial_id)->update([
                'name' => $request->name,
                'city' => $request->city,
                'message' => $request->message,
                'image' => $save_url,
                'created_at' => Carbon::now(),
            ]);
    
            $notification = array(
                'message' => 'Testimonial Updated Successfully',
                'alert-type' => 'success'
            );
    
            return redirect()->route('all.testimonial')->with($notification);
        } else {
            Testimonial::findOrFail($testimonial_id)->update([
                'name' => $request->name,
                'city' => $request->city,
                'message' => $request->message,
                'created_at' => Carbon::now(),
            ]);
    
            $notification = array(
                'message' => 'Testimonial Updated Successfully',
                'alert-type' => 'success'
            );
    
            return redirect()->route('all.testimonial')->with($notification);
        }
    }

    public function DeleteTestimonial($id) {
        $item = Testimonial::findOrFail($id);
        $img = $item->image;
        unlink($img);

        Testimonial::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Testimonial Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
