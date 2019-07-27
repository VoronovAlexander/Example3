<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    protected $paginator;
    protected $entityManager;

    /**
     * Конструктор репозитория
     *
     * @param RegistryInterface $registry
     * @param EntityManagerInterface $entityManager
     * @param PaginatorInterface $paginator
     */
    public function __construct(RegistryInterface $registry, EntityManagerInterface $entityManager, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Post::class);
        $this->paginator = $paginator;
        $this->entityManager = $entityManager;
    }

    /**
     * Получить пагинатор
     *
     * @param int $page
     * @param int $per_page
     * @return Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination
     */
    public function getIndex(int $page = 1, int $per_page = 15): SlidingPagination
    {
        $postsQuery = $this->createQueryBuilder('post')
            ->orderBy('post.id', 'DESC')
            ->getQuery();

        $paginaton = $this->paginator->paginate(
            $postsQuery,
            $page,
            $per_page
        );

        return $paginaton;
    }

    /**
     * Создать новый пост
     *
     * @param array $data
     * @return Post
     */
    public function create(Post $post): Post
    {
        $em = $this->entityManager;
        $em->persist($post);
        $em->flush();

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
        $post = $this->find($id);
        return $post;
    }

    public function generateFile(): string
    {
        $path = uniqid() . ".csv";
        $file = fopen($path, "w");

        $title = sprintf("Название; Содержание\r\n");
        $title = mb_convert_encoding($title, 'UTF-8');
        fwrite($file, $title);

        $batchSize = 100;
        $i = 0;
        $q = $this->entityManager->createQuery('select p from App\Entity\Post p');
        $iterableResult = $q->iterate();
        foreach ($iterableResult as $row) {
            $post = $row[0];

            $row = sprintf("%s;%s\r\n", $post->getName(), $post->getContent());
            $row = mb_convert_encoding($row, 'UTF-8');
            fwrite($file, $row);

            if (($i % $batchSize) === 0) {
                $this->entityManager->flush(); // Executes all updates.
                $this->entityManager->clear(); // Detaches all objects from Doctrine!
            }
            ++$i;
        }
        $this->entityManager->flush();

        fclose($file);

        return $path;
    }

}
