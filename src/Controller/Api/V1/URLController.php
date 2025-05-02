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
class URLController extends DashboardAbstractController
{
    public function __construct(
        private readonly ApiKeysService   $apiKeysService,
        private readonly BlackListService $blackListService,
    ) {
    }

    #[Route('url', name: 'app_api_v1_url_index', methods: ['GET'])]
    public function isURLOnBlackList(Request $request): Response
    {
        $url = $request->query->get('url');

        if ($this->isFieldEmpty($url)) {
            return $this->fieldIsRequiredResponse('url');
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

        $isOnBlackList = $this->blackListService->isURLOnBlackList($api, $url);

        return $this->json([
            'success' => $isOnBlackList,
            'message' => $isOnBlackList ? 'on blacklist' : 'not on blacklist',
        ]);
    }
}
