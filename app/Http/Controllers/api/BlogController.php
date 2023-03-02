<?php

namespace App\Http\Controllers\api;

use App\Models\Blog;
use App\Models\BlogProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Blog as BlogResource;
use App\Http\Controllers\API\BaseController as BaseController;

class BlogController extends BaseController
{
    public function index()
    {
        $blogs = BlogProject::all();
        return $this->sendResponse(BlogResource::collection($blogs), 'Posts fetched.');
    }
    
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required'
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }
        $blog = BlogProject::create($input);
        return $this->sendResponse(new BlogResource($blog), 'Post created.');
    }
   
    public function show($id)
    {
        $blog = BlogProject::find($id);
        if (is_null($blog)) {
            return $this->sendError('Post does not exist.');
        }
        return $this->sendResponse(new BlogResource($blog), 'Post fetched.');
    }
    
    public function update(Request $request, BlogProject $blog)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required'
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }
        $blog->title = $input['title'];
        $blog->description = $input['description'];
        $blog->save();
        
        return $this->sendResponse(new BlogResource($blog), 'Post updated.');
    }
   
    public function destroy(BlogProject $blog)
    {
        $blog->delete();
        return $this->sendResponse([], 'Post deleted.');
    }
}
