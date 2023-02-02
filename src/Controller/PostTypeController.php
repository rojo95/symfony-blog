<?php

namespace App\Controller;

use App\Entity\PostType;
use App\Form\PostTypeCreateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostTypeController extends AbstractController
{

    private $em;

    /**
     * @param $em
     */

     public function __construct(EntityManagerInterface $em)
     {
        $this->em = $em;
     }
    
    #[Route('/type/post/', name: 'post_type')]
    public function index(Request $req): Response
    {
        $type = new PostType();
        $types = $this->em->getRepository(PostType::class)->findAll();
        $form = $this->createForm(PostTypeCreateType::class, $type);
        $form->handleRequest($req);

        if ( $form->isSubmitted() && $form->isValid() ) {
            $this->em->persist($type);
            $this->em->flush();
            $this->redirectToRoute('post_type');
        }

        return $this->render('post_type/index.html.twig', [
            'title' => 'Tipos de Posts',
            'form' => $form,
            'types' => $types
        ]);
    }

    #[Route('/type/delete/post/{id}', name: 'delete_post_type')]
    public function remove($id)
    {
        $types = $this->em->getRepository(PostType::class)->find($id);

        $this->em->remove($types);
        $this->em->flush();

        return new JsonResponse(['success'=>true]);
        
    }
}
