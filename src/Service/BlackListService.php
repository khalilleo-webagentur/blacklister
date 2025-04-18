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

    public function isEmailOnBlackList(ApiKey $apiKey, string $hashEmail): bool
    {
        $blacklists = $this->getAllByUser($apiKey->getUser());

        foreach ($blacklists as $blacklist) {
            if (sha1($blacklist->getEmail()) === $hashEmail) {
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

            $domain = str_replace('_', '.', $domain);

            if ($blacklist->getDomain() === $domain) {
                $countDomainBlocked = $blacklist->getCountDomainBlocked();
                $this->save($blacklist->setCountDomainBlocked($countDomainBlocked + 1));
                return true;
            }
        }

        return false;
    }

    public function save(BlackList $blackList): BlackList
    {
        $this->blackListRepository->save($blackList, true);
        return $blackList;
    }
}