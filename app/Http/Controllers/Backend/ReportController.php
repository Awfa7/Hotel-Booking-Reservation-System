<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function BookingReport(){
        return view('backend.report.booking_report');
    }

    public function SearchBookingReport(Request $request){
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $booking = Booking::where('check_in','>=',$startDate)->where('check_out','<=' ,$endDate)->get();

        return view('backend.report.booking_search_date',compact('startDate','endDate','booking'));
    }
}
