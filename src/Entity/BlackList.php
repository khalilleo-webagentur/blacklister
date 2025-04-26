<?php

namespace App\Entity;

use App\Repository\BlackListRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlackListRepository::class)]
#[ORM\Table(name: '`BL_black_list`')]
class BlackList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'blackLists')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'blackLists')]
    private ?ApiKey $apiKey = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $username = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $domain = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ipAddress = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url = null;

    #[ORM\Column]
    private int $countUsernameBlocked = 0;

    #[ORM\Column]
    private int $countEmailBlocked = 0;

    #[ORM\Column]
    private int $countDomainBlocked = 0;

    #[ORM\Column]
    private int $countUrlBlocked = 0;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
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

    public function getApiKey(): ?ApiKey
    {
        return $this->apiKey;
    }

    public function setApiKey(?ApiKey $apiKey): static
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(?string $domain): static
    {
        $this->domain = $domain;

        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(?string $ipAddress): static
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getCountUsernameBlocked(): int
    {
        return $this->countUsernameBlocked;
    }

    public function setCountUsernameBlocked(int $countUsernameBlocked): static
    {
        $this->countUsernameBlocked = $countUsernameBlocked;

        return $this;
    }

    public function getCountEmailBlocked(): int
    {
        return $this->countEmailBlocked;
    }

    public function setCountEmailBlocked(int $countEmailBlocked): static
    {
        $this->countEmailBlocked = $countEmailBlocked;

        return $this;
    }

    public function getCountDomainBlocked(): int
    {
        return $this->countDomainBlocked;
    }

    public function setCountDomainBlocked(int $countDomainBlocked): static
    {
        $this->countDomainBlocked = $countDomainBlocked;

        return $this;
    }

    public function getCountUrlBlocked(): int
    {
        return $this->countUrlBlocked;
    }

    public function setCountUrlBlocked(int $countUrlBlocked): static
    {
        $this->countUrlBlocked = $countUrlBlocked;

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
}
