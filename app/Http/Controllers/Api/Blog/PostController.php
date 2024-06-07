<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Blog\BaseController;
use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    public function index()
    {
        $posts = BlogPost::with(['user', 'category'])->get();

        return $posts;
    }
}
