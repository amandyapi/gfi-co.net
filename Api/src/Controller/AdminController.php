<?php
// src/AppBundle/Controller/UserController.php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Picture;
use App\Entity\Mail;
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

class AdminController extends AbstractController
{
    protected $em;
    protected $mailer;
    public function __construct(EntityManagerInterface $em, MailerInterface $mailer)
    {
        $this->em = $em;
        $this->mailer = $mailer;
    }

    public function mailList(SessionInterface $session)
    {
        $user = $session->get('user');
        if($session->get('user') == NULL || $session->get('user') == null) 
        {
            return $this->redirectToRoute('login');
        }

        $repository = $this->getDoctrine()->getRepository(Mail::class);

        $mails = $this->getDoctrine()
                      ->getRepository(Mail::class)
                      ->findMails();

        $template = 'mail/mails.html.twig';            
        return $this->render($template, [
            'mails' => $mails,
            'user' => $user,
        ]); 
    }

    public function mailInfo($id, SessionInterface $session)
    {
        $user = $session->get('user');
        if($session->get('user') == NULL || $session->get('user') == null) 
        {
            return $this->redirectToRoute('login');
        }

        $repository = $this->getDoctrine()->getRepository(Mail::class);

        $mail = $this->getDoctrine()
                      ->getRepository(Mail::class)
                      ->findMail($id);

        //var_dump($mail);die();
        $bgUrl = 'https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80';

        $template = 'mail/mail.html.twig';            
        return $this->render($template, [
            'mail' => $mail,
            'user' => $user,
            'bgUrl' => $bgUrl
        ]); 
    }


    public function articleList(SessionInterface $session)
    {
        $user = $session->get('user');
        if($session->get('user') == NULL || $session->get('user') == null) 
        {
            return $this->redirectToRoute('login');
        }

        $repository = $this->getDoctrine()->getRepository(Article::class);

        $articles = $this->getDoctrine()
                      ->getRepository(Article::class)
                      ->findAllArticles();

        //var_dump($articles[0]);die();

        $template = 'articles/articles-list.html.twig';            
        return $this->render($template, [
            'articles' => $articles,
            'user' => $user
        ]); 
    }


    public function articleInfo($id, SessionInterface $session)
    {
        $user = $session->get('user');
        if($session->get('user') == NULL || $session->get('user') == null) 
        {
            return $this->redirectToRoute('login');
        }

        $repository = $this->getDoctrine()->getRepository(Article::class);

        $article = $this->getDoctrine()
                      ->getRepository(Article::class)
                      ->customFindArticle($id);

        //var_dump($article);die();
        
        $bgUrl = "http://newsite.gfi-co.net/assets/uploads/articles/".$article["picture"];

        $template = 'articles/article.html.twig';            
        return $this->render($template, [
            'article' => $article,
            'user' => $user,
            'bgUrl' => $bgUrl
        ]); 
    }

    public function articleEdit($id, SessionInterface $session, Request $request)
    {
        $user = $session->get('user');
        if($session->get('user') == NULL || $session->get('user') == null) 
        {
            return $this->redirectToRoute('login');
        }

        $repository = $this->getDoctrine()->getRepository(Article::class);

        $article = $this->getDoctrine()
                      ->getRepository(Article::class)
                      ->customFindArticle($id);

        //var_dump($article);die();
        
        $bgUrl = "http://newsite.gfi-co.net/assets/uploads/articles/".$article["picture"];

        $repository = $this->getDoctrine()->getRepository(Article::class);
        if(!empty($request->request->get('save')))
        {
            var_dump($request->request);die();
        }

        $template = 'articles/article-edit.html.twig';            
        return $this->render($template, [
            'article' => $article,
            'user' => $user,
            'bgUrl' => $bgUrl
        ]); 
    }


	
}
