<?php

namespace AppBundle\Repository;
use Doctrine\ORM\NoResultException;
/**
 * LigneRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LigneRepository extends \Doctrine\ORM\EntityRepository
{


  


   public function countAndCash( $startDate=null, $endDate=null,PointVente $pointVente=null){

        $qb = $this->createQueryBuilder('l')->join('l.commende', 'c')->join('l.produit', 'p');
         if($startDate!=null){
           $qb->andWhere('c.date is null or c.date>=:startDate')->setParameter('startDate', new \DateTime($startDate));
          }
          if($endDate!=null){
           $qb->andWhere('c.date is null or c.date<=:endDate')->setParameter('endDate',new \DateTime($endDate));
          } 

         if ($pointVente!=null) {
             $qb->andWhere('c.pointVente=:pointVente')->setParameter('pointVente', $pointVente);
          }    
       $qb->select('sum(l.quantite) as nombre')->addSelect('sum(l.quantite*p.cout) as total');
         return $qb->getQuery()->getArrayResult();  
  }


   public function countAndCashByWeek( $startDate=null, $endDate=null){
        $qb = $this->createQueryBuilder('l')->join('l.commende', 'c')->join('l.produit', 'p');
         if($startDate!=null){
           $qb->andWhere('c.date is null or c.date>=:startDate')->setParameter('startDate', new \DateTime($startDate));
          }
          if($endDate!=null){
           $qb->andWhere('c.date is null or c.date<=:endDate')->setParameter('endDate',new \DateTime($endDate));
          } 
   
       $qb->addOrderBy('c.week','asc')
       ->select('c.weekText')
       ->addSelect('sum(l.quantite) as nombre')
       ->addSelect('sum(l.quantite*p.cout) as total')
       ->addGroupBy('c.weekText');
         return $qb->getQuery()->getArrayResult();  
  }

   public function countAndCashByMonth( $startDate=null, $endDate=null){
        $qb = $this->createQueryBuilder('l')->join('l.commende', 'c')->join('l.produit', 'p');
         if($startDate!=null){
           $qb->andWhere('c.date is null or c.date>=:startDate')->setParameter('startDate', new \DateTime($startDate));
          }
          if($endDate!=null){
           $qb->andWhere('c.date is null or c.date<=:endDate')->setParameter('endDate',new \DateTime($endDate));
          } 
   
       $qb->addOrderBy('c.month','asc')
       ->select('c.month')
       ->addSelect('sum(l.quantite) as nombre')
       ->addSelect('sum(l.quantite*p.cout) as total')
       ->addGroupBy('c.month');
         return $qb->getQuery()->getArrayResult();  
  }

}
