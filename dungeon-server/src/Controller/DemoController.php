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
                'description' => 'Some description',
                'state' => 0
            ]
        ]);
    }
}
