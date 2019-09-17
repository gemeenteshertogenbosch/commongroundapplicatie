<?php
// src/Service/TeamService.php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use GuzzleHttp\Client;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Cache\Adapter\AdapterInterface as CacheInterface;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Team; 

class TeamService
{
	private $params;
	private $cash;
	private $markdown;
	private $em;
	
	public function __construct(ParameterBagInterface $params, MarkdownParserInterface $markdown, CacheInterface $cache, EntityManagerInterface $em)
	{
		$this->params = $params;
		$this->cash = $cache;
		$this->markdown = $markdown;
		$this->em = $em;
	}
	
	/* @todo lets force a user here */
	public function getUserSocialTeams($user)
	{
		$teams=[];
		/*
		// Lets see if we have github organisations
		if($user->getGithubToken()){
			
			$client = new Client(['base_uri' => 'https://api.github.com', 'headers'=>['Authorization'=>'Bearer '.$user->getGithubToken()]]);
			$response = $client->get('/user/orgs');
			$response = json_decode ($response->getBody(), true);
			var_dump($response);
			
			foreach($response as $organisation ){
				$teams[]= [
						"type"=>"github",
						"link"=>$organisation['url'],
						"id"=>$organisation['id'],
						"name"=>$organisation['login'],
						"avatar"=>$organisation['avatar_url']
				];
			}
		}
		*/
		// Lets see if we have gitlab groups
		if($user->getGitlabToken()){
			$client = new Client(['base_uri' => 'https://gitlab.com', 'headers'=>['Authorization'=>'Bearer '.$user->getGitlabToken()]]);
			$response = $client->get('/api/v4/groups');
			$response = json_decode ($response->getBody(), true);
			
			foreach($response as $organisation ){
				$teams[]= [
						"type"=>"gitlab",
						"link"=>$organisation['web_url'],
						"id"=>$organisation['id'],
						"name"=>$organisation['name'],
						"avatar"=>$organisation['avatar_url']
				];
			}
		}
		/*
		// Lets see if we have bitbucket teams
		if($user->getBitbucketToken()){
			
			$client = new Client(['base_uri' => 'https://api.bitbucket.org/', 'headers'=>['Authorization'=>'Bearer '.$user->getBitbucketToken()]]);
			$response = $client->get('/2.0/teams?role=member');
			$response = json_decode ($response->getBody(), true);
			var_dump($response);
			
			foreach($response['values'] as $organisation ){
				$teams[]= [
						"type"=>"bitbucket",
						"link"=>$organisation['links']['html']['href'],
						"id"=>$organisation['uuid'],
						"name"=>$organisation['display_name'],
						"avatar"=>$organisation['links']['avatar']['href']
				];
			}
		}
		*/
		
		return $team;
	}
		
	public function enrichTeams($teams)
	{
		$repository = $this->em->getRepository(Team::class);
		
		// Lets see if we already know these organisations
		foreach($teams as $team){
			$type = $organisation['type'];
			switch ($type) {
				case 'github':
					$team['commonground'] = $repository->findOneBy(['githubId' => $team['id']]);
					break;
				case 'bitbucket':
					$team['commonground'] = $repository->findOneBy(['bitbucketId' => $team['id']]);
					break;
				case 'gitlab':
					$team['commonground'] = $repository->findOneBy(['gitlabId' => $team['id']]);
					break;
			}
		}
		
		return $teams;
	}	
	
	public function getTeamOnSlug($slug)
	{
		$repository = $this->em->getRepository(Team::class);
		return $repository->findOneBy(['slug' => $slug]);
	}		
}
