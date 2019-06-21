<?php

namespace AppBundle\Repository;
use AppBundle\Entity\User; 
use AppBundle\Entity\Campagne;
/**
 * PointVenteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PointVenteRepository extends \Doctrine\ORM\EntityRepository
{
		  public function findByUser(User $user){
           $qb = $this->createQueryBuilder('p')
           ->where('p.user=:user')->setParameter('user', $user);
         return $qb->getQuery()->getResult();  
  }

    public   function ventePointVente(Campagne $campagne=null,$startDate=null, $endDate=null,$ville=null,$limit=true){

      $qb = $this->createQueryBuilder('p')->join('p.user','u')->join('p.commendes','c')->leftJoin('c.lignes','l');
         if($startDate!=null){
           $qb->andWhere('c.date is null or c.date>=:startDate')->setParameter('startDate', new \DateTime($startDate));
          }
          if($endDate!=null){
             $qb->andWhere('c.date is null or c.date<=:endDate')->setParameter('endDate',new \DateTime($endDate));
          }     
         $qb->andWhere('u.type=:type')->setParameter('type','superviseur') 
         ->andWhere('u.campagne=:campagne')->setParameter('campagne',$campagne);
         $qb
         ->select('u.username')
         ->addSelect('u.nom as sup')
         ->addSelect('u.secteur')
         ->addSelect('u.id as idSup')
         ->addSelect('p.nom')
         ->addSelect('p.quartier')
         ->addSelect('p.ba1')
         ->addSelect('p.ba2')
         ->addSelect('p.telGerant')
         ->addSelect('p.ville')
         ->addSelect('p.id')
         ->addSelect('sum(l.gratuite) as gratuite')
         ->addSelect('sum(l.quantite) as quantite')
         ->addSelect('count(DISTINCT c.date) as nombrejours')
         ->addSelect('max(c.nombreRessources) as nombreressources')
         ->addGroupBy('u.username')
         ->addGroupBy('p.id')
         ->addGroupBy('p.nom')
         ->addGroupBy('p.quartier')
         ->addGroupBy('p.telGerant')
         ->addGroupBy('p.ville')
         ->addGroupBy('p.ba1')
         ->addGroupBy('p.ba2')
         ->addGroupBy('u.nom')
         ->addGroupBy('u.id')
         ->addGroupBy('u.username')
          ->addGroupBy('u.secteur');
         if($limit) 
           return $qb->getQuery()->setMaxResults(10)->getArrayResult();
        return $qb->getQuery()->getArrayResult(); 
  }


    public   function venteParPointVente(Campagne $campagne=null,$startDate=null, $endDate=null,$ville=null,$limit=true){

      $qb = $this->createQueryBuilder('p')->join('p.user','u')->join('p.commendes','c')->leftJoin('c.lignes','l');
         if($startDate!=null){
           $qb->andWhere('c.date is null or c.date>=:startDate')->setParameter('startDate', new \DateTime($startDate));
          }
          if($endDate!=null){
             $qb->andWhere('c.date is null or c.date<=:endDate')->setParameter('endDate',new \DateTime($endDate));
          }     
         $qb->andWhere('u.type=:type')->setParameter('type','superviseur') 
         ->andWhere('u.campagne=:campagne')->setParameter('campagne',$campagne);
         $qb
         ->select('u.nom as sup')
         ->addSelect('u.secteur as secteur')
         ->addSelect('p.nom')
         ->addSelect('p.ba1')
         ->addSelect('p.ba2')
         ->addSelect('c.weekText as week')
         ->addSelect('p.ville')
         ->addSelect('p.id')
         ->addSelect('c.date')
         ->addGroupBy('p.id')
         ->addGroupBy('p.nom')
         ->addGroupBy('p.ville')
         ->addGroupBy('p.ba1')
         ->addGroupBy('p.ba2')
         ->addGroupBy('u.nom')
         ->addGroupBy('c.weekText')
         ->addGroupBy('c.date')
         ->addSelect('count(DISTINCT c.date) as nombrejours')
         ->addGroupBy('u.secteur');
         if($limit) 
           return $qb->getQuery()->setMaxResults(10)->getArrayResult();
        return $qb->getQuery()->getArrayResult(); 
  }

     public   function ventes(Campagne $campagne=null,$startDate=null, $endDate=null,$ville=null){
      $qb = $this->createQueryBuilder('p')
      ->leftJoin('p.commendes','c')
      ->leftJoin('c.lignes','l')
      ->andWhere('p.campagne=:campagne')
      ->setParameter('campagne',$campagne);
         if($startDate!=null){
           $qb->andWhere('c.date is null or c.date>=:startDate')->setParameter('startDate', new \DateTime($startDate));
          }
          if($endDate!=null){
             $qb->andWhere('c.date is null or c.date<=:endDate')->setParameter('endDate',new \DateTime($endDate));
          }     ;
         $qb
        ->select('sum(l.gratuite) as gratuite')
         ->addSelect('sum(l.quantite) as quantite')
         ->addSelect('count(DISTINCT c.date) as nombrejours')
         ->addSelect('count(DISTINCT p.id) as nombrepv');
        return $qb->getQuery()->getArrayResult(); 
  }
 	
}
