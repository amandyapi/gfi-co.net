<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;

class HotelRepository extends \Doctrine\ORM\EntityRepository
{
    public function findRoomsByHotel($hotelId)
    {
        $result = null; 
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT r.id, r.numero, r.statut, t.prix, t.libelle as type, r.description, r.observations, r.petit_dejeuner, r.climatisation, r.fer_a_repasser, r.annulation_gratuite, r.prepaiement_requis, r.coin_salon, r.television
                FROM room r
                JOIN typeroom t ON r.typeroom_id = t.id
                WHERE r.hotel_id = :hotelId
                AND r.statut = 0';

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            'hotelId' => $hotelId
        ]);

        $result = $stmt->fetchAll();
        return $result; 
    }

    public function findReservations()
    {
        $result = null; 
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT r.id, r.etat as canceled, r.debut, r.fin, r.create_time,
                u.id, u.nom, u.prenoms, u.email, u.contact,
                t.libelle as typeChambre, ro.numero as numeroChambre, t.prix
                FROM reservation r
                JOIN user u ON r.user_id = u.id
                JOIN room ro ON r.room_id = ro.id
                JOIN typeroom t ON ro.typeroom_id = t.id';

        $stmt = $conn->prepare($sql);

        $stmt->execute();

        $result = $stmt->fetchAll();
        return $result;
    }

    public function findReservationsByUser($userId)
    {
        $result = null; 
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT r.id, r.etat, r.debut, r.fin, r.create_time,
                u.id, u.nom, u.prenoms, u.email, u.contact
                FROM reservation r
                JOIN user u ON r.user_id = u.id
                JOIN room ro ON r.room_id = ro.id
                JOIN typeroom t ON ro.typeroom_id = t.id
                WHERE r.user_id = :userId';

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            'userId' => $userId
        ]);

        $result = $stmt->fetchAll();
        return $result;
    }


    public function findFreeRoom()
    {
        $result = null; 
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT r.id, r.numero, now() as fin, h.id as hotelId, h.libelle as hotel
                FROM room r
                LEFT JOIN reservation rv ON r.id = rv.room_id
                JOIN hotel h ON r.hotel_id = h.id
                WHERE rv.room_id IS NULL
                
                UNION
                
                SELECT rows.* FROM 
                (
                    SELECT rev.room_id as id, r.numero, MAX(rev.fin) as fin, h.id as hotelId, h.libelle as hotel
                    FROM reservation rev
                    JOIN room r ON rev.room_id = r.id
                    JOIN hotel h ON r.hotel_id = h.id
                    GROUP BY rev.room_id
                    ORDER BY rev.room_id
                ) as rows
                WHERE rows.fin < now()';

        $stmt = $conn->prepare($sql);

        $stmt->execute();

        $result = $stmt->fetchAll();
        return $result; 
    }

    public function findBookedRoom()
    {
        $result = null; 
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT rows.* FROM 
                    (
                        SELECT rev.room_id as id, r.numero, MAX(rev.fin) as fin, h.id as hotelId, h.libelle as hotel
                        FROM reservation rev
                        JOIN room r ON rev.room_id = r.id
                        JOIN hotel h ON r.hotel_id = h.id
                        GROUP BY rev.room_id
                        ORDER BY rev.room_id
                    ) as rows
                WHERE rows.fin > now()';

        $stmt = $conn->prepare($sql);

        $stmt->execute();

        $result = $stmt->fetchAll();
        return $result; 
    }

    public function findFreeRoomsByHotel($hotelId)
    {
        $result = null; 
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT rooms.* FROM
                (
                    SELECT r.id, r.numero, now() as fin, h.id as hotelId, h.libelle as hotel
                    FROM room r
                    LEFT JOIN reservation rv ON r.id = rv.room_id
                    JOIN hotel h ON r.hotel_id = h.id
                    WHERE rv.room_id IS NULL
                    
                    UNION
                    
                    SELECT rows.* FROM 
                        (
                            SELECT rev.room_id as id, r.numero, MAX(rev.fin) as fin, h.id as hotelId, h.libelle as hotel
                            FROM reservation rev
                            JOIN room r ON rev.room_id = r.id
                            JOIN hotel h ON r.hotel_id = h.id
                            GROUP BY rev.room_id
                            ORDER BY rev.room_id
                        ) as rows
                    WHERE rows.fin < now()
                ) as rooms
                
                WHERE rooms.hotelId = :hotelId';

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            'hotelId' => $hotelId
        ]);

        $result = $stmt->fetchAll();
        return $result; 
    }


    public function customFindFreeRoomByHotel($hotelId)
    {
        $result = null; 
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT r.id, r.numero, r.statut, t.prix, t.libelle as type, r.description, r.observations, r.petit_dejeuner, r.climatisation, r.fer_a_repasser, r.annulation_gratuite, r.prepaiement_requis, r.coin_salon, r.television
                FROM room r
                JOIN typeroom t ON r.typeroom_id = t.id
                WHERE r.hotel_id = :hotelId
                AND r.statut = 0';

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            'hotelId' => $hotelId
        ]);

        $result = $stmt->fetchAll();
        return $result; 
    }

    

}