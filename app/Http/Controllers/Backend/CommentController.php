<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function StoreComment(Request $request) {
        Comment::insert([
            'user_id' => $request->user_id,
            'post_id' => $request->post_id,
            'message' => $request->message,
            'created_at' => Carbon::now()
        ]);

        $notification = array(
            'message' => 'Comment Added Successfully Admin Will Approved',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
