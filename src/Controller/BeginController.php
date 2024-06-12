<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BeginController extends AbstractController
{
    /**
     * @Route("/", name="app_begin")
     */
    public function index(): Response
    {

        $em = $this->getDoctrine()->getManager();

        $users=$em->getRepository('App:User')->findAll();
        //$class=$em->getRepository('App:PClass')->findAll();
        //$datatypes=$em->getRepository('App:Datatype')->findAll();
        $courses=$em->getRepository('App:Course')->findAll();
        $activities=$em->getRepository('App:NActivity')->findAll();
        if (count($courses)>0)
             foreach( $courses as $c){

                $miembros=$c->getMembers();
             }
             else {
                 $miembros=[];
             }

        

 
        return $this->render('begin/index.html.twig', [
            'controller_name' => 'BeginController',
            'users'=>$users,
            //'class'=>$class,
            //'datatypes'=>$datatypes,
            'courses'=>$courses,
            'activities'=>$activities,
            'miembros'=>$miembros,
        ]);
    }

    /**
     * @Route("/app_show_uml", name="app_show_uml")
     */
    public function showsheetuml():Response

    {
        $em = $this->getDoctrine()->getManager();
        //$class=$em->getRepository('App:PClass')->findAll();
     

        return $this->render('begin/showumlsheet.html.twig', [
            'controller_name' => 'BeginController',
            //'class'=>$class,

        ]);

    }


}