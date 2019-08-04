<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 */
class Team
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Organisation", inversedBy="teams")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organisation;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="teams")
     */
    private $members;

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
    private $github;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $gitlab;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bitbucket;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $githubId;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $gitlabId;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bitbucketId;

    public function __construct()
    {
        $this->members = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|User[]
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(User $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
        }

        return $this;
    }

    public function removeMember(User $member): self
    {
        if ($this->members->contains($member)) {
            $this->members->removeElement($member);
        }

        return $this;
    }

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

    public function getGithub(): ?string
    {
        return $this->github;
    }

    public function setGithub(?string $github): self
    {
        $this->github = $github;

        return $this;
    }

    public function getGitlab(): ?string
    {
        return $this->gitlab;
    }

    public function setGitlab(?string $gitlab): self
    {
        $this->gitlab = $gitlab;

        return $this;
    }

    public function getBitbucket(): ?string
    {
        return $this->bitbucket;
    }

    public function setBitbucket(?string $bitbucket): self
    {
        $this->bitbucket = $bitbucket;

        return $this;
    }
    
    public function getGithubId(): ?string
    {
    	return $this->githubId;
    }
    
    public function setGithubId(?string $githubId): self
    {
    	$this->githubId= $githubId;
    	
    	return $this;
    }
    
    public function getGitlabId(): ?string
    {
    	return $this->gitlabId;
    }
    
    public function setGitlabId(?string $gitlabId): self
    {
    	$this->gitlabId= $gitlabId;
    	
    	return $this;
    }
    
    public function getBitbucketId(): ?string
    {
    	return $this->bitbucketId;
    }
    
    public function setBitbucketId(?string $bitbucketId): self
    {
    	$this->bitbucketId = $bitbucketId;
    	
    	return $this;
    }
}
