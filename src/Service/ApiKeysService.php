<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ApiKey;
use App\Repository\ApiKeyRepository;
use Khalilleo\TokenGen\Token;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class ApiKeysService
{
    public function __construct(
        private ApiKeyRepository $apiKeyRepository,
    ) {
    }

    public function getByApiKey(string $apiKey): ?ApiKey
    {
        return $this->apiKeyRepository->findOneBy(['apiKey' => $apiKey]);
    }

    /**
     * @return ApiKey[]
     *
     */
    public function getAllByUser(UserInterface $user): array
    {
        return $this->apiKeyRepository->findBy(['user' => $user], ['createdAt' => 'DESC']);
    }

    /**
     * @return ApiKey[]
     */
    public function getAll(): array
    {
        return $this->apiKeyRepository->findBy([], ['name' => 'ASC']);
    }

    public function create(UserInterface $user, string $name, string $userAgent): ApiKey
    {
        $apiKey = new ApiKey();
        $token = (new Token())->getRandomApiToken();

        if ($this->getByApiKey($token)) {
            $token = (new Token())->getRandomApiToken();
        }

        $apiKey
            ->setUser($user)
            ->setApiKey($token)
            ->setName($name)
            ->setUserAgent($userAgent);

        $this->save($apiKey);

        return $apiKey;
    }

    public function save(ApiKey $apiKey): void
    {
        $this->apiKeyRepository->save($apiKey->setUpdatedAt(new \DateTime()), true);
    }
}