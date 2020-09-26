<?php

namespace App\Repository;

use App\Entity\Demo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Demo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Demo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Demo[]    findAll()
 * @method Demo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Demo::class);
    }

    public function transform(Demo $demo)
    {
        return [
            'id'    => (int) $demo->getId(),
            'title' => (string) $demo->getTitle(),
            'description' => (string) $demo->getDescription(),
            'state' => (int) $demo->getState()
        ];
    }

    /**
     * @param $value
     * @param int $currentPage
     * @param int $maxResults
     * @return array [] Returns an array of Place objects
     */
    public function findByName($value, int $currentPage = 0, int $maxResults = 0)
    {
        $firstResult = $maxResults * $currentPage;
        $qb = $this->createQueryBuilder('h');

        if (!empty($value)) {
            $qb->andWhere('h.name LIKE :val')
                ->setParameter('val', '%' . $value . '%');
        }

        $qb->setFirstResult($firstResult)
            ->setMaxResults($maxResults)
            ->orderBy('h.id', 'ASC');

        $query = $qb->getQuery();
        return ['items' => $query->getResult(), 'listState' => $this->getListState($query, $maxResults, $firstResult, $currentPage)];
    }

    public function transformAll($items)
    {
        $return = [];
        foreach ($items as $demoSingle) {
            $return[] = $this->transform($demoSingle);
        }

        return $return;
    }

    public function getListState($query, $maxResults, $firstResult, $currentPage)
    {
        // load doctrine Paginator
        $paginator = new Paginator($query);

        // you can get total items
        $totalItems = count($paginator);

        // get total pages
        $totalPage = $maxResults > 0 ? ceil($totalItems / $maxResults) : $totalItems;

        // now get one page's items:
        $paginator
            ->getQuery()
            ->setFirstResult($firstResult) // set the offset
            ->setMaxResults($maxResults);

        $listState = [
            'currentPage' => $currentPage,
            'maxResults' => $maxResults,
            'totalPage' => $totalPage,
            'firstResult' => $firstResult,
            'totalItems' => $totalItems,
        ];
        return $listState;
    }

    // /**
    //  * @return Demo[] Returns an array of Demo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Demo
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
