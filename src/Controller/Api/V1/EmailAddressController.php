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
class EmailAddressController extends DashboardAbstractController
{
    public function __construct(
        private readonly ApiKeysService   $apiKeysService,
        private readonly BlackListService $blackListService,
    ) {
    }

    #[Route('email', name: 'app_api_v1_email', methods: ['GET'])]
    public function isEmailOnBlackList(Request $request): Response
    {
        $email = $request->query->get('email');

        if (empty($email)) {
            return $this->fieldIsRequiredResponse('email');
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

        $isOnBlackList = $this->blackListService->isEmailOnBlackList($api, $email);

        return $this->json([
            'success' => $isOnBlackList,
            'message' => $isOnBlackList ? 'on blacklist' : 'not on blacklist',
        ]);
    }
}
