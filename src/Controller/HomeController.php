<?php

namespace App\Controller;

use App\Service\CallApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(CallApiService $callApiService): Response
    {
      //  dd($callApiService->getDataFrance());
        return $this->render('home/index.html.twig', [
            'data' => $callApiService->getDataFrance(),
        ]);
    }
}
