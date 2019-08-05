<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Symfony\Component\Security\Core\User\UserInterface;

use App\Service\SchemaService;
use App\Service\OrganisationService;
use App\Service\GithubService;
use App\Service\GitlabService;
use App\Service\BitbucketService;
use App\Service\TeamService;

use App\Entity\Organisation;

class OrganisationController extends AbstractController
{
	
	/**
	 * @Route("/organisation/leave/{id}")
 	 * @ParamConverter("organisation", class="Organisation")
	 */
	public function leaveAction(Request $request, UserInterface $user, EntityManagerInterface $em, $organisation)
	{
		$organisation->removeUser($user);
		$em->persist($organisation);
		$em->flush();
				
		$targetUrl = $this->router->generate('app_user_index');
		
		return new RedirectResponse($targetUrl);
	}
	
	/**
	 * @Route("/organisation/join/{id}")
 	 * @ParamConverter("organisation", class="Organisation")
	 */
	public function joinAction(Request $request, UserInterface $user, EntityManagerInterface $em, $organisation)
	{
		$organisation->addUser($user);
		$em->persist($organisation);
		$em->flush();
		
		/* @todo domein automatisch inladen */
		// redirects externally
		return $this->redirect('https://'.$organisation->getSlug().'.common-ground.dev');
	}
	
	/**
	 * @Route("/organisation/add/{type}/{id}")
	 */
	public function addAction(GithubService $githubService, GitlabService $gitlabService, BitbucketService $bitbucketService, Request $request, UserInterface $user, EntityManagerInterface $em, $type, $id)
	{
		switch ($type) {
			case 'github':
				$organisation = $githubService->getOrganisationFromGitHub($id);				
				break;
			case 'bitbucket':
				$organisation = $bitbucketService->getOrganisationFromBitbucket($id);
				break;
			case 'gitlab':
				$organisation = $gitlabService->getOrganisationFromGitlabS($id);
				break;
			/* @todo let catch non existing organisations */
		}		
		
		$organisation->addUser($user);
		$em->persist($organisation);
		$em->flush();
		/* @todo let catch non existing organisations */
		
		/* @todo domein automatisch inladen */
		// redirects externally
		return $this->redirect('https://'.$organisation->getSlug().'.common-ground.dev');
	}
}
