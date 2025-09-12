<?php

namespace App\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'admin_dashboard_show')]
    public function dashboard(): Response
    {

        return $this->render('admin/pages/dashboard.html.twig');
    }

}
