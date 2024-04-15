<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class BlogController extends Controller
{
    public function BlogCategory(){
        $categories = BlogCategory::latest()->get();
        return view('backend.category.blog_category',compact('categories'));
    }

    public function StoreBlogCategory(Request $request){
        BlogCategory::insert([
            'category_name' => $request->category_name,
            'category_slug' => strtolower(str_replace(' ','-',$request->category_name))
        ]);

        $notification = array(
            'message' => 'Blog Category Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function EditBlogCategory($id){
        $categories = BlogCategory::find($id);
        return response()->json($categories);
    }

    public function UpdateBlogCategory(Request $request){

        $cat_id = $request->cat_id;
        BlogCategory::find($cat_id)->update([
            'category_name' => $request->category_name,
            'category_slug' => strtolower(str_replace(' ','-',$request->category_name))
        ]);

        $notification = array(
            'message' => 'Blog Category Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function DeleteBlogCategory($id){
        BlogCategory::find($id)->delete();
        $notification = array(
            'message' => 'Blog Category Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }


    // **************  Blog post methods

    public function AllBlogPost() {
        $posts = BlogPost::latest()->get();

        return view('backend.post.all_post',compact('posts'));
    }

    public function AddBlogPost() {
        $blogCategory = BlogCategory::latest()->get();
        return view('backend.post.add_post',compact('blogCategory'));
    }

    public function StoreBlogPost(Request $request){
        $image = $request->file('post_image');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        Image::make($image)->resize(550,370)->save('upload/post/' . $name_gen);
        $save_url = 'upload/post/' . $name_gen;

        BlogPost::insert([
            'blog_category_id' => $request->blog_category_id,
            'user_id' => Auth::user()->id,
            'post_title' => $request->post_title,
            'post_slug' => strtolower(str_replace(' ','-',$request->post_title)),
            'post_image' => $save_url,
            'short_desc' => $request->short_desc,
            'long_desc' => $request->long_desc,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Blog Post Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.blog.post')->with($notification);
    }

    public function EditBlogPost($id){
        $blogCategory = BlogCategory::latest()->get();
        $post = BlogPost::find($id);

        return view('backend.post.edit_post',compact('blogCategory','post'));
    }

    public function UpdateBlogPost(Request $request) {
            $post_id = $request->id;
    
            if($request->file('post_image')){
                $image = $request->file('post_image');
                $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
                Image::make($image)->resize(550,670)->save('upload/post/' . $name_gen);
                $save_url = 'upload/post/' . $name_gen;
        
                BlogPost::findOrFail($post_id)->update([
                    'blog_category_id' => $request->blog_category_id,
                    'user_id' => Auth::user()->id,
                    'post_title' => $request->post_title,
                    'post_slug' => strtolower(str_replace(' ','-',$request->post_title)),
                    'post_image' => $save_url,
                    'short_desc' => $request->short_desc,
                    'long_desc' => $request->long_desc,
                    'created_at' => Carbon::now(),
                ]);
        
                $notification = array(
                    'message' => 'Blog Post Updated Successfully',
                    'alert-type' => 'success'
                );
        
                return redirect()->route('all.blog.post')->with($notification);
            } else {
                BlogPost::findOrFail($post_id)->update([
                    'blog_category_id' => $request->blog_category_id,
                    'user_id' => Auth::user()->id,
                    'post_title' => $request->post_title,
                    'post_slug' => strtolower(str_replace(' ','-',$request->post_title)),
                    'short_desc' => $request->short_desc,
                    'long_desc' => $request->long_desc,
                    'created_at' => Carbon::now(),
                ]);
        
                $notification = array(
                    'message' => 'Blog Post Updated Successfully',
                    'alert-type' => 'success'
                );
        
                return redirect()->route('all.blog.post')->with($notification);
            }
    }

    public function DeleteBlogPost($id) {
        $item = BlogPost::findOrFail($id);
        $img = $item->post_image;
        unlink($img);

        BlogPost::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Blog Post Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function BlogDetails($slug) {
        $blog = BlogPost::where('post_slug',$slug)->first();
        $comments = Comment::where('post_id',$blog->id)->where('status',1)->limit(5)->get();
        $bCategories = BlogCategory::latest()->get();
        $lPosts = BlogPost::latest()->limit(3)->get();

        return view('frontend.blog.blog_details',compact('blog','comments','bCategories','lPosts'));
    }

    public function BlogCategoryList($id) {
        $posts = BlogPost::where('blog_category_id',$id)->paginate(3);
        $Category_name = BlogCategory::where('id',$id)->first();
        $bCategories = BlogCategory::latest()->get();
        $lPosts = BlogPost::latest()->limit(3)->get();

        return view('frontend.blog.blog_category_list',compact('posts','bCategories','lPosts','Category_name'));
    }

    public function BlogPostList(){
        $posts = BlogPost::latest()->paginate(3);
        $bCategories = BlogCategory::latest()->get();
        $lPosts = BlogPost::latest()->limit(3)->get();

        return view('frontend.blog.blog_posts',compact('posts','bCategories','lPosts'));
    }
} 
