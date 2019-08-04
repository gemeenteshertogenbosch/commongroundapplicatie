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

class ComponentService
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
		
	public function getAllComponents()
	{
	}	
	
	
	public function getOrganisationComponents(Organisation $organisation)
	{
	}	
	
}
