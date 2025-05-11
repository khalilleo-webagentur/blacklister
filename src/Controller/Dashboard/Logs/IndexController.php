<?php

declare(strict_types=1);

namespace App\Controller\Dashboard\Logs;

use App\Controller\Dashboard\DashboardAbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('dashboard/i0n7v8d7o3m6')]
class IndexController extends DashboardAbstractController
{
    #[Route('/loo5a6t0l1w2', name: 'app_dashboard_logs_index')]
    public function index(): Response
    {
        $this->hasRoleUser();

        return $this->render('dashboard/logs/index.html.twig');
    }
}
