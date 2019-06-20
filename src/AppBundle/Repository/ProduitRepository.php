<?php

namespace AppBundle\Repository;
use AppBundle\Entity\Campagne;
/**
 * ProduitRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProduitRepository extends \Doctrine\ORM\EntityRepository
{

      public function findByCampagne(Campagne $campagne,$all=true){
           $qb = $this->createQueryBuilder('p')
           ->where('p.campagne=:campagne')->setParameter('campagne', $campagne);
           if(!$all)
             $qb->andWhere('p.type=:type1 or p.type=:type2')
           ->setParameter('type1', 'produit')
           ->setParameter('type2', 'lot');
         return $qb->addOrderBy('p.nom','asc')->getQuery()->getResult();  
  }

  
	    public  function venteProduit(Campagne $campagne=null,$startDate=null, $endDate=null,$ville=null){
        $qb = $this->createQueryBuilder('p')
        ->leftJoin('p.lignes', 'l')
        ->leftJoin('l.commende', 'c')
        ->where('p.type=:type1 or p.type=:type2')->setParameter('type1','produit')->setParameter('type2','lot')
           ->andWhere('p.campagne=:campagne')->setParameter('campagne',$campagne);
         if($startDate!=null){
              $qb->andWhere('c.date is null or c.date>=:startDate')->setParameter('startDate', new \DateTime($startDate));
          }
          if($endDate!=null){
             $qb->andWhere('c.date is null or c.date<=:endDate')->setParameter('endDate',new \DateTime($endDate));
          }     
         $qb->select('p.id')
         ->addSelect('p.nom')
         ->addSelect('sum(l.quantite) as nombre')
         ->addSelect('sum(l.gratuite) as gratuite')
         ->addGroupBy('p.id')
         ->addGroupBy('p.nom');
           return $qb->getQuery()->getArrayResult(); 
  }
}
