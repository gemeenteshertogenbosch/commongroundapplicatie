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
use Doctrine\RST\Parser as ReStructuredText;

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
	
	
	public function getHelm(Component $component)
	{
		
		$lookIn = [
				'helm/',
				'api/helm/'
		];
		
		foreach ($lookIn as $location){
			if($responce = $this->github->getFileContent($component, $location.'values.yaml')){
				return Yaml::parse($responce);
			}
		}
		
		// If nothing is found
		return false;
	}
	
	public function getOpenApi(Component $component)
	{
		
		$lookIn = [
			'',
			'src/',
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
		$reStructuredText = new ReStructuredText();
		
		$git = [];
		$git['docs'] = [];
		$git['version'] = false;
		$git['tags'] = [];
		$git['servers'] = [];
		$git['links'] = [];
		
		$git['links']['repository'] = $component->getGit();
		
		/* should be moved to git service */
		if($component->getGitType()=="github"){
			
			$git['docs']['readme'] = $this->github->getFileContent($component, 'README.md');
			if($git['docs']['readme']){$git['docs']['readme'] = $this->markdown->transformMarkdown($git['docs']['readme']);}
			else{
				$git['docs']['readme'] = $this->github->getFileContent($component, 'README.rst');
				//if($git['docs']['readme']){$git['docs']['readme'] = $reStructuredText->parse($git['docs']['readme'])->render();}
			}
			
			$git['docs']['license'] = $this->github->getFileContent($component, 'LICENSE.md');
			if($git['docs']['license']){$git['docs']['license'] = $this->markdown->transformMarkdown($git['docs']['license']);}
			else{
				$git['docs']['license'] = $this->github->getFileContent($component, 'LICENSE.rst');
				if($git['docs']['license']){$git['docs']['license'] = $reStructuredText->parse($git['docs']['license'])->render();}
			}
			
			$git['docs']['changelog'] = $this->github->getFileContent($component, 'CHANGELOG.md');
			if($git['docs']['changelog'] ){$git['docs']['changelog'] = $this->markdown->transformMarkdown($git['docs']['changelog']);}
			else{
				$git['docs']['changelog'] = $this->github->getFileContent($component, 'CHANGELOG.rst');
				//if($git['docs']['changelog'] ){$git['docs']['changelog'] = $reStructuredText->parse($git['docs']['changelog'])->render();}
			}
			
			$git['docs']['contributing'] = $this->github->getFileContent($component, 'CONTRIBUTING.md');
			if($git['docs']['contributing']){$git['docs']['contributing'] = $this->markdown->transformMarkdown($git['docs']['contributing']);}
			else{
				$git['docs']['contributing'] = $this->github->getFileContent($component, 'CONTRIBUTING.rst');
				if($git['docs']['contributing']){$git['docs']['contributing'] = $reStructuredText->parse($git['docs']['contributing'])->render();}
			}
			
			$git['docs']['installation'] = $this->github->getFileContent($component, 'INSTALLATION.md');
			if($git['docs']['installation']){$git['docs']['installation'] = $this->markdown->transformMarkdown($git['docs']['installation']);}
			else{
				$git['docs']['installation'] = $this->github->getFileContent($component, 'INSTALLATION.rst');
				if($git['docs']['installation']){$git['docs']['installation'] = $reStructuredText->parse($git['docs']['installation'])->render();}
			}
			
			$git['docs']['tutorial'] = $this->github->getFileContent($component, 'TUTORIAL.md');
			if($git['docs']['tutorial']){$git['docs']['tutorial']	= $this->markdown->transformMarkdown($git['docs']['tutorial']);}
			else{
				$git['docs']['tutorial'] = $this->github->getFileContent($component, 'TUTORIAL.rst');
				if($git['docs']['tutorial']){$git['docs']['tutorial']	= $reStructuredText->parse($git['docs']['tutorial']);}
			}			
			
		}		
		
		// Lets then clear up al the html to mend broken links
		foreach($git['docs'] as $key => $value){
			$git['docs'][$key] = $this->parseHTML($component, $value);
		}
		
		// Lets transform das shizle
				
		$git['openapi'] = $this->getOpenApi($component);
		$git['helm'] = $this->getHelm($component);
				
		// Lets do something with this info
		if($git['openapi']['info']['version']){$git['version'] = $git['openapi']['info']['version'];}
		
		// Lets copy some component info		
		$git['name'] = $component->getName();
		$git['description'] = $component->getDescription();
		$git['logo'] = $component->getLogo();
		$git['git'] = $component->getGit();
		$git['gitType'] = $component->getGitType();
		$git['gitId'] = $component->getGitId();
		$git['id'] = $component->getId();
			
			
		// The we need to determine the type of te componet
		if($git['openapi'] && in_array('x-commonground',$git['openapi'])
				&& in_array('x-type',$git['openapi']['x-commonground']) ){
			$git['type'] = $git['openapi']['x-commonground']['type'];			
		}
		elseif($git['openapi']){
			$git['type'] = 'source';
		}
		else {
			$git['type'] = 'application';			
		}
		
		// We need to know the aditional owners
		if(array_key_exists('openapi', $git) && array_key_exists('x-commonground',$git['openapi']) && array_key_exists('developers',$git['openapi']['x-commonground'])){ 
			foreach($git['openapi']['x-commonground']['developers'] as $developer){				
				$organisations = $this->em->getRepository('App:Organisation')->findBy(array('github' => $developer['url']));
				foreach($organisations as $organisation){
					$component->addOrganisation($organisation);
					$this->em->persist($component);
					$this->em->flush();
				}
			}
		}
		
		$git['organisations']= [];
		foreach($component->getOrganisations() as $organisation){
			$git['organisations'][] = ['name'=>$organisation->getName(), 'url'=>$organisation->getGithub()];
			
		}
		
		return $git;
	}
	
	/**
	 *
	 *
	 * @param Component $component The component for wich the html is beings parsed
	 * @param string $html the html that is bieng parsed
	 */
	public function parseHTML(Component $component, $html)
	{			
		// The Regular Expression filter for an href url
		$reg_exUrl = '/<a\s+(?:[^>]*?\s+)?href=(["\'])(.*?)\1/';
		
		// Check if there is hhtl href url in the text
		if(preg_match_all($reg_exUrl, $html, $urls)) {
			
			// make the urls hyper links
			foreach($urls[0] as $key => $value){
				
				//var_dump($urls[2][$key]);
				// is the url is already valid we just continu
				if (strpos($urls[2][$key], "http") !== false) {
					continue;
				}
				$html = str_replace($urls[0][$key], '<a href="'.$component->getGit()."/blob/master/".$urls[2][$key].'" target="_blank" ', $html);
				continue;
			}
			
		} 
		
		// The Regular Expression filter for an img src
		$reg_exImage = '/<img\s+(?:[^>]*?\s+)?src=(["\'])(.*?)\1/';
		
		// Check if there is hhtl image in the text
		if(preg_match_all($reg_exImage, $html, $images)) {
			
			// make the urls hyper links
			foreach($images[0] as $key => $value){
				// is the url is already valid we just continu
				if (strpos($images[2][$key], "http") !== false) {
					continue;
				}
				$html = str_replace($images[0][$key], '<img src="'.$component->getGit()."/raw/master/".$images[2][$key].'" ', $html);
			}			
		} 
		
		return $html;
	}
	
	/**
	 * 
	 * 
	 * @param Component $component The component for wich git info is requested
	 * @param boolean $force If set to true will force a cash reset
	 */
	public function getComponentGit(Component $component, $force = false)
	{		
		$item = $this->cash->getItem('component_'.$component->getId());
		if ($item->isHit() && !$force) {
			return $item->get();
		}
		
		$value = $this->getComponentFromGit($component);	
		
		$item->set($value);
		$item->expiresAt(new \DateTime('tomorrow'));
		$this->cash->save($item);
		
		return $item->get();
	}
	
}
