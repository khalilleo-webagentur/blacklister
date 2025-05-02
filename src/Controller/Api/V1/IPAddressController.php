<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\Controller\Dashboard\DashboardAbstractController;
use App\Service\ApiKeysService;
use App\Service\BlackListService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('api/v1/')]
class IPAddressController extends DashboardAbstractController
{
    public function __construct(
        private readonly ApiKeysService   $apiKeysService,
        private readonly BlackListService $blackListService,
    ) {
    }

    #[Route('ip', name: 'app_api_v1_ips_index', methods: ['GET'])]
    public function isIPAddressOnBlackList(Request $request): Response
    {
        $domain = $request->query->get('ip');

        if ($this->isFieldEmpty($domain)) {
            return $this->fieldIsRequiredResponse('ip');
        }

        if (false === $this->isApiKeyValid($request)) {
            return $this->notAuthorizedResponse();
        }

        $token = $this->getUserToken($request);

        if (empty($token)) {
            return $this->notAuthorizedResponse();
        }

        $api = $this->apiKeysService->getByApiKey($token);

        if (!$api) {
            return $this->notAuthorizedResponse();
        }

        if (false === $this->hasHeaderUserAgent($request, $api)) {
            return $this->notAuthorizedResponse();
        }

        $isOnBlackList = $this->blackListService->isIPAddressOnBlackList($api, $domain);

        return $this->json([
            'success' => $isOnBlackList,
            'message' => $isOnBlackList ? 'on blacklist' : 'not on blacklist',
        ]);
    }
}
