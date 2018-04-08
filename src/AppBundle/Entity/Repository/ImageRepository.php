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

    /**
     * Retourne le top $nb des tags les plus utilisés
     *
     * @param integer $nb
     *
     * @return ArrayCollection Le tag en clé, le nombre en valeur
     */
    public function getTopTags($nb)
    {
        $tags = $this->getAllTags(false);

        $bestTags = array_count_values($tags);
        arsort($bestTags);

        $bestTags = array_slice($bestTags, 0, $nb);

        return $bestTags;
    }

    /**
     * Retourne la liste complète des tags
     *
     * @param boolean $distinct
     *
     * @return ArrayCollection
     */
    public function getAllTags($distinct = true)
    {
        $images = $this->findAll();

        $tags = [];
        foreach ($images as $image) {
            $tags = array_merge($tags, explode(';', $image->tags));
        }

        if ($distinct) {
            $tags = array_unique($tags);
        }

        return $tags;
    }

    /**
     * Cherche des tags
     *
     * @return string $search
     */
    public function findTags($search)
    {
        $qb = $this->getFindQueryBuilder();

        $qb->where(
            $qb->expr()->like('i.tags', ':tag')
        );
        $qb->setParameter('tag', "%$search%");

        $imagesWithTag = $qb->getQuery()->getResult();

        $tags = [];
        foreach ($imagesWithTag as $image) {
            $imageTags = explode(';', $image->tags);

            foreach($imageTags as $tag) {
                if (false !== strpos($tag, $search)) {
                    $tags[] = $tag;
                }
            }
        }

        $tags = array_unique($tags);
        asort($tags);
        $tags = array_values($tags);

        return $tags;
    }
}