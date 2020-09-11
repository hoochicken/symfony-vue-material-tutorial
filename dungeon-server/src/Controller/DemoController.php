<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DemoController
{
    /**
     * @Route("/demo")
     */
    public function demonicAction()
    {
        return new JsonResponse([
            [
                'title' => 'The Real Demo',
                'count' => 0
            ]
        ]);
    }
}
