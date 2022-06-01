<?php
// src/AppBundle/Controller/UserController.php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Mail;
use App\Entity\Devis;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;  

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Bridge\Twig\Mime\WrappedTemplatedEmail;

class DevisController extends AbstractController
{
    protected $em;
    protected $mailer;
    public function __construct(EntityManagerInterface $em, MailerInterface $mailer)
    {
        $this->em = $em;
        $this->mailer = $mailer;
    }

    public function getAll(SessionInterface $session)
    {
        $user = $session->get('user');
        if($session->get('user') == NULL || $session->get('user') == null) 
        {
            return $this->redirectToRoute('login');
        }

        $devis = $this->getDoctrine()
                      ->getRepository(Devis::class)
                      ->findDevis();
        //var_dump($devis);die();

        $template = 'devis/devis.html.twig';            
        return $this->render($template, [
            'devis' => $devis,
            'user' => $user,
        ]); 
    }

    public function getOne($id, SessionInterface $session)
    {

        $user = $session->get('user');
        if($session->get('user') == NULL || $session->get('user') == null) 
        {
            return $this->redirectToRoute('login');
        }

        $devis = $this->getDoctrine()
                      ->getRepository(Devis::class)
                      ->findOneDevis($id);
        //var_dump($devis);die();

        $bgUrl = 'https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80';

        $template = 'devis/index.html.twig';  
        return $this->render($template, [
            'd' => $devis,
            'user' => $user,
            'bgUrl' => $bgUrl,
        ]); 
    }
	
}
