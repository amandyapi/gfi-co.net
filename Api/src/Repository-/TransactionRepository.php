<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;

class TransactionRepository extends \Doctrine\ORM\EntityRepository
{
    public function findTransactionsByOrderId($orderId)
    {
        $result = null; 
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT *
                FROM transaction t
                WHERE t.order_id = :orderId';

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            'orderId' => $orderId
        ]);

        $result = $stmt->fetch();
        return $result; 
    }

    public function updateTransactionsByOrderId($orderId, $statut, $description)
    {
        $result = null; $data = [];
        $conn = $this->getEntityManager()->getConnection();

        $sql = "UPDATE transaction SET 
                statut="."'".$statut."'".", 
                description="."'".$description."'"." 
                WHERE order_id="."'".$orderId."'";
                

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