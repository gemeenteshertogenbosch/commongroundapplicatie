<?php
// src/Service/AmbtenaarService.php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use GuzzleHttp\Client;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Cache\Adapter\AdapterInterface as CacheInterface;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;

class OrganisationService
{
	private $params;
	
	public function __construct(ParameterBagInterface $params, MarkdownParserInterface $markdown, CacheInterface $cache)
	{
		$this->params = $params;
		$this->cash = $cache;
		$this->markdown = $markdown;
	}
		
	public function getOrganisationOnSlug($slug)
	{
	}		
	
	public function getOrganisationFromGithub($slug)
	{
	}	
}
