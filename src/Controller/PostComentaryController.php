<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostComentaryController extends AbstractController
{
    #[Route('/post/comentary', methods: ['DELETE'], name: 'comentary_delete')]
    public function index(): Response
    {
        return new JsonResponse(['success'=>true]);

    }
}
