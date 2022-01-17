<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;



class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function findUserByOldEmail($oldEmail, $password)
    {

    }

    public function countFindtransactionByUserFilter($params) 
    {
        $result = null;$parameters = [];
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT count(t.id) as totalItems FROM transaction t 
                WHERE t.user_id = :usr'; 
        $parameters['usr'] = $params['user'];
        
        if(\array_key_exists("moyenpaiement", $params))
        {
            $arrayPaiement = \explode(",", $params['moyenpaiement']);
            $pos = strpos($params['moyenpaiement'], ",");
            if($pos === false)
            {
                $sql.= ' AND t.moyenpaiement = :moyenpaiement';
                $parameters['moyenpaiement'] = $params['moyenpaiement'];
            }
            else 
            {
                $chr = "(";
                foreach ($arrayPaiement as $key => $value) {
                    
                    if($key == count($arrayPaiement) - 1) {
                        $chr.="'".$arrayPaiement[$key]."'";
                    } else {
                        $chr.="'".$arrayPaiement[$key]."',";
                    }
                    
                }
                $chr .= ")";

                $sql.= ' AND t.moyenpaiement IN ';
                $sql.= $chr;
            }
            
        }

        if(\array_key_exists("statut", $params))
        {
            $sql.= ' AND t.statut = :statut';
            $parameters['statut'] = $params['statut'];
        }

        if(\array_key_exists("dateFrom", $params))
        {
            $sql.= ' AND t.date > :dateFrom';
            $parameters['dateFrom'] = $params['dateFrom'];
        }

        if(\array_key_exists("dateTo", $params))
        {
            $sql.= ' AND t.date < :dateTo';
            $parameters['dateTo'] = $params['dateTo'];
        }

        $stmt = $conn->prepare($sql);

        $stmt->execute($parameters);

        $result = $stmt->fetch();
        return (int) $result['totalItems'];
    }

    public function findtransactionByUserFilter($params) 
    {
        $result = null;$parameters = [];
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM transaction t 
                WHERE t.user_id = :usr'; 
        $parameters['usr'] = $params['user'];

        if(\array_key_exists("moyenpaiement", $params))
        {
            $arrayPaiement = \explode(",", $params['moyenpaiement']);
            $pos = strpos($params['moyenpaiement'], ",");
            if($pos === false)
            {
                $sql.= ' AND t.moyenpaiement = :moyenpaiement';
                $parameters['moyenpaiement'] = $params['moyenpaiement'];
            }
            else 
            {
                $chr = "(";
                foreach ($arrayPaiement as $key => $value) {
                    
                    if($key == count($arrayPaiement) - 1) {
                        $chr.="'".$arrayPaiement[$key]."'";
                    } else {
                        $chr.="'".$arrayPaiement[$key]."',";
                    }
                    
                }
                $chr .= ")";

                $sql.= ' AND t.moyenpaiement IN ';
                $sql.= $chr;
            }
        }

        if(\array_key_exists("statut", $params))
        {
            $sql.= ' AND t.statut = :statut';
            $parameters['statut'] = $params['statut'];
        }

        if(\array_key_exists("dateFrom", $params))
        {
            $sql.= ' AND t.date > :dateFrom';
            $parameters['dateFrom'] = $params['dateFrom'];
        }

        if(\array_key_exists("dateTo", $params))
        {
            $sql.= ' AND t.date < :dateTo';
            $parameters['dateTo'] = $params['dateTo'];
        }
        
        $sqlLimit = ' LIMIT '. $params['limit'].','.$params['offset'];
        $sql.= $sqlLimit;

        $stmt = $conn->prepare($sql);

        $stmt->execute($parameters);

        $result = $stmt->fetchAll();
        return $result;
    }

    public function findtransactionByUser($user_id)
    {
        $result = null;
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT *
                FROM transaction t
                WHERE t.user_id = :usr';

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            'usr' => $user_id
        ]);

        $result = $stmt->fetchAll();
        return $result;
    }

    public function findUser($contact, $pwd)
    {
        $result = null;
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT *
                FROM user u
                WHERE u.contact = :contact
                AND u.password = :pwd';

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            'contact' => $contact,
            'pwd' => $pwd,
        ]);

        $result = $stmt->fetch();
        return $result;
    }

    public function checkEmailContactUnicity($email, $contact)
    {
        $result = null;$usersLength = 0;$isUnique = false;
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT COUNT(u.id) as nombre
                FROM user u
                WHERE u.contact = :contact
                OR u.email = :email';

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            'contact' => $contact,
            'email' => $email,
        ]);

        $result = $stmt->fetch();
        $usersLength = (int) $result["nombre"];
        if($usersLength > 0) {
            $isUnique = false;
        }
        else if($usersLength == 0)
        {
            $isUnique = true;
        }
        return $isUnique;
    }

    public function checkUserRegisterOtp($contact, $password, $registerOtp)
    {
        $result = null;$number = 0;$canActivate = false;
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT *
                FROM user u
                WHERE u.contact = :contact
                AND u.password = :pwd
                AND u.register_otp = :registerOtp';

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            'contact' => $contact,
            'pwd' => $password,
            'registerOtp' => $registerOtp
        ]);

        $result = $stmt->fetch();
        //$number = (int) $result["nombre"];

        return $result;
    }


}
