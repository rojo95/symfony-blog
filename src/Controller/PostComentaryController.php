<?php

namespace App\Controller;

use App\Entity\PostCommentary;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class PostComentaryController extends AbstractController
{
    private $em;
    
    /**
     * @param $em
     */
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/delete/", options={"expose"=true}, name="comentary_delete")
     */
    #[Route('/delete/', methods: ['DELETE'], name: 'comentary_delete')]
    public function delete(Request $req, UserInterface $user): Response
    {

        $id = $req->request->get('id');
        $userId = $user->getId();
        $comm = $this->em->getRepository(PostCommentary::class)->find($id);

        if($comm->getUserId() == $userId) {
            $this->em->remove($comm);
            $this->em->flush();
            return new JsonResponse(['success'=>true]);
        } else {
            return new JsonResponse(['success'=>false]);
        }


    }
}
