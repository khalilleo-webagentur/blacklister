<?php

declare(strict_types=1);

namespace App\Controller\Dashboard;

use App\Entity\ApiKey;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardAbstractController extends AbstractController
{
    use FormValidationTrait;

    protected function isFieldEmpty(?string $field): bool
    {
        if (empty($field)) {
            return true;
        }

        return false;
    }

    protected function fieldIsRequiredResponse(string $field): Response
    {
        return $this->json([
            'success' => false,
            'message' => sprintf('Field %s is required.', $field),
        ]);
    }

    protected function isApiKeyValid(Request $request): bool
    {
        $apiKey = $request->headers->get('Authorization');

        if (empty($apiKey) || !$request->headers->has('Authorization') || !str_starts_with($apiKey, 'Bearer ')) {
           return false;
        }

        $token = substr($apiKey, 7);

        if (empty($token) || strlen($token) !== 36) {
          return false;
        }

        return true;
    }

    protected function hasHeaderUserAgent(Request $request, ApiKey $apiKey): bool
    {
        $userAgent = $request->headers->get('User-Agent');

        if (empty($userAgent) || !$request->headers->has('User-Agent')) {
            return false;
        }

        if ($userAgent !== $apiKey->getUserAgent()) {
            return false;
        }

        return true;
    }

    protected function notAuthorizedResponse(): Response
    {
        return $this->json([
            'success' => false,
            'message' => 'Not authorized'
        ], Response::HTTP_UNAUTHORIZED);
    }

    protected function getUserToken(Request $request): string
    {
        if (!$request->headers->has('Authorization')) {
            return '';
        }

        $apiKey = $request->headers->get('Authorization');
        return substr($apiKey, 7);
    }

    protected function isSuperAdmin(): bool
    {
        return $this->isGranted('ROLE_SUPER_ADMIN');
    }

    protected function hasRoleUser(): void
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
    }
}