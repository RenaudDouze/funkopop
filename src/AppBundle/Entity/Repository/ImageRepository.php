<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Image reposiroty
 */
class ImageRepository extends EntityRepository
{
    /**
     * Get from tag
     *
     * @return string $tag
     */
    public function findFromTag($tag)
    {
        $qb = $this->getFindQueryBuilder();

        $qb->where(
            $qb->expr()->like(
                $qb->expr()->concat(
                    $qb->expr()->concat(
                        $qb->expr()->literal(';'),
                        'i.tags'
                    ),
                    $qb->expr()->literal(';')
                ),
                ':tag'
            )
        );
        $qb->setParameter('tag', "%;$tag;%");

        return $qb->getQuery()->getResult();
    }

    /**
     * Get base find query builder
     *
     * @return QueryBuilder
     */
    public function getFindQueryBuilder()
    {
        $qb = $this->createQueryBuilder('i');
        
        $qb->orderBy('i.createdAt', 'desc');

        return $qb;
    }

    /**
     * Finds entities by a set of criteria.
     *
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @return array The objects.
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        if (null === $orderBy) {
            $orderBy = [
                'createdAt' => 'DESC',
            ];
        }

        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }
}