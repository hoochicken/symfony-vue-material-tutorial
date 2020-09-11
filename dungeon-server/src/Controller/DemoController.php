<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DemoController extends ApiController
{
    /**
     * @Route("/demo")
     */
    public function demonicAction()
    {
        return $this->respond([
            [
                'title' => 'The Plain Demo',
                'count' => 0
            ]
        ]);
    }
}
