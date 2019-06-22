<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use AppBundle\Form\CredentialsType;
use AppBundle\Entity\AuthToken;
use AppBundle\Entity\Credentials;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use FOS\RestBundle\View\View; 
use AppBundle\Entity\PointVente;
use AppBundle\Entity\Produit;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use AppBundle\Entity\Campagne;
/**
 * Etape controller.
 *
 */
class AppController extends Controller
{
    /**
     * Lists all etape entities.
     *
     */
    public function indexAction(Campagne $campagne=null)
    {   
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $startDate= new \DateTime('first day of this month');
        $endDate= new  \DateTime('last day of this month');
        if(is_null($session->get('startDate'))){
        $session->set('startDate',$startDate->format('Y-m-d'));
        $session->set('endDate',$endDate->format('Y-m-d'));
        $session->set('end_date_formated',$endDate->format('d/m/Y'));
        $session->set('start_date_formated',$startDate->format('d/m/Y'));
         }
        $region=$session->get('region');
        $startDate=$session->get('startDate');
        $endDate=$session->get('endDate');

        if ($campagne==null) {
          return  $this->redirectToRoute('homepage');
        }

        $venteProduits=$em->getRepository('AppBundle:Produit')->venteProduit($campagne,$startDate,$endDate,$region);
        $countAndCashByWeek= $em->getRepository('AppBundle:Ligne')->countAndCashByWeek($campagne,$startDate,$endDate,$region);
        $countAndCashByMonth= $em->getRepository('AppBundle:Ligne')->countAndCashByMonth($campagne,$startDate,$endDate,$region);

        $ventePointVentes=$em->getRepository('AppBundle:PointVente')->ventePointVente($campagne,$startDate,$endDate,$region);
        $venteSuperviseur=$em->getRepository('AppBundle:User')->venteSuperviseur($campagne,$startDate,$endDate,$region);
        $colors=array("#FF6384","#36A2EB","#FFCE56","#F7464A","#FF5A5E","#46BFBD", "#5AD3D1","#FDB45C");
        $rapportInsident=$em->getRepository('AppBundle:Commende')->rapportInsident($campagne,$startDate,$endDate,$region);
        $produits=$em->getRepository('AppBundle:Produit')->findByCampagne($campagne,false);
        return $this->render('AppBundle::index.html.twig', 
          array(
            'colors'=>$colors,
            'produits'=>$produits,
            'ventePointVentes'=>$ventePointVentes,
            'venteProduits'=>$venteProduits,
            'rapportInsident'=>$rapportInsident,
            'venteSuperviseur'=>$venteSuperviseur,
            'countAndCashByMonth'=>$countAndCashByMonth,
            'countAndCashByWeek'=>$countAndCashByWeek,
          ));
    }

    public function realisationProduitAction( PointVente $pointVente)
    {
        $details=$this->realisationProduit($pointVente->getId());
       return $this->render('AppBundle::part/produit.html.twig', 
          array(
            'details'=>$details,
          )); 
    }

    public function realisationProduit($pointVente,$startDate=null, $endDate=null)
    {
       $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        if(is_null($startDate))
           $startDate=$session->get('startDate');
         if(is_null($endDate))
             $endDate=$session->get('endDate');
        $campagne=$session->get('campagne');
        $produits=$em->getRepository('AppBundle:Produit')->findByCampagne($campagne,false);
        $details=array();
        foreach ($produits as $key => $produit) {
         $details[]=$em->getRepository('AppBundle:Ligne')->detailVente($pointVente,$produit->getId(),$startDate, $endDate)[0];
        }
       return $this->makup($details); 
    }
   
public function makup($details){
  foreach ($details as $key => &$detail) {
    if (is_null($detail['stock'])||$detail['stock']<$detail['stockFinal']||$detail['stock']<$detail['variante']) {
        $detail['stock']=($detail['variante']+$detail['gratuite']);
        $detail['stockFinal']=0;
    }
    if (is_null($detail['stockFinal'])&&$detail['stock']!=null){
     $detail['stockFinal']=($detail['stock']-$detail['variante']-$detail['gratuite']);
    }
    if($detail['stockFinal']<0){
      $detail['stock']=$detail['stock']-$detail['stockFinal'];
       $detail['stockFinal']=0;
    }
       
  }
  return $details;
}

