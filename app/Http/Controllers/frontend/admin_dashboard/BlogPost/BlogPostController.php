<?php

namespace App\Http\Controllers\frontend\admin_dashboard\BlogPost;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use concat;
use Auth;
use DB;
use App\Models\blogposts;
use Illuminate\Validation\Rule;

class BlogPostController extends Controller
{
    public function addBlogPost(Request $request){
        $request->validate([
            'token'             => 'required|string',
            'title'             => 'required|string|max:255',
            // 'slug'              => 'required|string|max:255|unique:blog_posts,slug',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('blog_posts')->where(function ($query) use ($request) {
                    return $query->where('token', $request->token);
                }),
            ],
            'content'           => 'nullable|string',
            'excerpt'           => 'nullable|string',
            'author'            => 'nullable|string',
            'thumbnail'         => 'nullable|string',
            'category_id'       => 'nullable|string',
            'tags'              => 'nullable|string',
            'seo_title'         => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string',
            'meta_keywords'     => 'nullable|string|max:255',
            'published'         => 'nullable|boolean',
        ]);
        
        DB::beginTransaction();
        try {
            $postId                 = DB::table('blog_posts')->insertGetId([
                'token'             => $request->token,
                'SU_id'             => $request->SU_id ?? NULL,
                'title'             => $request->title,
                'slug'              => $request->slug,
                'content'           => $request->content,
                'excerpt'           => $request->excerpt,
                'author'            => $request->author,
                'thumbnail'         => $request->thumbnail,
                'category_id'       => $request->category_id,
                'tags'              => $request->tags,
                'seo_title'         => $request->seo_title,
                'meta_description'  => $request->meta_description,
                'meta_keywords'     => $request->meta_keywords,
                'published'         => $request->published ?? 0,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
            
            DB::commit();
            return response()->json([
                'message' => 'Blog post created successfully',
                'post_id' => $postId,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong while saving the blog post.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    
    public function getAllBlogPosts(Request $request){
        try {
            $posts = DB::table('blog_posts')->where('token', $request->token)->orderBy('created_at', 'desc')->get();
            return response()->json([
                'success' => true,
                'data' => $posts
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve blog posts.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function getSingleBlogPosts(Request $request){
        try {
            if($request->id){
                $posts = DB::table('blog_posts')->where('token', $request->token)->where('id', $request->id)->first();
            }else{
                $posts = DB::table('blog_posts')->where('token', $request->token)->where('slug', $request->slug)->first();
            }
            return response()->json([
                'success' => true,
                'data' => $posts
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve blog posts.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function updateBlogPost(Request $request){
        $request->validate([
            'token'             => 'required|string',
            'title'             => 'required|string|max:255',
            // 'slug'              => 'required|string|max:255|unique:blog_posts,slug',
            'slug' => [
                'required',
                'string',
                'max:255',
                // Rule::unique('blog_posts')->where(function ($query) use ($request) {
                //     return $query->where('token', $request->token);
                // }),
            ],
            'content'           => 'nullable|string',
            'excerpt'           => 'nullable|string',
            'author'            => 'nullable|string',
            'thumbnail'         => 'nullable|string',
            'category_id'       => 'nullable|string',
            'tags'              => 'nullable|string',
            'seo_title'         => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string',
            'meta_keywords'     => 'nullable|string|max:255',
            'published'         => 'nullable|boolean',
        ]);
        
        try {
            $check_Slug = DB::table('blog_posts')->where('token',$request->token)->where('slug',$request->slug)->where('id','!=',$request->id)->first();
            if($check_Slug != null){
                return response()->json([
                    'error'     => 'Slug Duplicate!',
                ], 500);
            }else{
                DB::table('blog_posts')->where('token',$request->token)->where('id',$request->id)->update([
                    'title'             => $request->title,
                    'SU_id'             => $request->SU_id ?? NULL,
                    'slug'              => $request->slug,
                    'content'           => $request->content,
                    'excerpt'           => $request->excerpt,
                    'author'            => $request->author,
                    'thumbnail'         => $request->thumbnail,
                    'category_id'       => $request->category_id,
                    'tags'              => $request->tags,
                    'seo_title'         => $request->seo_title,
                    'meta_description'  => $request->meta_description,
                    'meta_keywords'     => $request->meta_keywords,
                    'published'         => $request->published ?? 0,
                    // 'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
                
                return response()->json([
                    'message' => 'Blog post updated successfully',
                ], 201);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error'     => 'Something went wrong while updating the blog post.',
                'details'   => $e->getMessage()
            ], 500);
        }
    }
    
    public function deleteblogPost(Request $request){
        try {
            DB::table('blog_posts')->where('token', $request->token)->where('id', $request->id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Blog deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete blog posts.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}