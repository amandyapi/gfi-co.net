<?php

namespace App\Repository;

use App\Entity\Devis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Devis|null find($id, $lockMode = null, $lockVersion = null)
 * @method Devis|null findOneBy(array $criteria, array $orderBy = null)
 * @method Devis[]    findAll()
 * @method Devis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DevisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Devis::class);
    }

    public function findDevis()
    {
        $result = null;
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT * 
                FROM devis d
                ORDER BY d.create_time DESC';

        $stmt = $conn->prepare($sql);

        $stmt->execute();

        $result = $stmt->fetchAll();
        return $result;
    }

    public function findOneDevis($id)
    {
        $result = null;
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT *
                FROM devis d
                WHERE d.id = :id';

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            'id' => $id,
        ]);

        $result = $stmt->fetch();
        return $result;
    }
}
