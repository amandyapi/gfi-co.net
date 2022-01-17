<?php
// src/AppBundle/Controller/UserController.php

namespace App\Controller;

use App\Entity\Picture;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Bridge\Twig\Mime\WrappedTemplatedEmail;

class PictureController extends AbstractController
{
    protected $em;
    protected $mailer;
    public function __construct(EntityManagerInterface $em, MailerInterface $mailer)
    {
        $this->em = $em;
        $this->mailer = $mailer;
    }

    public function hotelImage($entity, $id, Request $request)
    {
        if($entity != "hotels" && $entity != "reservations" && $entity != "rooms" && $entity != "activites" && $entity != "publicites" && $entity != "users" )
        {
            $message = "Wrong Entity";
            $error = ['error' => $message];
            return $this->json($error, 404);
        }
        elseif(empty($id)){
            $error = ['error' => "Wrong entity ref"];
            return $this->json($error, 404);
        }
        try 
        {
            $images = $this->getDoctrine()
                           ->getRepository(Picture::class)
                           ->findPicturesByEntity($entity, $id);
            //var_dump($images);die();

            return $this->json($images, 200);

        } catch (\Throwable $th) {
            return $this->json($th->getMessage(), 500);
        }
    }

}
