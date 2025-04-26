<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ApiKey;
use App\Entity\BlackList;
use App\Repository\BlackListRepository;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class BlackListService
{
    public function __construct(
        private BlackListRepository $blackListRepository
    ) {
    }

    /**
     * @return BlackList[]
     */
    public function getAllByUser(UserInterface $user): array
    {
        return $this->blackListRepository->findBy(['user' => $user], ['id' => 'DESC']);
    }

    /**
     * @return BlackList[]
     */
    public function getAll(): array
    {
        return $this->blackListRepository->findBy([], ['id' => 'DESC']);
    }

    public function isUsernameOnBlackList(ApiKey $apiKey, string $username): bool
    {
        $blacklists = $this->getAllByUser($apiKey->getUser());

        foreach ($blacklists as $blacklist) {
            if ($blacklist->getUsername() === $username) {
                $countUsernameBlocked = $blacklist->getCountUsernameBlocked();
                $this->save($blacklist->setCountUsernameBlocked($countUsernameBlocked + 1));
                return true;
            }
        }

        return false;
    }

    public function isEmailOnBlackList(ApiKey $apiKey, string $email): bool
    {
        $blacklists = $this->getAllByUser($apiKey->getUser());

        foreach ($blacklists as $blacklist) {
            if ($blacklist->getEmail() === $email) {
                $countEmailBlocked = $blacklist->getCountEmailBlocked();
                $this->save($blacklist->setCountEmailBlocked($countEmailBlocked + 1));
                return true;
            }
        }

        return false;
    }

    public function isDomainOnBlackList(ApiKey $apiKey, string $domain): bool
    {
        $blacklists = $this->getAllByUser($apiKey->getUser());

        foreach ($blacklists as $blacklist) {
            if ($blacklist->getDomain() === $domain) {
                $countDomainBlocked = $blacklist->getCountDomainBlocked();
                $this->save($blacklist->setCountDomainBlocked($countDomainBlocked + 1));
                return true;
            }
        }

        return false;
    }

    public function create(UserInterface $user, ApiKey $apiKey, ?string $username, ?string $email, ?string $domain): void
    {
        $blackList = new BlackList();
        $blackList
            ->setUser($user)
            ->setApiKey($apiKey)
            ->setUsername($username)
            ->setEmail($email)
            ->setDomain($domain);

        $this->save($blackList);
    }

    public function save(BlackList $blackList): BlackList
    {
        $this->blackListRepository->save($blackList->setUpdatedAt(new \DateTime()), true);
        return $blackList;
    }
}