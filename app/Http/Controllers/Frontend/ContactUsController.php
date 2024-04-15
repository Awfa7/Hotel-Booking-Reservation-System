<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public function ShowContactUs(){

        return view('frontend.contact.contact_us');
    }

    public function StoreContactUs(Request $request){
        Contact::insert([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Your Message Send Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function AdminContactMessage(){

        $contact = Contact::latest()->get();
        return view('backend.contact.contact_message',compact('contact'));

    }
}
