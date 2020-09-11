<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class DemoController extends ApiController
{
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
