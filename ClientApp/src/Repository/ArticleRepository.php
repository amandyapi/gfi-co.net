<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findArticles()
    {
        $result = null;
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT *
                FROM article a';

        $stmt = $conn->prepare($sql);

        $stmt->execute();

        $result = $stmt->fetchAll();
        return $result;
    }

    public function findArticle($id)
    {
        $result = null;
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT *
                FROM article a
                WHERE a.id = :id';

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            'id' => $id,
        ]);

        $result = $stmt->fetch();
        return $result;
    }
    
    public function findArticleByLang($lang)
    {
        $result = null;
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT *
                FROM article a
                WHERE a.lang = :lang';

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            'lang' => $lang,
        ]);

        $result = $stmt->fetch();
        return $result;
    }

    public function updateArticle($id, $lang, $title, $slug, $content)
    {
        $result = null; $data = [];
        $conn = $this->getEntityManager()->getConnection();

        $sql = "UPDATE article SET 
                lang="."'".$lang."'".", 
                title="."'".$title."'".", 
                slug="."'".$slug."'".", 
                content="."'".$content."'"." 
                WHERE id="."'".$id."'";
                

        $stmt = $conn->prepare($sql);

        $stmt->execute();

        $result = $stmt->fetch();
        $data = [
            'result' => $result,
            'sql' => $sql,
        ];
        return $data; 
    }
}
