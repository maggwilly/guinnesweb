<?php

namespace Pwm\MessagerBundle\Controller;
use Pwm\MessagerBundle\Entity\Sending;
use Pwm\MessagerBundle\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use FOS\RestBundle\View\View; 
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Event\CommandeEvent;
/**
 * Notification controller.
 *
 */
class NotificationController extends Controller
{
    /**
     * Lists all notification entities.
     *
     */
    private $registrationIds=array();
    const HEADERS=array(
    "Authorization: key=AAAAJiQu4xo:APA91bH63R7-CeJ7jEgGtb2TNVkCx0TDWAYbu32mO1_4baLtrrFidNrbNy98Qngb6G67efbuJ8BpInpJiCeoTp-p5mt2706P2hXbXqrTXOWlaJFTDHza2QVWSlwsbF27eBhD2PZVJKuu",
    "content-type: application/json"
  );
  const FCM_URL = "https://fcm.googleapis.com/fcm/send";
 

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $notifications = $em->getRepository('MessagerBundle:Notification')->findList();
        return $this->render('MessagerBundle:notification:index.html.twig', array(
            'notifications' => $notifications,
        ));
    }


    public function renderTemplate(\Pwm\AdminBundle\Entity\Commande $commande=null)
    {
        return $this->renderView(
            'MessagerBundle:notification:confirmation.html.twig',
            array(
                'commande' => $commande
            )
        );
    }
    /**
     * Creates a new notification entity.
     *
     */
    public function newAction(Request $request)
    {
        $notification = new Notification();
       $em = $this->getDoctrine()->getManager();
        $form = $this->createForm('Pwm\MessagerBundle\Form\NotificationType', $notification);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
 
            $em->persist($notification);
            $em->flush();

            return $this->redirectToRoute('notification_show', array('id' => $notification->getId()));
        }

        return $this->render('MessagerBundle:notification:new.html.twig', array(
            'notification' => $notification,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a notification entity.
     *
     */
    public function showAction(Request $request,Notification $notification)
    {
        $em = $this->getDoctrine()->getManager();
        $deleteForm = $this->createDeleteForm($notification);
        $sendForm = $this->createForm('Pwm\MessagerBundle\Form\NotificationSendType', $notification);
        $sendForm->handleRequest($request);
        if ($sendForm->isSubmitted() && $sendForm->isValid()) {
             $registrationIds='';
            $groupe= $notification->getGroupe();
            if($groupe!=null){
               switch ($groupe->getTag()) {
                   case 'is.registred.not.singup':
                     $registrations = $em->getRepository('MessagerBundle:Registration')->findNotsingup();
                         $this->sendTo($registrations ,$notification);
                    break;
                   case 'loaded.too.long.time':
                     $registrations = $em->getRepository('MessagerBundle:Registration')->findTooLongTimeLogin();
                     $this->sendTo($registrations ,$notification);
                    break;                     
                   case 'singup.subscribed.starter':
                        $destinations=$em->getRepository('AdminBundle:Info')->findSubscribersByBundle('starter');
                        foreach ($destinations as $info) {
                             $this->sendTo($info->getRegistrations(),$notification);
                     }
                    break; 
                   case 'singup.subscribed.standard':
                        $destinations=$em->getRepository('AdminBundle:Info')->findSubscribersByBundle('standard');
                        foreach ($destinations as $info) {
                          $this->sendTo($info->getRegistrations(),$notification);
                     }
                    break;                    
                   case 'singup.subscribed.expired':
                        $destinations=$em->getRepository('AdminBundle:Info')->findSubscribersExpired();
                        foreach ($destinations as $info) {
                          $this->sendTo($info->getRegistrations(),$notification);
                     }
                    break;
                                                             
                   case 'singup.not.profil.filled':
                       $destinations=$em->getRepository('AdminBundle:Info')->findNotProfilFilled();
                        foreach ($destinations as $info) {
                          $this->sendTo($info->getRegistrations(),$notification);
                     }
                    break;                                      
                   default:
                       if ($groupe->getSession()!=null) {
                           $destinations=$groupe->getSession()->getInfos();
                        foreach ($destinations as $info) {
                            $this->sendTo($info->getRegistrations(),$notification);
                          }
                       }
                       break;
               }

              }else{
                $registrations = $em->getRepository('MessagerBundle:Registration')->findAll();
                   $this->sendTo($registrations,$notification);
            }
             $em->flush();
            return $this->firebaseSend($this->registrationIds ,$notification);// $this->redirectToRoute('notification_show', array('id' => $notification->getId()));
        }
        return $this->render('MessagerBundle:notification:show.html.twig', array(
            'notification' => $notification,
            'send_form' => $sendForm->createView(),
            'delete_form' => $deleteForm->createView()
        ));
    }

    /**
     * Displays a form to edit an existing notification entity.
     *
     */
    public function sendTo($registrations,Notification $notification)
    {
    $em = $this->getDoctrine()->getManager();
   foreach ($registrations as $registration) {
     $this->registrationIds[]=$registration->getRegistrationId();
        $sending=new Sending($registration,$notification);
          $em->persist($sending);  
       }
         $em->flush();
     return  $this->registrationIds;
    }


public function firebaseSend($registrationIds,Notification $notification ){
$data=array(
        'registration_ids' => array_values($registrationIds),
        'title' => $notification->getTitre(),
        'body' => $notification->getSousTitre(),
        'badge' => 1,
        'tag' => 'confirm',
        'priority' => 'high',
        'data' => array(
               'action' => "new_message"
        )
    );

     $fmc_response= $this->get('fmc_manager')->sendMessage($data,false);
     //$fmc_response=$this->sendPostRequest(self::FCM_URL,$data,self::HEADERS,false);
  return new Response($fmc_response);
}


  public function sendPostRequest($url,$data,$headers,$json_decode=true)
    {
        $content = json_encode($data);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 120);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
        $json_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
         curl_close($curl);
       if ($err) {
            $json_err = json_decode($err, true);
            return $json_decode?$json_err:$err;
        }
        $response = json_decode($json_response, true);
        return $json_decode?$response:$json_response;
    }


    /**
     * Displays a form to edit an existing notification entity.
     *
     */
    public function editAction(Request $request, Notification $notification)
    {
        $deleteForm = $this->createDeleteForm($notification);
        $editForm = $this->createForm('Pwm\MessagerBundle\Form\NotificationType', $notification);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('notification_edit', array('id' => $notification->getId()));
        }
        return $this->render('MessagerBundle:notification:edit.html.twig', array(
            'notification' => $notification,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a notification entity.
     *
     */
    public function deleteAction(Request $request, Notification $notification)
    {
        $form = $this->createDeleteForm($notification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($notification);
            $em->flush();
        }

        return $this->redirectToRoute('notification_index');
    }

    /**
     * Creates a form to delete a notification entity.
     *
     * @param Notification $notification The notification entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Notification $notification)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('notification_delete', array('id' => $notification->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
