<?php

namespace App\Repository;

use App\Controller\ArticleController;
use App\Entity\Article;
use App\Entity\Form\ArticleSearchDto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class ArticleRepository
 *
 * @package App\Repository
 */
class ArticleRepository extends ServiceEntityRepository
{
    /**
     * ArticleRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @param ArticleSearchDto $articleSearchDto
     *
     * @return Article[]
     */
    public function getArticles(ArticleSearchDto $articleSearchDto): array
    {
        $qb = $this->getBaseTrainingListQuery()
            ->orderBy('a.id', 'DESC');

        $qb = $this->applyFilters($qb, $articleSearchDto);
        $page = $articleSearchDto->getPage() ? $articleSearchDto->getPage() : 1;

        $qb->setMaxResults(ArticleController::ITEMS_PER_PAGE);
        $qb->setFirstResult(ArticleController::ITEMS_PER_PAGE * ($page - 1));

        return $qb
            ->getQuery()
            ->getResult();
    }

    /**
     * @param ArticleSearchDto $articleSearchDto
     *
     * @return mixed
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getTrainingsCount(ArticleSearchDto $articleSearchDto)
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('count(t.id)')
            ->from($this->_entityName, 't');

        $qb = $this->applyFilters($qb, $articleSearchDto);

        return $qb
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param QueryBuilder $qb
     * @param ArticleSearchDto $articleSearchDto
     *
     * @return QueryBuilder
     */
    private function applyFilters(QueryBuilder $qb, ArticleSearchDto $articleSearchDto): QueryBuilder
    {
        if ($articleSearchDto->getAuthor()) {
            $qb->andWhere('a.author = ' . $articleSearchDto->getAuthor()->getId());
        }

        return $qb;
    }

    /**
     * @return QueryBuilder
     */
    private function getBaseTrainingListQuery(): QueryBuilder
    {
        return $this->_em->createQueryBuilder()
            ->select('a', 'u')
            ->from($this->_entityName, 'a')
            ->leftJoin('a.author', 'u');
    }
}
