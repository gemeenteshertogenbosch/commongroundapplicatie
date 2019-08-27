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

use App\Service\SchemaService;
use App\Service\OrganisationService;
use App\Service\GithubService;
use App\Service\GitlabService;
use App\Service\BitbucketService;
use App\Service\TeamService;

use App\Entity\Component;
use App\Entity\Organisation;

class ComponentController extends AbstractController
{
	/**
	 * @Route("/component/add/{type}/{organisation}/{id}")
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
	
}
