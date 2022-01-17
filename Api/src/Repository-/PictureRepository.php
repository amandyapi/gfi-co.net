<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;

class PictureRepository extends \Doctrine\ORM\EntityRepository
{
    public function findPicturesByEntity($entity, $ref)
    {
        $result = null; 
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT id, libelle, imageData, create_time
                FROM picture p
                WHERE p.entite = :entite 
                AND p.ref = :ref';

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            'entite' => $entity,
            'ref' => $ref
        ]);

        $result = $stmt->fetchAll();
        return $result; 
    }

    

}