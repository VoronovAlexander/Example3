<?php

namespace App\Http\Controllers;

use App\Post;
use App\Repositories\PostRepository;
use Illuminate\Http\Request;

class PostController extends Controller
{

    private $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1|max:100',
        ]);

        $page = (int) $request->input('page', 1);
        $per_page = (int) $request->input('per_page', 15);

        $posts = $this->postRepository
            ->getIndex($page, $per_page);

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3',
            'content' => 'required|string|min:5',
        ]);

        $data = $request->input();
        
        $this->postRepository->store($data);

        return redirect(route('posts.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        
        $post = $this->postRepository
            ->getShow($id);

        return view('posts.show', compact('post'));
    }

    /**
     * Download csv.
     *
     * @return \Illuminate\Http\Response
     */
    public function download()
    {
        $path = public_path(uniqid() . ".csv");
        $file = fopen($path, "w");

        $title = sprintf("Название; Содержание\r\n");
        $title = mb_convert_encoding($title, 'UTF-8');
        fwrite($file, $title);

        Post::orderByDesc('id')
            ->chunk(100, function ($posts) use ($file) {
                foreach ($posts as $post) {
                    $row = sprintf("%s;%s\r\n", $post->name, $post->content);
                    $row = mb_convert_encoding($row, 'UTF-8');
                    fwrite($file, $row);
                }
            });

        fclose($file);

        return response()->download($path);
    }

}
