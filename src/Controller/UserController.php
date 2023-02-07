<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\RegisterUsersType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $em;
     /**
      * constructor para inicializar el entuty manager interface
      * @param $em
      */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    /**
     * funcion de registro de usuario
     * @param $req información de formulario
     */
    #[Route('/registration', name: 'user_registration')]
    public function register(Request $req, UserPasswordHasherInterface $passwordHasher): Response
    {

        $user = new User();
        $form = $this->createForm(RegisterUsersType::class, $user);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $plaintextPassword  = $form->get('password')->getData();

            $hashedPassword = $passwordHasher->hashPassword(
                $user, $plaintextPassword
            );
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_USER']);
            $this->em->persist($user);
            $this->em->flush();
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/register.html.twig', [
            'title' => 'Registro de Usuario',
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/user/{id}", name="user")
     */
    public function showUser(Request $req, $id, PaginatorInterface $paginator,): Response
    {

        $user = $this->em->getRepository(User::class)->findByUserId($id);

        $query = $this->em->getRepository(Post::class)->findByUser($user['id']);

        $posts = $paginator->paginate(
            $query, /* query NO result */
            $req->query->getInt('page', 1), /*pagina inicial*/
            5 /*limite de registros por pagina*/,
            ['label_previous'=>'atras']
        );


        
        return $this->render('user/user.html.twig', [
            'title' => 'Usuario '.$user['username'],
            'user' => $user,
            'posts' => $posts
        ]);
    }
}
