<?php

declare(strict_types=1);

namespace App\Controller\Dashboard;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('dashboard/o3o4v1v3a1g8h2q2')]
class IndexController extends DashboardAbstractController
{
    #[Route('/home', name: 'app_dashboard_index')]
    public function index(): Response
    {
        $this->hasRoleAdmin();

        return $this->render('dashboard/index.html.twig');
    }
}
