<?php

declare(strict_types=1);

namespace App\Controller\Dashboard\ApiKey;

use App\Controller\Dashboard\DashboardAbstractController;
use App\Service\ApiKeysService;
use App\Traits\FormValidationTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('dashboard/k0n7v8d7o3m6')]
class IndexController extends DashboardAbstractController
{
    use FormValidationTrait;

    private const DASHBOARD_API_KEYS = 'app_dashboard_api_keys_index';

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
            : $this->apiKeysService->getAllByUser($user);

        return $this->render('dashboard/api-keys/index.html.twig', [
            'apiKeys' => $apiKeys,
        ]);
    }

    #[Route('/p3s3n5n6s6x6', name: 'app_dashboard_api_keys_new', methods: ['POST'])]
    public function new(Request $request): Response
    {
        $this->hasRoleAdmin();

        $name = $this->validate($request->request->get('name'));
        $userAgent = $this->validate($request->request->get('userAgent'));

        if (empty($name) || empty($userAgent)) {
            $this->addFlash('warning', 'Please enter a valid name and user-agent.');
            return $this->redirectToRoute(self::DASHBOARD_API_KEYS);
        }

        $user = $this->getUser();

        if (count($this->apiKeysService->getAllByUser($user)) > 3 && !$this->isSuperAdmin()) {
            $this->addFlash('warning', 'You cannot add more than 3 keys.');
            return $this->redirectToRoute(self::DASHBOARD_API_KEYS);
        }

        $user = $this->getUser();
        $userAgent = $this->formatUserAgent($userAgent);
        $this->apiKeysService->create($user, $name, $userAgent);

        $this->addFlash('success', 'Api key created.');

        return $this->redirectToRoute(self::DASHBOARD_API_KEYS);
    }
}
