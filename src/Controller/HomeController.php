<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

use App\Service\SchemaService;
use App\Service\OrganisationService;

use App\Entity\Organisation;
use App\Entity\Component;

class HomeController extends AbstractController
{ 
	/**
	* @Route("/", name="home")
	*/
	public function indexAction(SchemaService $schemaService, OrganisationService $organisationService,  Request $request, EntityManagerInterface $em)
	{
		// First of we want to get a subdomain 
		/* @todo welicht willen we dit op een andere plek afhandelen */
		$currentHost = $request->getHttpHost();
		$parsedUrl = parse_url($currentHost);		
		$host = explode('.', $parsedUrl['path']);		
		$subdomain = $host[0];
				
		// So now we should have a sub domain, so lets walk trough the options
		if($subdomain == "www" || count($host)<= 2){ 
			// If iether the domain is www or only consists of less then two parts. e.g. google.com or localhost, the we have hit the main page and wil load the inforamtion for that page
			// We then return that page to prevent further exectution of code
			
			/* @todo wellicht willen we nog iets doen met published zetten of iets dergelijks*/
			$organisations = $em->getRepository(Organisation::class)->findAll();
			$components = $em->getRepository(Component::class)->findAll();
			
			$variables = ["organisations" => $organisations,"components" => $components];
			return $this->render('home/main.html.twig',$variables);
		}
		
		// If we got here then we have a subdomain, so try to render a organisation or organisation not found pages
		$organisation = $organisationService->getOrganisationOnSlug($subdomain);
		if($organisation){			
			$repositories= $organisationService->getOrganisationRepositories($organisation);
						
			$isAdmin = $organisation->getUsers()->contains($this->getUser());
			$isAdmin = true; /* @todo verijwderen, is voor test doeleinden */
			$variables = ["organisation" => $organisation,"repositories"=> $repositories, "isAdmin" => $isAdmin];
			return $this->render('home/organisation.html.twig',$variables);			
		}
		else{			
			$variables = ["subdomain" => $subdomain];
			return $this->render('home/join-organisation.html.twig',$variables);
		}
	}
}
