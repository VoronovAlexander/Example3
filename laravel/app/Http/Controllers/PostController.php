<?php

namespace App\Http\Controllers;

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
            'name' => 'required|string|min:3|max:255',
            'content' => 'required|string|min:5|max:5000',
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
        $path = $this->postRepository
            ->generateFile();

        return response()->download($path);
    }

}
