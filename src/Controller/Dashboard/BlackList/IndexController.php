<?php

declare(strict_types=1);

namespace App\Controller\Dashboard\BlackList;

use App\Controller\Dashboard\DashboardAbstractController;
use App\Service\ApiKeysService;
use App\Service\BlackListService;
use App\Traits\FormValidationTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('dashboard/i0n7v8d7o3m6')]
class IndexController extends DashboardAbstractController
{
    use FormValidationTrait;

    private const DASHBOARD_BLACKLISTS = 'app_dashboard_blacklist_index';

    public function __construct(
        private readonly BlackListService $blackListService,
        private readonly ApiKeysService   $apiKeysService,
    ) {
    }

    #[Route('/w0h5a6t0l1w2', name: 'app_dashboard_blacklist_index')]
    public function index(): Response
    {
        $this->hasRoleAdmin();

        $user = $this->getUser();

        $blacklists = $this->isSuperAdmin()
            ? $this->blackListService->getAll()
            : $this->blackListService->getAllByUser($user);

        $apiKeys = $this->apiKeysService->getAllByUser($user);

        return $this->render('dashboard/blacklist/index.html.twig', [
            'blacklists' => $blacklists,
            'apiKeys' => $apiKeys,
        ]);
    }

    #[Route('/d3v1h3e2j4b3', name: 'app_dashboard_blacklist_new', methods: ['POST'])]
    public function new(Request $request): Response
    {
        $this->hasRoleAdmin();

        $username = $this->validate($request->request->get('username'));
        $email = $this->validate($request->request->get('email'));
        $domain = $this->validate($request->request->get('domain'));
        $ipAddress = $this->validate($request->request->get('ip'));
        $url = $this->validate($request->request->get('url'));
        $apiKeyId = $this->validateNumber($request->request->get('apiKey'));

        if (empty($username) && empty($email) && empty($domain) && empty($ipAddress) && empty($url)) {
            $this->addFlash('warning', 'At least on of them must be filled.');
            return $this->redirectToRoute(self::DASHBOARD_BLACKLISTS);
        }

        $user = $this->getUser();
        $apiKey = $this->apiKeysService->getOneByUserAndId($user, $apiKeyId);

        if (!$apiKey) {
            $this->addFlash('warning', 'API Key not found.');
            return $this->redirectToRoute(self::DASHBOARD_BLACKLISTS);
        }

        $this->blackListService->create($user, $apiKey, $username, $email, $domain, $ipAddress, $url);

        $this->addFlash('success', 'Added blacklist successfully.');

        return $this->redirectToRoute(self::DASHBOARD_BLACKLISTS);
    }
}
