<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ApiKey;
use App\Repository\ApiKeyRepository;
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

    public function getByUser(UserInterface $user): ?ApiKey
    {
        return $this->apiKeyRepository->findOneBy(['user' => $user], ['id' => 'DESC']);
    }

    /**
     * @return ApiKey[]
     */
    public function getAll(): array
    {
        return $this->apiKeyRepository->findBy([], ['id' => 'DESC']);
    }
}