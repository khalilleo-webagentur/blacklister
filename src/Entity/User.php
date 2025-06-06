<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`BL_user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: 'json')]
    private array $roles = ['ROLE_USER'];

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $token = null;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\Column]
    private bool $isDeleted = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    /**
     * @var Collection<int, UserSetting>
     */
    #[ORM\OneToMany(targetEntity: UserSetting::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $userSettings;

    /**
     * @var Collection<int, TempUser>
     */
    #[ORM\OneToMany(targetEntity: TempUser::class, mappedBy: 'user')]
    private Collection $tempUsers;

    /**
     * @var Collection<int, BlackList>
     */
    #[ORM\OneToMany(targetEntity: BlackList::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $blackLists;

    /**
     * @var Collection<int, ApiKey>
     */
    #[ORM\OneToMany(targetEntity: ApiKey::class, mappedBy: 'user')]
    private Collection $apiKeys;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
        $this->userSettings = new ArrayCollection();
        $this->tempUsers = new ArrayCollection();
        $this->blackLists = new ArrayCollection();
        $this->apiKeys = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    public function setDeleted(bool $isDeleted): static
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
        //
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
     * @return Collection<int, UserSetting>
     */
    public function getUserSettings(): Collection
    {
        return $this->userSettings;
    }

    public function addUserSetting(UserSetting $userSetting): static
    {
        if (!$this->userSettings->contains($userSetting)) {
            $this->userSettings->add($userSetting);
            $userSetting->setUser($this);
        }

        return $this;
    }

    public function removeUserSetting(UserSetting $userSetting): static
    {
        if ($this->userSettings->removeElement($userSetting)) {
            // set the owning side to null (unless already changed)
            if ($userSetting->getUser() === $this) {
                $userSetting->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TempUser>
     */
    public function getTempUsers(): Collection
    {
        return $this->tempUsers;
    }

    public function addTempUser(TempUser $tempUser): static
    {
        if (!$this->tempUsers->contains($tempUser)) {
            $this->tempUsers->add($tempUser);
            $tempUser->setUser($this);
        }

        return $this;
    }

    public function removeTempUser(TempUser $tempUser): static
    {
        if ($this->tempUsers->removeElement($tempUser)) {
            // set the owning side to null (unless already changed)
            if ($tempUser->getUser() === $this) {
                $tempUser->setUser(null);
            }
        }

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
            $blackList->setUser($this);
        }

        return $this;
    }

    public function removeBlackList(BlackList $blackList): static
    {
        if ($this->blackLists->removeElement($blackList)) {
            // set the owning side to null (unless already changed)
            if ($blackList->getUser() === $this) {
                $blackList->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ApiKey>
     */
    public function getApiKeys(): Collection
    {
        return $this->apiKeys;
    }

    public function addApiKey(ApiKey $apiKey): static
    {
        if (!$this->apiKeys->contains($apiKey)) {
            $this->apiKeys->add($apiKey);
            $apiKey->setUser($this);
        }

        return $this;
    }

    public function removeApiKey(ApiKey $apiKey): static
    {
        if ($this->apiKeys->removeElement($apiKey)) {
            // set the owning side to null (unless already changed)
            if ($apiKey->getUser() === $this) {
                $apiKey->setUser(null);
            }
        }

        return $this;
    }
}
