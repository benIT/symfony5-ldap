<?php

namespace App\Controller;

use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(ServiceRepository $serviceRepository)
    {
        $services = $serviceRepository->findBy([], ['date' => 'DESC'], 10);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'services' => $services

        ]);
    }
}
