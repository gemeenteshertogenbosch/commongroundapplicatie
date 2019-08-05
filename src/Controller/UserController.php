<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

use App\Service\SchemaService;
use App\Service\OrganisationService;
use GuzzleHttp\Client;

class UserController extends AbstractController
{
	 /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout()
    {
        // controller can be blank: it will never be executed!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
	
	/**
	* @Route("/user")
	*/
	public function indexAction(SchemaService $schemaService, OrganisationService $organisationService,  Request $request, UserInterface $user)
	{
		
		
		$variables =[];		
		$variables['user'] = $user;		
		
		// Lets get al the organisations that this user has on social profiles
		$variables['organisations'] =  $organisationService->getUserSocialOrganisations($user);				
		
		// Now that we have all the data we can see if we have aditional data on the organisations ons our platform
		$variables['organisations'] = $organisationService->enrichOrganisations($variables['organisations']);
		
		return $this->render('home/edit-user.html.twig', $variables);
	}
}
