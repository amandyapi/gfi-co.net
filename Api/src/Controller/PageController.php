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

    public function firstHome()
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

        $template = 'about/team-'.$lang.'.html.twig';            
        return $this->render($template); 
    }

    public function prestations($lang, Request $request)
    {
        $file = "data/prestations-".$lang.".json";
        $data = file_get_contents($file);
        $prestations = \json_decode($data);

        if(!empty($request->request->get('envoyer')))
        {
            $page = 'prestations';

            $response;
            $entityManager = $this->getDoctrine()->getManager();
            
            $gfiContactMail = 'contact@gfi-co.net';
            $senderFullName = $request->request->get('contact-name');
            $senderMail = $request->request->get('contact-email');
            $senderContact = $request->request->get('contact-phone');
            $title = $request->request->get('objet');
            $content = $request->request->get('contact-messgae');

            try {
                $email = (new TemplatedEmail())
                    ->from(new Address($senderMail, $senderFullName))
                    ->to(new Address($gfiContactMail))
                    ->subject($title)
                    ->htmlTemplate('contact-mail.html.twig')
                    //->text('nous avons le plaisir de vous informer, que votre compte <b>CIKHAPAY</b>');
    
                    ->context([
                        'senderFullName' => $senderFullName,
                        'senderMail' => $senderMail,
                        'senderContact' => $senderContact,
                        'title' => $title,
                        'content' => $content,
                    ]);
    
                $this->mailer->send($email);
    
                $mail = new Mail();
                $mail->setSenderFullName(\strtolower($senderFullName));
                $mail->setSenderMail(\strtolower($senderMail));
                $mail->setSenderContact(\strtolower($senderContact));
                $mail->setTitle(\strtolower($title));
                $mail->setContent(\strtolower($content));
    
                $entityManager->persist($mail);
                $entityManager->flush();
    
                //return $this->json(true, 200);
                return $this->redirectToRoute('gfi-success-mail', [
                    'lang' => $lang
                ]);
            } catch (\Throwable $th) {
                $response = $th->getMessage();
                
                return $this->redirectToRoute('gfi-error-mail', [
                    'lang' => $lang,
                    'page' => $page
                ]);
            }
        }

        $template = 'prestations/prestations-'.$lang.'.html.twig';            
        return $this->render($template, [
            'prestations' => $prestations
        ]); 
    }

    public function prestationsInfos($lang, $id, $slug)
    {

        $file = "data/prestations/".$lang."/".$id.".json";
        $data = file_get_contents($file);
        $prestation = \json_decode($data);
        //var_dump($prestation);die();

        $template = 'prestations/prestations-info-'.$lang.'.html.twig';            
        return $this->render($template, [
            'prestation' => $prestation
        ]); 
    }

    public function projects($lang)
    {
        $file = "data/project-list-".$lang.".json";
        $data = file_get_contents($file);
        $projects = \json_decode($data);

        //var_dump($projects);die();

        $template = 'projects/projects-grid-'.$lang.'.html.twig';            
        return $this->render($template, [
            'projects' => $projects,
        ]); 
    }

    public function projectsInfos($lang, $slug)
    {
        $title = \str_replace('-', ' ', $slug);
        $projet = [];

        $file = "data/projects-".$lang.".json";
        $data = file_get_contents($file);

        $projects = \json_decode($data);
        $pictures = [];
        $rdc = [];
        $etage = [];

        foreach ($projects as $key => $value) {
            if($projects[$key]->slug == $slug){
                $projet = $projects[$key];
            }
        }

        foreach ($projet->picture as $key => $value) {
           $pictures[] = $value;
        }

        foreach ($projet->specifications->rdc as $key => $value) {
           $rdc[] = $value;
        }

        foreach ($projet->specifications->etage as $key => $value) {
           $etage[] = $value;
        }

        //var_dump($pictures);die();
        
        $template = 'projects/projects-info-'.$lang.'.html.twig';            
        return $this->render($template, [
            'lang' => $lang,
            'title' => $title,
            'projet' => $projet,
            'pictures' => $pictures,
            'rdc' => $rdc,
            'etage' => $etage
        ]); 
    }

    public function articles($lang)
    {
        $articles = [];
        $articles = $this->getDoctrine()
                      ->getRepository(Article::class)
                      ->findArticles(3);
                      
        $template = 'articles/articles-'.$lang.'.html.twig';            
        return $this->render($template, [
            'lang' => $lang,
            'recentArticles' => $articles,
        ]); 
    }

    public function articlesInfo($lang, $slug)
    {

        $template = 'articles/articles-info-'.$lang.'.html.twig';            
        return $this->render($template); 
    }

    public function contact($lang)
    {
        $page = 'contacts';
        
        $template = 'contacts/contacts-'.$lang.'.html.twig';            
        return $this->render($template, [
            'lang' => $lang,
            'page' => $page
        ]); 
    }

	public function successMail($lang)
    {

        $template = 'contacts/mail-success-'.$lang.'.html.twig';            
        return $this->render($template); 
    }

	
	public function errorMail($lang, $page)
    {

        $template = 'contacts/mail-error-'.$lang.'.html.twig';              
        return $this->render($template, [
            'lang' => $lang,
            'page' => $page
        ]); 
    }

	
}
