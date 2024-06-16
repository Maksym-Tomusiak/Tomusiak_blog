<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Blog\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\BlogPostCreateRequest;
use App\Http\Requests\BlogPostUpdateRequest;
use App\Jobs\BlogPostAfterCreateJob;
use App\Jobs\BlogPostAfterDeleteJob;
use App\Models\BlogPost;
use App\Repositories\BlogCategoryRepository;
use App\Repositories\BlogPostRepository;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    use DispatchesJobs;
    /**
     * @var BlogPostRepository
     */
    private $blogPostRepository;
    /**
     * @var BlogCategoryRepository
     */
    private $blogCategoryRepository; // властивість через яку будемо звертатись в репозиторій категорій
    public function __construct()
    {
        parent::__construct();
        $this->blogPostRepository = app(BlogPostRepository::class); //app вертає об'єкт класа
        $this->blogCategoryRepository = app(BlogCategoryRepository::class);
    }
    public function index()
    {
        $posts = BlogPost::with(['user', 'category'])->get();
        return $posts;
    }

    public function show(string $id)
    {
        $item = BlogPost::with(['user', 'category'])->get()->find($id);
        if (empty($item)) {                         //помилка, якщо репозиторій не знайде наш ід
            abort(404);
        }
        return $item;
    }

    public function delete(string $id)
    {
        $result = BlogPost::destroy($id); //софт деліт, запис лишається

        if (!$result) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        BlogPostAfterDeleteJob::dispatch($id)->delay(20);

        return response()->json(['success' => 'Post deleted successfully'], 200);
    }
    public function create(BlogPostCreateRequest $request)
    {
        $data = $request->input(); //отримаємо масив даних, які надійшли з форми

        $item = (new BlogPost())->create($data); //створюємо об'єкт і додаємо в БД

        if ($item) {
            $job = new BlogPostAfterCreateJob($item);
            $this->dispatch($job);
            return response()->json(['success' => 'Post created successfully'], 200);
        } else {
            response()->json(['error' => 'Post not found'], 404);
        }
    }
    public function update(BlogPostUpdateRequest $request, string $id)
    {
        $item = $this->blogPostRepository->getEdit($id);
        if (empty($item)) { //якщо ід не знайдено
            return response()->json(['error' => 'Post not found'], 404);
        }

        $data = $request->all(); //отримаємо масив даних, які надійшли з форми


        $result = $item->update($data); //оновлюємо дані об'єкта і зберігаємо в БД

        if ($result) {
            return response()->json(['success' => 'Post deleted successfully'], 200);
        } else {
            return response()->json(['error' => "The update could not be completed due to a conflict with the current state of the resource."], 409);
        }
    }
}
