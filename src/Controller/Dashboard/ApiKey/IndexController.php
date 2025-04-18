<?php

declare(strict_types=1);

namespace App\Controller\Dashboard\ApiKey;

use App\Controller\Dashboard\DashboardAbstractController;
use App\Service\ApiKeysService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('dashboard/k0n7v8d7o3m6')]
class IndexController extends DashboardAbstractController
{
    public function __construct(
        private readonly ApiKeysService $apiKeysService,
    ) {
    }

    #[Route('/k0h5a6t0l1w2', name: 'app_dashboard_api_keys_index')]
    public function index(): Response
    {
        $this->hasRoleAdmin();

        $user = $this->getUser();

        $apiKeys = $this->isSuperAdmin()
            ? $this->apiKeysService->getAll()
            : [$this->apiKeysService->getByUser($user)];

        return $this->render('dashboard/api-keys/index.html.twig', [
            'apiKeys' => $apiKeys,
        ]);
    }
}
