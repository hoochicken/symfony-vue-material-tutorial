<?php
namespace App\Controller;

use App\Repository\DemoRepository;
use Symfony\Component\HttpFoundation\Request;

class DemoController extends ApiController
{
    public function demonicAction(Request $request, DemoRepository $demoRepository)
    {
        $searchterm = trim($request->request->get('searchterm'));
        $listState = json_decode($request->request->get('listState'));
        $currentPage = $listState->currentPage ?? 0;
        $maxResult = $listState->maxResults ?? 10;

        // get items and pagination info
        $result = $demoRepository->findByName($searchterm, $currentPage, $maxResult);
        $items = $demoRepository->transformAll($result['items']);
        return $this->respond(['items' => $items, 'listState' => $result['listState']]);
    }
}
