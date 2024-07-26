<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/users")
 */
class UserController extends AbstractController
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    /**
     * @Route("/", name="app_user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                 /* código para codificar clave     */
                 $plainpwd = $user->getPassword();
                 $encoded = $this->passwordEncoder->encodePassword($user, $plainpwd);
                 $user->setPassword($encoded);
            $userRepository->add($user);
            $this->addFlash("success", 'Insertado satisfactoriamente!!! ');
            $form = $this->createForm(UserType::class, $user);
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
     /* código para codificar clave     */
     $plainpwd = $user->getPassword();
     $encoded = $this->passwordEncoder->encodePassword($user, $plainpwd);
     $user->setPassword($encoded);

            $userRepository->add($user);
            $this->addFlash('success', 'Datos actualizados satisfactoriamente !!!');
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/{id}/edit/perfil", name="app_user_edit1", methods={"GET", "POST"})
     */
    public function edit1(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user, ['isEdit'=> true,]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
     /* código para codificar clave     */
     $plainpwd = $user->getPassword();
     $encoded = $this->passwordEncoder->encodePassword($user, $plainpwd);
     $user->setPassword($encoded);

            $userRepository->add($user);
            $this->addFlash('success', 'Datos actualizados satisfactoriamente !!!');
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit1.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="app_user_delete", methods={"POST","GET"})
     */
    public function delete(Request $request, User $user, UserRepository $userRepository,int $id): Response
    {
        if (!$this->esMiembro($user)){// && $this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user);
            $this->addFlash('success', 'Datos eliminados satisfactoriamente !!!');
        }
        else{
            if($this->esMiembro($user)){
                $userRepository->deleteUser($id);
                //$this->addFlash('error', 'Es miembro de un curso,revise !!!');
                $this->addFlash('success', 'Usuario eliminado satisfactoriamente !!!');
            }
       

        }
      

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    public function esMiembro(User $user){
        $esmiembro=false;
        $em=$this->getDoctrine()->getManager();
        $cursos=$em->getRepository('App:Course')->findAll();

        foreach($cursos as $c){

        if($c->getMembers()->contains($user)){
            $esmiembro=true;
            return $esmiembro;
            $this->addFlash('error', 'Es miembro de un curso,revise !!!');
        }
        }
        return $esmiembro;

    }

}