    public function kpiAction()
    {   
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $region=$session->get('region');
        $startDate=$session->get('startDate','first day of this month');
        $endDate=$session->get('endDate', 'last day of this month');
        $campagne=$session->get('campagne');
        $ventes= $em->getRepository('AppBundle:PointVente')->ventes($campagne,$startDate,$endDate,$region);
        return $this->render('AppBundle::part/kpi.html.twig', 
          array(
            'ventes'=>$ventes[0],
          ));
    }


    public function setPeriodeAction(Request $request)
    {
        $periode= $request->request->get('periode');
        $dates = explode(" - ", $periode);
        $startDate=$dates[0];
        $endDate=$dates[1];
        $format = 'd/m/Y';
        $startDate= \DateTime::createFromFormat($format, $dates[0]);
        $endDate= \DateTime::createFromFormat($format, $dates[1]);
        $session = $this->getRequest()->getSession();
        $session->set('startDate',$startDate->format('Y-m-d'));
        $session->set('endDate',$endDate->format('Y-m-d'));
        $session->set('periode',$periode);
        $session->set('end_date_formated',$endDate->format('d/m/Y'));
        $session->set('start_date_formated',$startDate->format('d/m/Y'));
       $referer = $this->getRequest()->headers->get('referer');   
         return new RedirectResponse($referer);
    }


