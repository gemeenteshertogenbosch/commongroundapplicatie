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
	}
	
	/**
	 * @Route("/organisation/add/{type}/{id}")
	 */
	public function addAction(GithubService $githubService, GitlabService $gitlabService, BitbucketService $bitbucketService, Request $request, UserInterface $user, EntityManagerInterface $em, $type, $id)
	{
		switch ($type) {
			case 'github':
				// Firts we need an organisation
				$organisation = $githubService->getOrganisationFromGitHub($id);
				// Then we can add the teams
				/*
				$teams = $githubService->getTeamsFromGitHub($id);
				foreach($teams as $team){
					$organisation->addTeam($teams);
				}
				*/
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
	}
	
	/**
	* @Route("/organisation/edit/{slug}")
	*/
	public function editAction(SchemaService $schemaService, OrganisationService $organisationService, TeamService $teamService,Request $request, UserInterface $user, $slug)
	{
		$organisation = $organisationService->getOrganisationOnSlug($slug);
		/* @todo let catch non existing organisations */
		
		$variables =[];		
		$variables['user'] = $user;		
		// Lets see if we have github organisations
		
		// Lets get al the organisations that this user has on social profiles
		$variables['organisations'] =  $organisationService->getUserSocialOrganisations($user);
		
		// Now that we have all the data we can see if we have aditional data on the organisations ons our platform
		$variables['organisations'] = $organisationService->enrichOrganisations($variables['organisations']);
		
		// Lets get al the organisations that this user has on social profiles
		$variables['teams'] =  $teamService->getUserSocialTeams($user);
		
		// Now that we have all the data we can see if we have aditional data on the organisations ons our platform
		$variables['teams'] = $teamService->enrichTeams($variables['teams']);
		
		return $this->render('home/edit-organisation.html.twig', $variables);
	}
}
