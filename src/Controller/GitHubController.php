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

use App\Service\GithubService;

class GitHubController extends Controller
{
	private $githubService;
	
	public function __construct(GithubService $githubService)
	{
		$this->githubService = $githubService;
	}
	
	/**
	 * 
	 *
	 * @Route("/webhook/github")
	 */
	public function webhookAction(Request $request)
	{
		
		$response = json_decode ($response->getBody(), true);
		// on Symfony 3.3 or lower, $clientRegistry = $this->get('knpu.oauth2.registry');
		
	}
	
	/**
	 * Link to this controller to start the "connect" process
	 *
	 * @Route("/connect/github", name="connect_github_start")
	 */
	public function connectAction(ClientRegistry $clientRegistry)
	{
		// on Symfony 3.3 or lower, $clientRegistry = $this->get('knpu.oauth2.registry');
		
		// will redirect to github!
		return $clientRegistry
		->getClient('github') // key used in config/packages/knpu_oauth2_client.yaml
		->redirect([
				'public_profile', 'email','user','read:org' // the scopes you want to access
		])
		;
	}
	
	/**
	 * After going to github, you're redirected back here
	 * because this is the "redirect_route" you configured
	 * in config/packages/knpu_oauth2_client.yaml
	 *
	 * @Route("/connect/github/check", name="connect_github_check", schemes={"https"})
	 */
	public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
	{
		// We dont actualy use this route its just a hook for our guard authenticators
	}
	
	/**
	 * This route remove the github credentials from the curently loged in user and log him out
	 *
	 * @Route("/connect/github/remove", name="connect_github_remove", schemes={"https"})
	 */
	public function removeAction(Request $request,  UserInterface $user, EntityManagerInterface $em){
		
		$user->setGithubId(null);
		$user->setGitubToken(null);
		$em->persist($user);
		$em->flush();
		
		$targetUrl = $this->router->generate('home');
		
		return new RedirectResponse($targetUrl);
	}
}