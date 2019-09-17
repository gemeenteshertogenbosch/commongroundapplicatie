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

class BitBucketController extends Controller
{
	/**
	 * Link to this controller to start the "connect" process
	 *
	 * @Route("/connect/bitbucket", name="connect_bitbucket_start")
	 */
	public function connectAction(ClientRegistry $clientRegistry)
	{
		// on Symfony 3.3 or lower, $clientRegistry = $this->get('knpu.oauth2.registry');
		
		// will redirect to bitbucket!
		return $clientRegistry
		->getClient('bitbucket') // key used in config/packages/knpu_oauth2_client.yaml
		->redirect([
				'account'// the scopes you want to access
		])
		;
	}
	
	/**
	 * After going to bitbucket, you're redirected back here
	 * because this is the "redirect_route" you configured
	 * in config/packages/knpu_oauth2_client.yaml
	 *
	 * @Route("/connect/bitbucket/check", name="connect_bitbucket_check", schemes={"https"})
	 */
	public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
	{
		// We dont actualy use this route its just a hook for our guard authenticator
	}
	
	/**
	 * This route removes the bitbucket credentials from the curently loged in user and logs him out
	 *
	 * @Route("/connect/bitbucket/remove", name="connect_bitbucket_remove", schemes={"https"})
	 */
	public function removeAction(Request $request,  UserInterface $user, EntityManagerInterface $em) {
		
		$user->setBitbucketId(null);
		$user->setBitbucketToken(null);
		$em->persist($user);
		$em->flush();
		
		$targetUrl = $this->router->generate('home');
		
		return new RedirectResponse($targetUrl);
	}
}