<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
 
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;

use App\Service\SchemaService;
use App\Service\OrganisationService;
use App\Service\ComponentService;
use App\Service\GithubService;
use App\Service\GitlabService;
use App\Service\BitbucketService;
use App\Service\TeamService;

use App\Entity\Component;
use App\Entity\Organisation;

class ComponentController extends AbstractController
{
	/**
	 * @Route("/components/add/{type}/{organisation}/{id}")
 	 * @ParamConverter("organisation", class="App:Organisation")
	 */
	public function addAction(GithubService $githubService, GitlabService $gitlabService, BitbucketService $bitbucketService, Request $request, EntityManagerInterface $em, $organisation, $type, $id)
	{
		switch ($type) {
			case 'github':
				$component = $githubService->getComponentFromGitHub($id);				
				break;
			case 'bitbucket':
				$component = $bitbucketService->getComponentFromBitbucket($id);
				break;
			case 'gitlab':
				$component = $gitlabService->getComponentFromGitlabS($id);
				break;
			/* @todo let catch non existing organisations */
		}		
		
		if($em->getRepository('App:Component')->findOneBy(array('gitId' => $component->getGitId(),'gitType' => $component->getGitType()))){
			/* @todo ths should be a symfony exeption */
			throw new \Exception('Component is already connected');
		}
		
		$component->setOrganisation($organisation);		
		$em->persist($component);
		$em->flush();
		/* @todo let catch non existing organisations */
		
		/* @todo domein automatisch inladen */
		// redirects externally
		
		return $this->redirectToRoute('app_organisation_view', ['slug' => $organisation->getSlug()]);
	}
	
	
	/**
	 * @Route("/components/add")
	 */
	public function quickaddAction(GithubService $githubService, GitlabService $gitlabService, BitbucketService $bitbucketService, Request $request, EntityManagerInterface $em)
	{
		$url= $request->get('url');
		$url = parse_url(urldecode ( $url));
		
		if(!$url){
			/* @todo ths should be a symfony exeption */
			throw new \Exception('No url parameter provided');
		}
		
		$host = $url['host'];		
		switch ($host) {
			case 'github.com':
				$component = $githubService->getComponentFromGitHubOnUrl($url);
				break;
			case 'bitbucket':
				//$component = $bitbucketService->getComponentFromBitbucket($id);
				break;
			case 'gitlab':
				//$component = $gitlabService->getComponentFromGitlabS($id);
				break;
				/* @todo let catch non existing git types */
		}
		
		if($em->getRepository('App:Component')->findOneBy(array('gitId' => $component->getGitId(),'gitType' => $component->getGitType()))){
			/* @todo ths should be a symfony exeption */
			throw new ConflictHttpException("Component is already connected to this platform");
		}
		
		//$component->setOrganisation($organisation);
		$em->persist($component);
		$em->flush();
		/* @todo let catch non existing organisations */
		
		/* @todo domein automatisch inladen */
		// redirects externally
		
		if(!$component->getOwner()->getVetted()){			
			throw new BadRequestHttpException("You have added a component to an organisation that has not yet been vetted for this platform, your organisation and its components wil be visable as soon as the vetting has been completed");
		}
		
		return $this->redirectToRoute('home');
	}
	
	/**
	 * @Route("/component/{component}/refresh")
	 */
	public function refreshAction(Component $component, ComponentService $componentService, GithubService $githubService, EntityManagerInterface $em)
	{
		$url = parse_url(urldecode ($component->getGit()));
		
		if(!$url){
			/* @todo ths should be a symfony exeption */
			throw new BadRequestHttpException("No url parameter provided");
		}
		
		$host = $url['host'];
		switch ($host) {
			case 'github.com':
				$path = explode('/',$url['path']);				
				$repository = $githubService->getRepositoryFromGitHub($path[1], $path[2]);
				$component->setName($repository['name']);
				$component->setDescription($repository['description']);
				if(!$component->getGitType()){$component->setGitType('github');}
				if(!$component->getGitId()){$component->setGitId($repository['id']);}
				$em->persist($component);
				$em->flush();
				break;
			case 'bitbucket':
				//$component = $bitbucketService->getComponentFromBitbucket($id);
				break;
			case 'gitlab':
				//$component = $gitlabService->getComponentFromGitlabS($id);
				break;
				/* @todo let catch non existing git types */
		}
		
		
		$git = $componentService->getComponentGit($component, true);
				
		return $this->redirectToRoute('home');
	}
}
