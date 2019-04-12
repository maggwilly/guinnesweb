<?php

namespace AppBundle\Repository;

/**
 * ProduitRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProduitRepository extends \Doctrine\ORM\EntityRepository
{

	    public  function produits($startDate=null, $endDate=null){

        $qb = $this->createQueryBuilder('p')->leftJoin('p.lignes', 'l')->leftJoin('l.commende', 'c');
         if($startDate!=null){
              $qb->andWhere('c.date is null or c.date>=:startDate')->setParameter('startDate', new \DateTime($startDate));
          }
          if($endDate!=null){
             $qb->andWhere('c.date is null or c.date<=:endDate')->setParameter('endDate',new \DateTime($endDate));
          }     
         $qb->select('p.id')
         ->addSelect('p.nom')
         ->addSelect('sum(l.quantite) as nombre')
         ->addGroupBy('p.id')
         ->addGroupBy('p.nom');
           return $qb->getQuery()->getArrayResult(); 
  }
}