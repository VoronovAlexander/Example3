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

    /**
     * Генерирование отчета
     *
     * @return string
     */
    public function generateFile(): string
    {
        $path = public_path(uniqid() . ".csv");
        $file = fopen($path, "w");

        $title = sprintf("Название; Содержание\r\n");
        $title = mb_convert_encoding($title, 'UTF-8');
        fwrite($file, $title);

        $this->model
            ->orderByDesc('id')
            ->chunk(100, function ($posts) use ($file) {
                foreach ($posts as $post) {
                    $row = sprintf("%s;%s\r\n", $post->name, $post->content);
                    $row = mb_convert_encoding($row, 'UTF-8');
                    fwrite($file, $row);
                }
            });

        fclose($file);

        return $path;
    }

}
