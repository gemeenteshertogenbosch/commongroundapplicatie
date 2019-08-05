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

use App\Entity\Organisation; 


use App\Service\GithubService; 
use App\Service\GitlabService; 
use App\Service\BitbucketService; 

class OrganisationService
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
	
	/* @todo lets force a organisation here */
	public function getOrganisationRepositories($organisation)
	{
		$repositories =[];
		if($organisation->getGithubId()){
			$repositories = array_merge ($repositories, $this->github->getOrganisationRepositories($organisation->getGithubId())); 
			
		}
		if($organisation->getGitlabId()){
			$repositories = array_merge ($repositories, $this->gitlab->getGroupRepositories($organisation->getGitlabId()));
		}
		if($organisation->getBitbucketId()){
			$repositories = array_merge ($repositories, $this->bitbucket->getOrganisationRepositories($organisation->getBitbucketId()));
		}
			
		return $repositories;
	}
	/* @todo lets force a user here */
	public function getUserSocialOrganisations($user)
	{
		$organisations=[];
		
		// Lets see if we have github organisations
		if($user->getGithubToken()){
			
			$client = new Client(['base_uri' => 'https://api.github.com', 'headers'=>['Authorization'=>'Bearer '.$user->getGithubToken()]]);
			$response = $client->get('/user/orgs');
			$response = json_decode ($response->getBody(), true);
			
			foreach($response as $organisation ){
				$organisations[]= [
						"type"=>"github",
						"link"=>$organisation['url'],
						"id"=>$organisation['login'],
						"name"=>$organisation['login'],
						"avatar"=>$organisation['avatar_url']
				];
			}
		}
		// Lets see if we have gitlab groups
		if($user->getGitlabToken()){
			$client = new Client(['base_uri' => 'https://gitlab.com', 'headers'=>['Authorization'=>'Bearer '.$user->getGitlabToken()]]);
			$response = $client->get('/api/v4/groups');
			$response = json_decode ($response->getBody(), true);
			
			foreach($response as $organisation ){
				$organisations[]= [
						"type"=>"gitlab",
						"link"=>$organisation['web_url'],
						"id"=>$organisation['id'],
						"name"=>$organisation['name'],
						"avatar"=>$organisation['avatar_url']
				];
			}
		}
		// Lets see if we have bitbucket teams
		if($user->getBitbucketToken()){
			
			$client = new Client(['base_uri' => 'https://api.bitbucket.org/', 'headers'=>['Authorization'=>'Bearer '.$user->getBitbucketToken()]]);
			$response = $client->get('/2.0/teams?role=member');
			$response = json_decode ($response->getBody(), true);
			
			foreach($response['values'] as $organisation ){
				$organisations[]= [
						"type"=>"bitbucket",
						"link"=>$organisation['links']['html']['href'],
						"id"=>$organisation['uuid'],
						"name"=>$organisation['display_name'],
						"avatar"=>$organisation['links']['avatar']['href']
				];
			}
		}
		
		return $organisations;
	}
	
	public function enrichOrganisations($organisations)
	{
		$repository = $this->em->getRepository(Organisation::class);
		
		// Lets see if we already know these organisations
		foreach($organisations as $organisation){
			$type = $organisation['type'];
			switch ($type) {
				case 'github':
					$organisation['commonground'] = $repository->findOneBy(['githubId' => $organisation['id']]);
					break;
				case 'bitbucket':
					$organisation['commonground'] = $repository->findOneBy(['bitbucketId' => $organisation['id']]);
					break;
				case 'gitlab':
					$organisation['commonground'] = $repository->findOneBy(['gitlabId' => $organisation['id']]);
					break;
			}			
		}
		
		return $organisations;
	}	
	public function getOrganisationOnSlug($slug)
	{
		$repository = $this->em->getRepository(Organisation::class);
		return $repository->findOneBy(['slug' => $slug]);
	}		
	
	public function getOrganisationFromGithub($slug)
	{
	}	
}
