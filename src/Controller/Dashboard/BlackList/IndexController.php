<?php

declare(strict_types=1);

namespace App\Controller\Dashboard\BlackList;

use App\Service\BlackListService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('dashboard/i0n7v8d7o3m6')]
class IndexController extends AbstractController
{
    public function __construct(
        private readonly BlackListService $blackListService
    ) {
    }

    #[Route('/w0h5a6t0l1w2', name: 'app_dashboard_blacklist_index')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $user = $this->getUser();

        $blacklists = $this->blackListService->getAllByUser($user);

        return $this->render('dashboard/blacklist/index.html.twig', [
            'blacklists' => $blacklists,
        ]);
    }
}
