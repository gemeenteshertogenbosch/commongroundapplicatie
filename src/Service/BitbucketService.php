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

class BitbucketService
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
			$this->client = new Client(['base_uri' => 'https://api.bitbucket.org/2.0/', 'headers'=>['Authorization'=>'Bearer '.$user->getGithubToken()]]);
		}
		else{
			$this->client = new Client(['base_uri' => 'https://api.bitbucket.org/2.0/']);
		}
	}
		
	public function getRepositories($organisations)
	{
	}		
		
	// we always use a user to ask api qoustions
	public function getTeam($id)
	{
	}
	
	// we always use a user to ask api qoustions
	public function getProject($id)
	{
	}	
}
