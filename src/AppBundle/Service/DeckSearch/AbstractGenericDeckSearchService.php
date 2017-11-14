<?php

namespace AppBundle\Service\DeckSearch;

use AppBundle\Entity\Card;
use AppBundle\Entity\Deck;
use AppBundle\Search\DeckSearch;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of AbstractGenericDeckSearchService
 *
 * @author Alsciende <alsciende@icloud.com>
 */
abstract class AbstractGenericDeckSearchService extends AbstractDeckSearchService
{
    abstract public function getOrderedQuery (QueryBuilder $qb): Query;

    public function search (DeckSearch $search)
    {
        $query = $this
            ->getBaseQueryBuilder($search)
            ->select('COUNT(d)')
            ->getQuery();

        $query = $this
            ->getParametrizedQuery($query, $search);

        $search->setTotal((int) $query->getSingleScalarResult());

        $qb = $this
            ->getBaseQueryBuilder($search)
            ->select('d, u, COUNT(DISTINCT l.user) nbLikes, COUNT(DISTINCT c.id) nbComments')
            ->join('d.user', 'u')
            ->leftJoin('d.deckLikes', 'l')
            ->leftJoin('d.comments', 'c')
            ->groupBy('d, u');

        $query = $this
            ->getParametrizedQuery(
                $this->getOrderedQuery($qb),
                $search
            )
            ->setFirstResult($search->getFirstIndex())
            ->setMaxResults($search->getLimit());

        foreach ($query->getResult() as $result) {
            /** @var Deck $deck */
            $deck = $result[0];
            $deck->setNbLikes((int) $result['nbLikes']);
            $deck->setNbComments((int) $result['nbComments']);
            $search->addRecord($deck);
        }
    }

    private function getBaseQueryBuilder (DeckSearch $search): QueryBuilder
    {
        $qb = $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->from('AppBundle:Deck', 'd')
            ->where('d.published = :published');

        if ($search->getSince() instanceof \DateTime) {
            $qb->andWhere('d.publishedAt >= :date');
        }

        if ($search->getClan() !== null) {
            $qb->andWhere('d.primaryClan = :clan');
        }

        if($search->isFeatured()) {
            $qb->innerJoin('AppBundle:Feature', 'f', Query\Expr\Join::INNER_JOIN, 'f.deck = d');
        }

        if($search->getCard() instanceof Card) {
            $qb2 = $qb->getEntityManager()->createQueryBuilder()
                ->select('dc')
                ->from('AppBundle:DeckCard','dc')
                ->where('dc.card = :card')
                ->andWhere('dc.deck = d');
            $qb->andWhere($qb->expr()->exists($qb2->getDQL()));
        }

        return $qb;
    }

    private function getParametrizedQuery (Query $query, DeckSearch $search): Query
    {
        $query->setParameter('published', true);

        if ($search->getSince() instanceof \DateTime) {
            $query->setParameter('date', $search->getSince());
        }

        if ($search->getClan() !== null) {
            $query->setParameter('clan', $search->getClan());
        }

        if($search->getCard() instanceof Card) {
            $query->setParameter('card', $search->getCard());
        }
        return $query;
    }
}