<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ComponentRepository")
 */
class Component
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
	private $id;
	
	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $name;
	
	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $description;
	
	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $logo;
	
	/**
     * @Gedmo\Slug(fields={"name"}, unique=true)
	 * @ORM\Column(type="string", length=255)
	 */
	private $slug;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Organisation", inversedBy="components")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organisation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gitType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $git;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $openapi;
	
	public function getName(): ?string
                                    	{
                                    		return $this->name;
                                    	}
	
	public function setName(string $name): self
                                    	{
                                    		$this->name = $name;
                                    		
                                    		return $this;
                                    	}
	
	public function getDescription(): ?string
                                    	{
                                    		return $this->description;
                                    	}
	
	public function setDescription(?string $description): self
                                    	{
                                    		$this->description = $description;
                                    		
                                    		return $this;
                                    	}
	
	public function getLogo(): ?string
                                    	{
                                    		return $this->logo;
                                    	}
	
	public function setLogo(?string $logo): self
                                    	{
                                    		$this->logo = $logo;
                                    		
                                    		return $this;
                                    	}
	
	public function getSlug(): ?string
                                    	{
                                    		return $this->slug;
                                    	}
	
	public function setSlug(string $slug): self
                                    	{
                                    		$this->slug = $slug;
                                    		
                                    		return $this;
                                    	}

    public function getOrganisation(): ?Organisation
    {
        return $this->organisation;
    }

    public function setOrganisation(?Organisation $organisation): self
    {
        $this->organisation = $organisation;

        return $this;
    }

    public function getGitType(): ?string
    {
        return $this->gitType;
    }

    public function setGitType(string $gitType): self
    {
        $this->gitType = $gitType;

        return $this;
    }

    public function getGit(): ?string
    {
        return $this->git;
    }

    public function setGit(string $git): self
    {
        $this->git = $git;

        return $this;
    }

    public function getOpenapi(): ?string
    {
        return $this->openapi;
    }

    public function setOpenapi(?string $openapi): self
    {
        $this->openapi = $openapi;

        return $this;
    }
}
