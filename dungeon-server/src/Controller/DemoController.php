<?php
namespace App\Controller;

use App\Repository\DemoRepository;

class DemoController extends ApiController
{
    public function demonicAction(DemoRepository $demoRepository)
    {
        $demos = $demoRepository->transformAll();
        return $this->respond($demos);
    }
}
