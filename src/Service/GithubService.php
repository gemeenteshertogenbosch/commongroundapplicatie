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

class GithubService
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
			$this->client = new Client(['base_uri' => 'https://api.github.com/', 'headers'=>['Authorization'=>'Bearer '.$user->getGithubToken()]]);
		}
		else{
			$this->client = new Client(['base_uri' => 'https://api.github.com/']);			
		}
	}
		
	public function getRepositories($organisations)
	{
	}			
	
	public function getOrganisationFromGitHub($id)
	{
		$response = $this->client->get('/orgs/'.$id);
		$response = json_decode ($response->getBody(), true);
		
		$organisation = New Organisation;
		$organisation->setName($response['name']);
		$organisation->setDescription($response['description']);
		$organisation->setLogo($response['avatar_url']);
		$organisation->setGithub($response['html_url']);
		$organisation->setGithubId($response['id']);
		
		return $organisation;
	}
		
	// Returns an array of teams for an organisation
	public function getTeamsFromGitHub($id)
	{		
		$response = $this->client->get('/orgs/'.$id.'/teams');
		$responses = json_decode ($response->getBody(), true);
		
		$teams=[];
		foreach($responses as $response){
			$team = New Team;
			$team->setName($response['name']);
			$team->setDescription($response['description']);
			$team->setGithub($response['html_url']);
			$team->setGithubId($response['id']);
			$teams[] = $team;
		}
				
		return $teams;
	}	
	
	
	// Returns an array of teams for an organisation
	public function getOrganisationRepositories ($organisation)
	{		
		
	}	
	
	// Returns an array of teams for an organisation
	public function getUserRepositories ($user)
	{
		
	}
}
