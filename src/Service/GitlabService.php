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
use Symfony\Component\Security\Core\User\UserInterface;

use App\Entity\Organisation;
use App\Entity\Component; 

class GitlabService
{
	private $params;
	private $cash;
	private $markdown;
	private $em;
	private $githubToken;
	private $client;
	
	public function __construct(ParameterBagInterface $params, MarkdownParserInterface $markdown, CacheInterface $cache, EntityManagerInterface $em, $githubToken = null)
	{
		$this->params = $params;
		$this->cash = $cache;
		$this->markdown = $markdown;
		$this->em = $em;
		$this->githubToken = $githubToken;
		if($this->githubToken){
			$this->client = new Client(['base_uri' => 'https://gitlab.com/api/v4/', 'headers'=>['Authorization'=>'Bearer '.$user->getGithubToken()]]);
		}
		else{
			$this->client = new Client(['base_uri' => 'https://gitlab.com/api/v4/']);
		}
	}	
			
	public function getComponentFromGitLab($id)
	{
		$response = $this->client->get('/api/v4/projects/'.$id);
		$response = json_decode ($response->getBody(), true);
		
		$component = New Component;
		$component->setName($response['name']);
		$component->setDescription($response['description']);
		$component->setGitlab($response['web_url']);
		$component->setGitlabId($response['id']);
		$component->setAvatar($response['avatar_url']);
		
		return $component;
	}
	
	
	// we always use a user to ask api qoustions
	public function getGroupFromGitLab($id)
	{
		
		$organisation = New Organisation;
		
	}	
	
	// Returns an array of repositories for an organisation
	public function getGroupRepositories ($id)
	{		
		$repositories= [];
		$response = $this->client->get('groups/'.$id.'/projects');
		$responses = json_decode ($response->getBody(), true);
		
		foreach($responses as $repository){
			$repositories[]= [
					"type"=>"gitlab",
					"link"=>$repository['html_url'],
					"id"=>$repository['name'],
					"name"=>$repository['name'],
					"description"=>$repository['description']
			];
		}
		
		return $repositories;
	}
	
	// Returns an array of repositories for an user
	public function getUserRepositories ($id)
	{
		$repositories= [];
		$response = $this->client->get('users/'.$id.'/projects');
		$responses = json_decode ($response->getBody(), true);
		
		foreach($responses as $repository){
			$repositories[]= [
					"type"=>"gitlab",
					"link"=>$repository['web_url'],
					"id"=>$repository['id'],
					"name"=>$repository['name_with_namespace'],
					"description"=>$repository['description']
			];
		}
		
		return $repositories;
	}
}
