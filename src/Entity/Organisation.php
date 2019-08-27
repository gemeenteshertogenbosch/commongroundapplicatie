<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrganisationRepository")
 */
class Organisation
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
     * @ORM\OneToMany(targetEntity="App\Entity\Component", mappedBy="organisation", orphanRemoval=true)
     */
    private $components;

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

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="organisations")
     */
    private $users;
    
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User")     
     * @ORM\JoinTable(
     *  name="organisation_admin"
     * )
     */
    private $admins;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $css;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $javascript;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Team", mappedBy="organisation", orphanRemoval=true)
     */
    private $teams;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $subdomain;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $vetted;

    public function __construct()
    {
        $this->components = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->admins = new ArrayCollection();
        $this->teams = new ArrayCollection();
    }
    
    public function getId(): ?string
    {
    	return $this->id;
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

    /**
     * @return Collection|Component[]
     */
    public function getComponents(): Collection
    {
        return $this->components;
    }

    public function addComponent(Component $component): self
    {
        if (!$this->components->contains($component)) {
            $this->components[] = $component;
            $component->setOrganisation($this);
        }

        return $this;
    }

    public function removeComponent(Component $component): self
    {
        if ($this->components->contains($component)) {
            $this->components->removeElement($component);
            // set the owning side to null (unless already changed)
            if ($component->getOrganisation() === $this) {
                $component->setOrganisation(null);
            }
        }

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
    
    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }
    
    /**
     * @return Collection|Admin[]
     */
    public function getAdmins(): Collection
    {
    	return $this->admins;
    }
    
    public function addAdmin(User $admin): self
    {
    	if (!$this->admins->contains($admin)) {
    		$this->admins[] = $admin;
    	}
    	
    	return $this;
    }
    
    public function removeAdmin(User $admin): self
    {
    	if ($this->admins->contains($admin)) {
    		$this->admins->removeElement($admin);
    	}
    	
    	return $this;
    }

    public function getCss(): ?string
    {
        return $this->css;
    }

    public function setCss(?string $css): self
    {
        $this->css = $css;

        return $this;
    }

    public function getJavascript(): ?string
    {
        return $this->javascript;
    }

    public function setJavascript(?string $javascript): self
    {
        $this->javascript = $javascript;

        return $this;
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams[] = $team;
            $team->setOrganisation($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->teams->contains($team)) {
            $this->teams->removeElement($team);
            // set the owning side to null (unless already changed)
            if ($team->getOrganisation() === $this) {
                $team->setOrganisation(null);
            }
        }

        return $this;
    }

    public function getSubdomain(): ?bool
    {
        return $this->subdomain;
    }

    public function setSubdomain(?bool $subdomain): self
    {
        $this->subdomain = $subdomain;

        return $this;
    }

    public function getVetted(): ?bool
    {
        return $this->vetted;
    }

    public function setVetted(?bool $vetted): self
    {
        $this->vetted = $vetted;

        return $this;
    }
}
