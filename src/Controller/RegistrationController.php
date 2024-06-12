<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\AppCustomAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;


class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, 
                            UserPasswordEncoderInterface $passwordEncoder, 
                            GuardAuthenticatorHandler $guardHandler, 
                            AppCustomAuthenticator $authenticator): Response
    {
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $user = $form->getData();
                
            // Asignar el rol al usuario
            $user->setRoles(['ROLE_USER']);
            
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                    )
                );
            $entityManager = $this->getDoctrine()->getManager();
            $users = $entityManager->getRepository(User::class);
            if ($users->findBy( ['email' => $user->getEmail()]))
            {
                $this->addFlash('error', 'Email ya registrado !!!');
                return $this->render('registration/register.html.twig', [
                            'registrationForm' => $form->createView(),
                            'error' => 'Email ya registrado. Rectifique y vuelva a intentarlo !!!',
                        ]);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            
            // Authenticar al usuario después del registro
            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'error' => '',
        ]);
    }
}
