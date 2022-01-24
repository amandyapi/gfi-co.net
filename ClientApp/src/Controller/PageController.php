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

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Bridge\Twig\Mime\WrappedTemplatedEmail;

class PageController extends AbstractController
{
    protected $em;
    protected $mailer;
    public function __construct(EntityManagerInterface $em, MailerInterface $mailer)
    {
        $this->em = $em;
        $this->mailer = $mailer;
    }

    public function root()
    {
        return $this->redirectToRoute('gfi-root');
    }

    public function home($lang)
    {
        $recentArticles = [];
        $recentArticles = $this->getDoctrine()
                      ->getRepository(Article::class)
                      ->findLastArticles(3);
        //var_dump($recentArticles);die();

        $template = 'home/home-'.$lang.'.html.twig';            
        return $this->render($template, [
            'lang' => $lang,
            'recentArticles' => $recentArticles,
        ]); 
    }

    public function about($lang)
    {

        $template = 'about/about-'.$lang.'.html.twig';            
        return $this->render($template); 
    }

    public function team($lang)
    {

        $template = 'team/team-'.$lang.'.html.twig';            
        return $this->render($template); 
    }

    public function prestations($lang)
    {

        $template = 'prestations/prestations-'.$lang.'.html.twig';            
        return $this->render($template); 
    }

    public function prestationsInfos($lang, $slug)
    {

        $template = 'prestations/prestations-info-'.$lang.'.html.twig';            
        return $this->render($template); 
    }

    public function projects($lang)
    {

        $template = 'projects/projects-'.$lang.'.html.twig';            
        return $this->render($template); 
    }

    public function projectsInfos($lang, $slug)
    {

        $template = 'projects/projects-info-'.$lang.'.html.twig';            
        return $this->render($template); 
    }

    public function concepts($lang)
    {

        $template = 'concepts/concepts-'.$lang.'.html.twig';            
        return $this->render($template); 
    }

    public function conceptsInfos($lang, $slug)
    {

        $template = 'concepts/concepts-info-'.$lang.'.html.twig';            
        return $this->render($template); 
    }

    public function articles($lang)
    {

        $template = 'articles/articles-'.$lang.'.html.twig';            
        return $this->render($template); 
    }

    public function articlesInfo($lang, $slug)
    {

        $template = 'articles/articles-info-'.$lang.'.html.twig';            
        return $this->render($template); 
    }

    public function contact($lang)
    {

        $template = 'contacts/contacts-'.$lang.'.html.twig';            
        return $this->render($template); 
    }

	
}
