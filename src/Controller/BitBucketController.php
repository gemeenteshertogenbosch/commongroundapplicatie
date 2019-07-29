<?php
// src/Controller/GitHubController.php
namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
		->getClient('bitbucket_main') // key used in config/packages/knpu_oauth2_client.yaml
		->redirect([
				'public_profile', 'email' // the scopes you want to access
		])
		;
	}
	
	/**
	 * After going to bitbucket, you're redirected back here
	 * because this is the "redirect_route" you configured
	 * in config/packages/knpu_oauth2_client.yaml
	 *
	 * @Route("/connect/bitbucket/check", name="connect_bitbucket_check")
	 */
	public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
	{
		// ** if you want to *authenticate* the user, then
		// leave this method blank and create a Guard authenticator
		// (read below)
		
		/** @var \KnpU\OAuth2ClientBundle\Client\Provider\BitbucketClient $client */
		$client = $clientRegistry->getClient('bitbucket_main');
		
		try {
			// the exact class depends on which provider you're using
			/** @var \League\OAuth2\Client\Provider\BitbucketUser $user */
			$user = $client->fetchUser();
			
			// do something with all this new power!
			// e.g. $name = $user->getFirstName();
			var_dump($user); die;
			// ...
		} catch (IdentityProviderException $e) {
			// something went wrong!
			// probably you should return the reason to the user
			var_dump($e->getMessage()); die;
		}
	}
}