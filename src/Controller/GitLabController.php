<?php
// src/Controller/GitHubController.php
namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;

class GitLabController extends Controller
{
	/**
	 * Link to this controller to start the "connect" process
	 *
	 * @Route("/connect/gitlab", name="connect_gitlab_start")
	 */
	public function connectAction(ClientRegistry $clientRegistry)
	{
		// on Symfony 3.3 or lower, $clientRegistry = $this->get('knpu.oauth2.registry');
		
		// will redirect to gitlab!
		return $clientRegistry
		->getClient('gitlab') // key used in config/packages/knpu_oauth2_client.yaml
		->redirect([
				'read_user', 'api','profile','email' // the scopes you want to access
		])
		;
	}
	
	/**
	 * After going to gitlab, you're redirected back here
	 * because this is the "redirect_route" you configured
	 * in config/packages/knpu_oauth2_client.yaml
	 *
	 * @Route("/connect/gitlab/check", name="connect_gitlab_check", schemes={"https"})
	 */
	public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
	{
		// We dont actualy use this route its just a hook for our guard authenticators
	}
	
	/**
	 * This route remove the git lab credentials from the curently loged in user and log him out
	 *
	 * @Route("/connect/gitlab/remove", name="connect_gitlab_remove", schemes={"https"})
	 */
	public function removeAction(Request $request,  UserInterface $user, EntityManagerInterface $em){
		
		$user->setGitlabId(null);
		$user->setGitlabToken(null);
		$em->persist($user);
		$em->flush();
		
		$targetUrl = $this->router->generate('app_user_index');
		
		return new RedirectResponse($targetUrl);
		
	}
}