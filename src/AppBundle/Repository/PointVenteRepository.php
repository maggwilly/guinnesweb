<?php

namespace AppBundle\Repository;
use AppBundle\Entity\User; 
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

    public function fiedSoldiersCount( $startDate=null, $endDate=null){

        $qb = $this->createQueryBuilder('p')->join('p.commendes', 'c');
         if($startDate!=null){
           $qb->andWhere('c.date is null or c.date>=:startDate')->setParameter('startDate', new \DateTime($startDate));
          }
          if($endDate!=null){
           $qb->andWhere('c.date is null or c.date<=:endDate')->setParameter('endDate',new \DateTime($endDate));
          }     
try {
    $qb->select('count(DISTINCT p.id) as nombre');
         return $qb->getQuery()->getSingleScalarResult();  
   } catch (NoResultException $e) {
        return 0;
     }
  }

    public function fieldSoldiers( $startDate=null, $endDate=null,$all=false){

        $qb = $this->createQueryBuilder('p')->join('p.commendes', 'c');
         if($startDate!=null){
           $qb->andWhere('c.date is null or c.date>=:startDate')->setParameter('startDate', new \DateTime($startDate));
          }
          if($endDate!=null){
           $qb->andWhere('c.date is null or c.date<=:endDate')->setParameter('endDate',new \DateTime($endDate));
          }
          if (!$all) 
           return $qb->getQuery()->setMaxResults(10)->getResult();

        return $qb->getQuery()->getResult();   
  } 

   public  function ventePeriode($startDate=null, $endDate=null){

      $RAW_QUERY ='select u.nom as supernom, fs.nom as fsnom,fs.secteur as fsserietablette, fs.telephone as fstelephone, NULL as fsorange, (case when p.id in(1,2) then l.quantite else 0 end)  as souscription, (case when p.id in(3,4) then l.quantite else 0 end)  as renouvellement, s.nom as snom, s.prenom as sprenom, s.telephone as stelephone, (p.cout*l.quantite) as montant, s.contrat, s.mode from 
         point_vente fs 
         join commende c on fs.id=c.point_vente_id 
         join user_account u  on u.id=fs.user_id
         left join ligne l on l.commende_id=c.id
         left join produit p on l.produit_id=p.id 
         left join souscripteur s on l.souscripteur_id=s.id
         where c.date>=:startDate and c.date<=:endDate';
         $statement = $this->_em->getConnection()->prepare($RAW_QUERY);
         $startDate=new \DateTime($startDate);
         $endDate=new \DateTime($endDate);
         $statement->bindValue('startDate', $startDate->format('Y-m-d'));
         $statement->bindValue('endDate',  $endDate->format('Y-m-d'));
         $statement->execute();
       return  $result = $statement->fetchAll();
  }

     public  function recapPeriode($startDate=null, $endDate=null){

      $RAW_QUERY ='select u.nom as supernom, fs.nom as fsnom,fs.secteur as fsserietablette, fs.telephone as fstelephone, sum((case when p.id in(1,2) then 1 else 0 end)) as souscription, sum((case when p.id in(3,4) then 1 else 0 end))  as renouvellement, sum(l.quantite) as total, count(DISTINCT c.date) as nbjours from 
         point_vente fs 
         join commende c on fs.id=c.point_vente_id 
         join user_account u  on u.id=fs.user_id
         join ligne l on l.commende_id=c.id
         join produit p on l.produit_id=p.id 
         join souscripteur s on l.souscripteur_id=s.id
         where c.date>=:startDate and c.date<=:endDate
          group by u.nom,fs.nom, fs.telephone,fs.secteur';
         $statement = $this->_em->getConnection()->prepare($RAW_QUERY);
         $startDate=new \DateTime($startDate);
         $endDate=new \DateTime($endDate);
         $statement->bindValue('startDate', $startDate->format('Y-m-d'));
         $statement->bindValue('endDate',  $endDate->format('Y-m-d'));
         $statement->execute();
       return  $result = $statement->fetchAll();
  } 	
}
