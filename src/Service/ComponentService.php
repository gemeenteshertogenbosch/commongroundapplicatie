<?php
// src/Service/AmbtenaarService.php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use GuzzleHttp\Client;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Cache\Adapter\AdapterInterface as CacheInterface;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Organisation; // your user entity
use App\Entity\Component; // your user entity

use App\Service\GithubService;
use App\Service\GitlabService;
use App\Service\BitbucketService; 

class ComponentService
{
	private $params;
	private $cash;
	private $markdown;
	private $em;
	private $gitlab;
	private $github;
	private $bitbucket;
	
	public function __construct(
			ParameterBagInterface $params, 
			MarkdownParserInterface $markdown, 
			CacheInterface $cache, 
			EntityManagerInterface $em,
			GithubService $github,
			GitlabService $gitlab,
			BitbucketService $bitbucket)
	{
		$this->params = $params;
		$this->cash = $cache;
		$this->markdown = $markdown;
		$this->em = $em;
		$this->gitlab= $gitlab;
		$this->github= $github;
		$this->bitbucket= $bitbucket;
	}
		
	public function getAllComponents()
	{
	}	
	
	
	public function getOpenApi(Component $component)
	{
		
		$lookIn = [
			'',
			'schema/',
			'public/',
			'public/schema/',	
			'api/',
			'api/schema/',
			'api/public/',
			'api/public/schema/'
		];
		
		foreach ($lookIn as $location){
			if($responce = $this->github->getFileContent($component, $location.'openapi.yaml')){
				return Yaml::parse($responce);
			}
			if($responce = $this->github->getFileContent($component, $location.'openapi.json')){
				return json_decode($responce, true);
			}
		}
		
		// If nothing is found
		return false;
	}
	
	public function getComponentFromGit(Component $component)
	{
		/*@todo we should filter for html here */
		
		
		$git = [];
		if($component->getGitType()=="github"){
			$git['readme'] = $this->github->getFileContent($component, 'README.md');
			$git['license'] = $this->github->getFileContent($component, 'LICENSE');
			$git['changelog'] = $this->github->getFileContent($component, 'CHANGELOG.md');
			$git['contributing'] = $this->github->getFileContent($component, 'CONTRIBUTING.md');
			$git['installation'] = $this->github->getFileContent($component, 'INSTALLATION.md');
			$git['codeofconduct'] = $this->github->getFileContent($component, 'CODE_OF_CONDUCT.md');
		}		
		$git['openapi'] = $this->getOpenApi($component);
		
		// Lets copy some component info		
		$git['name'] = $component->getName();
		$git['description'] = $component->getDescription();
		$git['logo'] = $component->getLogo();
		$git['git'] = $component->getGit();
		$git['gitType'] = $component->getGitType();
		$git['gitId'] = $component->getGitId();
		$git['id'] = $component->getId();
			
		// The we need to determine the type of te componet
		if($git['openapi']){
			$git['type'] = '';
		}
		else {
			$git['type'] = '';			
		}
		
		var_dump($git);
		return $git;
	}
	
	public function getComponentGit(Component $component)
	{		
		$item = $this->cash->getItem('component_'.md5($component->getId()));
		if ($item->isHit()) {
			return $item->get();
		}
		
		$value = $this->getComponentFromGit($component);	
		
		$item->set($value);
		$item->expiresAt(new \DateTime('tomorrow'));
		$this->cash->save($item);
		
		return $item->get();
	}
}
