<?php

namespace App\Entity;

use App\Repository\ApiKeyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApiKeyRepository::class)]
#[ORM\Table(name: '`BL_api_key`')]
class ApiKey
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'apiKeys')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 50)]
    private string $version = 'v1';

    #[ORM\Column(length: 150)]
    private string $userAgent = "Awesome-App";

    #[ORM\Column(length: 255)]
    private string $apiKey = "";

    #[ORM\Column(length: 100)]
    private string $name = "Default API Key";

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    /**
     * @var Collection<int, BlackList>
     */
    #[ORM\OneToMany(targetEntity: BlackList::class, mappedBy: 'apiKey')]
    private Collection $blackLists;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->blackLists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): static
    {
        $this->version = $version;

        return $this;
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    public function setUserAgent(string $userAgent): static
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): static
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, BlackList>
     */
    public function getBlackLists(): Collection
    {
        return $this->blackLists;
    }

    public function addBlackList(BlackList $blackList): static
    {
        if (!$this->blackLists->contains($blackList)) {
            $this->blackLists->add($blackList);
            $blackList->setApiKey($this);
        }

        return $this;
    }

    public function removeBlackList(BlackList $blackList): static
    {
        if ($this->blackLists->removeElement($blackList)) {
            // set the owning side to null (unless already changed)
            if ($blackList->getApiKey() === $this) {
                $blackList->setApiKey(null);
            }
        }

        return $this;
    }
}