    public function setRegionAction(Request $request)
    {
        $region=$request->query->get('region');
         $session = $this->getRequest()->getSession();
        $session->set('region',$region);
       $referer = $this->getRequest()->headers->get('referer');   
         return new RedirectResponse($referer);
    }



    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"user"})
     */
    public function postAuthTokensAction(Request $request)
    {
        $credentials = new Credentials();
        $form = $this->createForm( CredentialsType::class, $credentials);
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $form;
        }
         $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->findOneByUsername($credentials->getLogin());

        if (!$user) { // L'utilisateur n'existe pas
            return $this->invalidCredentials();
        }
        $authToken=AuthToken::create($user);
        $em->persist($authToken);
        $em->flush();
        return $authToken->getUser();
    }


    
    public function venteExcelAction()
    {
      $em = $this->getDoctrine()->getManager();
      $session = $this->getRequest()->getSession();
       $region=$session->get('region');
       $campagne=$session->get('campagne');
        if ($campagne==null) {
            return  $this->redirectToRoute('homepage');
           }
      $startDate=$session->get('startDate','first day of this month');
      $endDate=$session->get('endDate', 'last day of this month');
      $periode= $session->get('periode',' 01/01 - 31/12/'.date('Y'));
      $days=$this->getWorkingDays($startDate, $endDate);
        // ask the service for a Excel5
       $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
       $phpExcelObject->getProperties()->setCreator("LPM C")
           ->setLastModifiedBy("LPM C")
           ->setTitle("REALISATIONS  ".$periode.' '.$region)
           ->setSubject("REALISATIONS  de ".$periode.' '.$region)
           ->setDescription("REALISATIONS ".$periode.' '.$region)
           ->setKeywords("REALISATIONS".$periode)
           ->setCategory("REALISATIONS GUINNESS");
          $phpExcelObject->setActiveSheetIndex(0)
               ->setCellValue('A1', 'N')
               ->setCellValue('B1', 'VILLE')
               ->setCellValue('C1', 'WEEK')
               ->setCellValue('D1', 'DAY')
               ->setCellValue('E1', 'DATE')
               ->setCellValue('F1', 'DEPOT')
               ->setCellValue('G1', 'SUPERVISEUR')
               ->setCellValue('H1', 'PDV')
               ->setCellValue('I1', 'BA')
               ->setCellValue('J1', 'TGT');
              $produits=$em->getRepository('AppBundle:Produit')->findByCampagne($campagne,false);
               $columm=10;
              foreach ($produits as $shiet => $produit) { 
                $phpExcelObject->getActiveSheet()
                ->setCellValueByColumnAndRow($columm+$shiet,1,  'SI_'.$produit->getShortNom())
                ->setCellValueByColumnAndRow($columm+$shiet+1,1,'SF_'.$produit->getShortNom())
                ->setCellValueByColumnAndRow($columm+$shiet+2,1,'SALE_'.$produit->getShortNom())
                ->setCellValueByColumnAndRow($columm+$shiet+3,1,'GRATUIT_'.$produit->getShortNom());
                $columm+=4; 
            }
             $columm=10;
              $key=0; 
 
        foreach ($days as $shiet => $day) {
          $ventePointVentes=$em->getRepository('AppBundle:PointVente')->venteParPointVente($campagne,$day,$day,$region,false);
                if(empty($ventePointVentes))  
                    continue;
             foreach ($ventePointVentes as  $value) {
                // $startDate= \DateTime::createFromFormat('Y-m-d', $value['createdAt']);
               $phpExcelObject->getActiveSheet()//->setActiveSheetIndex($shiet)
               ->setCellValue('A'.($key+2), '')
               ->setCellValue('B'.($key+2), strtoupper($value['ville']))
               ->setCellValue('C'.($key+2), $value['week'])
               ->setCellValue('D'.($key+2), $value['date'])
               ->setCellValue('E'.($key+2), $value['date'])
               ->setCellValue('F'.($key+2), strtoupper($value['secteur']))
               ->setCellValue('G'.($key+2), strtoupper($value['sup']))
               ->setCellValue('H'.($key+2), strtoupper($value['nom']))
               ->setCellValue('I'.($key+2), strtoupper($value['ba1'].' '.$value['ba2']!=null?$value['ba2']:''))
               ->setCellValue('J'.($key+2), 192*$value['nombrejours']);  
               
               $details=$this->realisationProduit($value['id']);
             foreach ($details as $keyDetail=> $detail) {
               $phpExcelObject->getActiveSheet()//->setActiveSheetIndex($shiet)
                ->setCellValueByColumnAndRow($columm+$keyDetail,  ($key+2), $detail['stock'])
                ->setCellValueByColumnAndRow($columm+$keyDetail+1,($key+2), $detail['stockFinal'])
                ->setCellValueByColumnAndRow($columm+$keyDetail+2,($key+2), $detail['variante'])
                ->setCellValueByColumnAndRow($columm+$keyDetail+3,($key+2), $detail['gratuite']);
                $columm+=4;           
             };
             $columm=10;
           $key++;                           
           };
        
       // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        // create the write
        }  
        $phpExcelObject->getActiveSheet()->setTitle('DATABASE');           
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $startDate=new \DateTime($startDate);
        $endDate= new \DateTime($endDate);
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'Perf. '.$startDate->format('d M Y').' au '.$endDate->format('d M Y').'.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);
        return $response;        
    }



    public function pointagesPeriodeExcelAction()
    {
      $styleGreen = array(
       'fill'  => array(
         'type'  => 'solid',
         'color' => array('rgb' => '088E1C'),
       ));
      $styleRed = array(
       'fill'  => array(
         'type'  => 'solid',
         'color' => array('rgb' => 'F53B12'),
       ));
      $em = $this->getDoctrine()->getManager();
      $session = $this->getRequest()->getSession();
      $region=$session->get('region','Douala');
        $startDate=$session->get('startDate','first day of this month');
        $endDate=$session->get('endDate', 'last day of this month');
      $periode= $session->get('periode',' 01/01 - 31/12/'.date('Y'));
      $days=$this->getWorkingDays($startDate, $endDate);
      $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
      $phpExcelObject->getProperties()->setCreator("LPM C")
           ->setLastModifiedBy("LPM C")
           ->setTitle("POINTAGEs  ".$periode)
           ->setSubject("POINTAGEs  de ".$periode)
           ->setDescription("POINTAGEs ".$periode)
           ->setKeywords("POINTAGEs".$periode)
           ->setCategory("POINTAGEs DBS");
            $workedDays=$em->getRepository('AppBundle:Commende')->workedDays($startDate,$endDate,true);
           // $phpExcelObject->createSheet(0);
            $phpExcelObject->setActiveSheetIndex(0)
               ->setCellValue('A1', 'SUPERVISEURS')
               ->setCellValue('B1', 'NOM & PRENOM')
               ->setCellValue('C1', 'NUMERO PERSONNEL')
               ->setCellValue('D1', 'TOTAL VENTE')
               ->setCellValue('E1', 'TOTAL JOURS');
                foreach ($days as $key => $day) {
                   $date=new \DateTime($day);
                   $column= $phpExcelObject->getActiveSheet()
                     ->getCellByColumnAndRow($key+5,1)
                     ->setValue($date->format('d M'))
                     ->getColumn();  
                 $phpExcelObject->getActiveSheet()->getStyle($column.'1')->getAlignment()->setTextRotation(90);
                }
             foreach ($workedDays as $key => $value){
               $phpExcelObject->getActiveSheet()
               ->setCellValue('A'.($key+2), $value['superviseur'])
               ->setCellValue('B'.($key+2), $value['nom']) 
               ->setCellValue('C'.($key+2), $value['telephone']) 
               ->setCellValue('D'.($key+2), $value['nombre'])
               ->setCellValue('E'.($key+2), $value['nombrejours']);
                  foreach ($days as $shiet => $day) {
                    $isThere=$em->getRepository('AppBundle:Commende')->isThere($value['id'],$day);
                  $cell= $phpExcelObject->getActiveSheet()
                     ->getCellByColumnAndRow($shiet+5,($key+2))->setValue($isThere)->getStyle();
                     if($isThere>0)
                        $cell->applyFromArray($styleGreen);
                      else
                         $cell->applyFromArray($styleRed);
                 }            
           };
        $phpExcelObject->getActiveSheet()->setTitle('POINTAGES FS');   
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $startDate=new \DateTime($startDate);
        $endDate= new \DateTime($endDate);
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'Pointages. '.$startDate->format('d M Y').' au '.$endDate->format('d M Y').'.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);
        return $response;        
    }



