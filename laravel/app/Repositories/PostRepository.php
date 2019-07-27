<?php

namespace App\Repositories;

use App\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class PostRepository extends BaseRepository
{

    /**
     * Конструктор репозитория
     */
    public function __construct(Post $post)
    {
        $this->model = $post;
    }

    /**
     * Получить пагинатор
     * 
     * @param int $page
     * @param int $per_page
     * @return LengthAwarePaginator
     */
    public function getIndex(int $page = 1, int $per_page = 15): LengthAwarePaginator
    {
        $posts = $this->model
            ->orderByDesc('id')
            ->paginate($per_page);

        $posts->each(function ($post) {
            $post->content = Str::limit($post->content, 70);
        });

        return $posts;
    }

    /**
     * Создать новый пост
     * 
     * @param array $data
     * @return Post
     */
    public function store(array $data): Post
    {
        $post = $this->model
            ->create($data);
        return $post;
    }

    /**
     * Получить для просмотра
     * 
     * @param int $id
     * @return Post
     */
    public function getShow(int $id)
    {
        $post = $this->getById($id);
        return $post;
    }

}
