<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\CreatePostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PostController extends AbstractController
{

    public function index(Request $request)
    {
        $page = $request->query
            ->getInt('page', 1);
        $per_page = $request->query
            ->getInt('per_page', 10);

        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->getIndex($page, $per_page);

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    public function show(int $id)
    {
        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);

        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    public function create()
    {
        $post = new Post();

        $form = $this->createForm(CreatePostType::class, $post, [
            'action' => $this->generateUrl('post_store'),
        ]);

        return $this->render('post/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function store(Request $request, ValidatorInterface $validator)
    {
        $post = new Post();

        $form = $this->createForm(CreatePostType::class, $post);

        $form->handleRequest($request);

        $post = $form->getData();

        $errors = $validator->validate($post);

        if (count($errors) > 0) {
            return $this->render('post/create.html.twig', [
                'form' => $form->createView(),
                'errors' => $errors,
            ]);
        }

        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->create($post);

        return $this->redirectToRoute('post_index');
    }

    public function download()
    {
        $path = $this->getDoctrine()
            ->getRepository(Post::class)
            ->generateFile();

        return $this->file($path);
    }
}
