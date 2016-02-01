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
        $qb = $this->createQueryBuilder('i');

        $qb->where(
            $qb->expr()->like(
                $qb->expr()->concat(
                    'i.tags',
                    $qb->expr()->literal(';')
                ),
                ':tag'
            )
        );
        $qb->setParameter('tag', "%$tag;%");

        return $qb->getQuery()->getResult();
    }
}