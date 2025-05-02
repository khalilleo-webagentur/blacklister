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

    public function getOneById(int $id): ?BlackList
    {
        return $this->blackListRepository->findOneBy(['id' => $id]);
    }

    public function getOneByUserAndId(UserInterface $user, int $id): ?BlackList
    {
        return $this->blackListRepository->findOneBy(['user' => $user, 'id' => $id]);
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
    public function getAllByApiKey(ApiKey $apiKey): array
    {
        return $this->blackListRepository->findBy(['apiKey' => $apiKey]);
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
        $blacklists = $this->getAllByApiKey($apiKey);

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
        $blacklists = $this->getAllByApiKey($apiKey);

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
        $blacklists = $this->getAllByApiKey($apiKey);

        foreach ($blacklists as $blacklist) {
            if ($blacklist->getDomain() === $domain) {
                $countDomainBlocked = $blacklist->getCountDomainBlocked();
                $this->save($blacklist->setCountDomainBlocked($countDomainBlocked + 1));
                return true;
            }
        }

        return false;
    }

    public function isIPAddressOnBlackList(ApiKey $apiKey, string $ipAddress): bool
    {
        $blacklists = $this->getAllByApiKey($apiKey);

        foreach ($blacklists as $blacklist) {
            if ($blacklist->getIpAddress() === $ipAddress) {
                $countIpBlocked = $blacklist->getCountIpAddressBlocked();
                $this->save($blacklist->setCountIpAddressBlocked($countIpBlocked + 1));
                return true;
            }
        }

        return false;
    }

    public function isURLOnBlackList(ApiKey $apiKey, string $url): bool
    {
        $blacklists = $this->getAllByApiKey($apiKey);

        foreach ($blacklists as $blacklist) {
            if ($blacklist->getUrl() === $url) {
                $countUrlBlocked = $blacklist->getCountUrlBlocked();
                $this->save($blacklist->setCountUrlBlocked($countUrlBlocked + 1));
                return true;
            }
        }

        return false;
    }

    public function create(
        UserInterface $user,
        ApiKey $apiKey,
        ?string $username,
        ?string $email,
        ?string $domain,
        ?string $ip,
        ?string $url,
    ): void {

        $blackList = new BlackList();
        $blackList
            ->setUser($user)
            ->setApiKey($apiKey)
            ->setUsername($username)
            ->setEmail($email)
            ->setDomain($domain)
            ->setIpAddress($ip)
            ->setUrl($url);

        $this->save($blackList);
    }

    public function getStatistics(UserInterface $user): array
    {
        $blacklists = $this->getAllByUser($user);

        $username = $email = $domain = $ip = $url = 0;

        foreach ($blacklists as $blacklist) {
            $username += $blacklist->getCountUsernameBlocked();
            $domain += $blacklist->getCountDomainBlocked();
            $email += $blacklist->getCountEmailBlocked();
            $ip += $blacklist->getCountIpAddressBlocked();
            $url += $blacklist->getCountUrlBlocked();
        }

        return [
            'username' => $username,
            'email' => $email,
            'domain' => $domain,
            'ip' => $ip,
            'url' => $url,
        ];
    }

    public function save(BlackList $blackList): BlackList
    {
        $this->blackListRepository->save($blackList->setUpdatedAt(new \DateTime()), true);
        return $blackList;
    }
}