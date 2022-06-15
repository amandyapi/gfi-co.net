<?php
// src/AppBundle/Controller/UserController.php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Picture;
use App\Entity\Mail;
use App\Entity\Devis;
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
    protected $devisBg;

    public function __construct(EntityManagerInterface $em, MailerInterface $mailer)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->devisBg = [
                            0 => '21.jpg', 1 => '20.jpg', 2 => '19.jpg', 3 => '18.jpg', 4 => '17.jpg', 5 => '16.jpg', 
                            6 => '14.jpg', 7 => '13.jpg', 8 => '12.jpg', 8 => '11.jpg', 8 => '10.jpg', 8 => '9.jpg'
                        ];
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
        $devisUrl = $this->devisBg[0];
        $template = 'home/home-'.$lang.'.html.twig';            
        return $this->render($template, [
            'lang' => $lang,
            'recentArticles' => $recentArticles,
            'devisUrl' => $devisUrl
        ]); 
    }

    public function about($lang)
    {
        $devisUrl = $this->devisBg[1];
        $template = 'about/about-'.$lang.'.html.twig';            
        return $this->render($template, [
            'devisUrl' => $devisUrl
        ]); 
    }

    public function team($lang)
    {
        $devisUrl = $this->devisBg[2];
        $template = 'about/team-'.$lang.'.html.twig';            
        return $this->render($template, [
            'devisUrl' => $devisUrl
        ]); 
    }

    public function prestations($lang, Request $request)
    {
        $file = "data/prestations-".$lang.".json";
        $data = file_get_contents($file);
        $prestations = \json_decode($data);
        $devisUrl = $this->devisBg[0];

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
                    'devisUrl' => $devisUrl
                ]);
            }
        }

        $template = 'prestations/prestations-'.$lang.'.html.twig';            
        return $this->render($template, [
            'prestations' => $prestations,
            'prestations' => $prestations
        ]); 
    }

    public function prestationsInfos($lang, $id, $slug)
    {

        $file = "data/prestations/".$lang."/".$id.".json";
        $data = file_get_contents($file);
        $prestation = \json_decode($data);

        $asideFile = "data/prestations-".$lang.".json";
        $asiteData = file_get_contents($asideFile);
        $asideRaw = \json_decode($asiteData);
        $devisUrl = $this->devisBg[4];
        $content = [];
        $aside = [];
        
        foreach ($prestation->content as $key => $value) {

            if($prestation->id == 6)
            {
                //foreach ($prestation->content as $k => $v) {
                    $content[] = [
                        'ul' => $value->ul,
                        'text' => $value->text,
                    ];
                //}
            }
            else{
                $content[] = $value;
            }
            
         }

        $i = 1;
        foreach ($asideRaw as $key => $value) {
            $a = new \stdClass;
            $a->id = $value->id;
            $a->title = $value->titleTxt;
            $a->slug = $value->slug;
            
            $aside[] = $a;
            $i++;
        }
        //var_dump($aside);die();

        $template = 'prestations/prestations-info-'.$lang.'.html.twig';            
        return $this->render($template, [
            'prestation' => $prestation,
            'content' => $content,
            'aside' => $aside,
            'id' => $id,
            'devisUrl' => $devisUrl
        ]); 
    }

    public function projects($lang)
    {
        $file = "data/project-list-".$lang.".json";
        $data = file_get_contents($file);
        $projects = \json_decode($data);
        $devisUrl = $this->devisBg[5];
        //var_dump($projects);die();

        $template = 'projects/projects-grid-'.$lang.'.html.twig';            
        return $this->render($template, [
            'projects' => $projects,
            'devisUrl' => $devisUrl
        ]); 
    }

    public function projectsInfos($lang, $slug)
    {
        $title = \str_replace('-', ' ', $slug);
        $projet = [];
        $devisUrl = $this->devisBg[6];
        $file = "data/projects-".$lang.".json";
        $data = file_get_contents($file);

        $projects = \json_decode($data);
        $pictures = [];
        $sketch = [];
        $rdc = [];
        $etage = [];
        $etage2 = [];

        foreach ($projects as $key => $value) {
            if($projects[$key]->slug == $slug){
                $projet = $projects[$key];
            }
        }

        foreach ($projet->picture as $key => $value) {
           $pictures[] = $value;
        }

        if($projet->specifications->rdc->text != ""){
            foreach ($projet->specifications->rdc->text as $key => $value) {
                $rdc[] = $value;
            }  
        }
        
        
        if($projet->specifications->etage->text != ""){
            foreach ($projet->specifications->etage->text as $key => $value) {
                $etage[] = $value;
            }    
        }
        
        if($projet->specifications->etage2->text != ""){
            foreach ($projet->specifications->etage2->text as $key => $value) {
                $etage2[] = $value;
            }
        }
        
        $template = 'projects/project-'.$lang.'.html.twig';            
        return $this->render($template, [
            'lang' => $lang,
            'title' => $title,
            'projet' => $projet,
            'pictures' => $pictures,
            'rdc' => $rdc,
            'etage' => $etage,
            'etage2' => $etage2,
            'devisUrl' => $devisUrl
        ]); 
    }

    public function articles($lang)
    {
        $devisUrl = $this->devisBg[7];
        $articles = [];
        $articles = $this->getDoctrine()
                      ->getRepository(Article::class)
                      ->findArticles($lang);
                      
        $template = 'articles/articles-'.$lang.'.html.twig';            
        return $this->render($template, [
            'lang' => $lang,
            'articles' => $articles,
            'devisUrl' => $devisUrl
        ]); 
    }


    public function articlesPaged($lang, $page)
    {
        $articles = [];
        $limit = 6;
        $offset = ($page-1)*$limit;

        $totalArticles = $this->getDoctrine()
                      ->getRepository(Article::class)
                      ->findTotalArticles($lang);

        $nbPages = \ceil($totalArticles/$limit);
        
        $articles = $this->getDoctrine()
                      ->getRepository(Article::class)
                      ->customFindArticles($limit, $offset, $lang);

        /*var_dump($totalArticles);
        var_dump($nbPages);
        var_dump($articles);
        die();*/
        $devisUrl = $this->devisBg[8];
        $template = 'articles/articles-'.$lang.'.html.twig';            
        return $this->render($template, [
            'lang' => $lang,
            'articles' => $articles,
            'totalArticles' => $totalArticles,
            'nbPages' => $nbPages,
            'page' => $page,
        ]); 
    }

    public function articlesInfo($lang, $slug)
    {
        $devisUrl = $this->devisBg[9];
        $articles = [];
        $article = $this->getDoctrine()
                      ->getRepository(Article::class)
                      ->findArticleBySlug($lang, $slug);
        $recentArticles = $this->getDoctrine()
                      ->getRepository(Article::class)
                      ->findVeryLastArticles(3);
        //var_dump($article);die();
        $serverName = $_SERVER['SERVER_NAME'];

        $url = "http://newsite.gfi-co.net/".$lang."/articles/".$article['slug'];
        //var_dump($url);die();
        $title = $article['title'];
        $summary = "newsite.gfi-co.net/";
        $source = "gfi-co";

        $template = 'articles/articles-info-'.$lang.'.html.twig';            
        return $this->render($template, [
            'lang' => $lang,
            'article' => $article,
            'recentArticles' => $recentArticles,
            'serverName' => $serverName,
            'url' => $url,
            'title' => $title,
            'summary' => $summary,
            'source' => $source,
        ]); 
    }

    public function contact($lang, Request $request)
    {
        $page = 'contacts';
        $devisUrl = $this->devisBg[10];
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

	
	public function customDevis(Request $request, $lang)
    {
        $template = 'form/index-'.$lang.'.html.twig';   
        if(!empty($request->request->get('validation')))
        {   
            try {
                $nometpnoms = $request->request->get('nometpnoms');
                $email = $request->request->get('email');
                $contact = $request->request->get('contact');
                $terrain = $request->request->get('dejaTerrain');
                $typeMaison = $request->request->get('typeMaison');
                $devise = $request->request->get('devise');
                $budget = $request->request->get('budget');
                $debutTravaux = $request->request->get('debutTravaux');
                $pays = $request->request->get('pays');
                $cityVille = $request->request->get('cityVille');

                $entityManager = $this->getDoctrine()->getManager();
                $devis = new Devis();
                $devis->setNom($nometpnoms);
                $devis->setEmail($email);
                $devis->setContact($contact);
                $devis->setTerrain($terrain);
                $devis->setNombreDePieces($typeMaison);
                $devis->setDevise($devise);
                $devis->setBudget($budget);
                $devis->setDateDebutPrev($debutTravaux);
                $devis->setPays($pays);
                $devis->setVille($cityVille);

                $entityManager->persist($devis);
                $entityManager->flush(); 

                return $this->redirectToRoute('gfi-devis-success', [
                    'lang' => 'fr'
                ]);
            } catch (\Throwable $th) {
                $message = $th->getMessage();
                $trace = $th->getTraceAsString();
                /*var_dump($message);
                var_dump($trace);
                die();*/
                return $this->redirectToRoute('gfi-devis', [
                    'lang' => 'fr'
                ]);
            }
            
        }   

        return $this->render($template, [
            'lang' => $lang
        ]); 
    }
	
	public function customDevisSuccess($lang)
    {
        $template = 'form/success-'.$lang.'.html.twig'; 

        return $this->render($template, [
            'lang' => $lang
        ]); 
    }
	
	public function moreAcd($lang)
    {
        $template = 'prestations/acd-'.$lang.'.html.twig';              
        return $this->render($template, [
            'lang' => $lang
        ]); 
    }

	
}
