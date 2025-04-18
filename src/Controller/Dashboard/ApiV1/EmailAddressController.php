<?php

declare(strict_types=1);

namespace App\Controller\Dashboard\ApiV1;

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

    #[Route('hash-email', name: 'app_api_v1_hash_email', methods: ['GET'])]
    public function sha1HashEmail(Request $request): Response
    {
        $email = $request->get('email');

        if (empty($email)) {
            return $this->json([
                'success' => false,
                'message' => 'Parameter email cannot be empty.',
            ]);
        }

        return $this->json([
            'success' => true,
            'message' => sprintf('Hash of your email %s is: %s', $email, sha1($email)),
        ]);
    }

    #[Route('email/{hashEmail?}', name: 'app_api_v1_email', methods: ['GET'])]
    public function isEmailOnBlackList(?string $hashEmail, Request $request): Response
    {
        if (empty($hashEmail)) {
            return $this->fieldIsRequiredResponse('E-mail Address');
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

        $isOnBlackList = $this->blackListService->isEmailOnBlackList($api, $hashEmail);

        return $this->json([
            'success' => $isOnBlackList,
            'message' => $isOnBlackList ? 'on blacklist' : 'not on blacklist',
        ]);
    }
}