public function getWorkingDays($startDate, $endDate)
{
    $date1 = new \DateTime($startDate);
    $date2 = new \DateTime($endDate);
    if ($date1 >= $date2) {
        return [];
    } else {
        $no_days  = [];
        while ($date1 <= $date2) {
           // $what_day = date("N", $begin);
           // if (!in_array($what_day, [6,7]) ) // 6 and 7 are weekend
             $no_days[]=$date1->format('Y-m-d');
             $date1->modify('+1 day');
        };

        return $no_days;
    }
}

   /*load secteurs from excel*/
  public function loadrhAction()
    {
     $manager = $this->getDoctrine()->getManager();
    $path = $this->get('kernel')->getRootDir(). "/../web/import/rhs.xlsx";
     $objPHPExcel = $this->get('phpexcel')->createPHPExcelObject($path);
    $rhs= $objPHPExcel->getSheet(1);
    $highestRow  = $rhs->getHighestRow(); // e.g. 10
    for ($row = 5; $row <= $highestRow; ++ $row) {
            $secteur = $rhs->getCellByColumnAndRow(0, $row)->getValue();
            $nomsecteur = $rhs->getCellByColumnAndRow(1, $row)->getValue();
             $nom = $rhs->getCellByColumnAndRow(2, $row)->getValue();
            $telephone = $rhs->getCellByColumnAndRow(4, $row)->getValue();
            $username= $rhs->getCellByColumnAndRow(5, $row)->getValue();
             $user = $manager->getRepository('AppBundle:User')->findOneByUsername($username);
             if ($nom==null || $nom=='') 
                     continue;
            $pointVente=new PointVente();
            $pointVente
            ->setSecteur( $secteur)
            ->setNomSecteur( $nomsecteur)
            ->setNom($nom)
            ->setTelephone($telephone)
            ->setUser($user);
            $manager->persist($pointVente);
    }
     $manager->flush();
    return $this->redirectToRoute('homepage');      
    }

}