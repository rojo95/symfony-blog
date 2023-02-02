<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\PostCommentary;
use App\Entity\User;
use App\Entity\UserLikePost;
use App\Form\CreatePostType;
use App\Form\PostCommentaryType;
use Defuse\Crypto\Crypto;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

use function PHPSTORM_META\type;
use function PHPUnit\Framework\isNan;

/**
 * controlador de los post del blog
 */

class PostController extends AbstractController
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
     * funcion para crear nuevos posts y ver los existentes
     * @param Request $req
     */
    #[Route('/', name: 'new_post')]
    public function create(Request $req, SluggerInterface $slugger, PaginatorInterface $paginator, UserInterface $user = null): Response
    {
        $post = new Post();
        $query  = $this->em->getRepository(Post::class)->getAll();
        // $posts = $this->em->getRepository(Post::class)->getAll();

        $pagination = $paginator->paginate(
            $query, /* query NO result */
            $req->query->getInt('page', 1), /*pagina inicial*/
            5 /*limite de registros por pagina*/,
            ['label_previous'=>'atras']
        );
    
        $form = $this->createForm(CreatePostType::class, $post);
        $form->handleRequest($req);
        
        if ( $form->isSubmitted() && $form->isValid() ) {

            $user = $user->getId();
            
            $file = $form->get('file')->getData();

            $url = preg_replace('([^A-Za-z0-9\s])', '', $form->get('title')->getData());
            $url = str_replace(" ", "-", strtolower($url));

            if ($file) {
                $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                // Se le crea un nombre seguro al archivo
                // $safeFilename = $slugger->slug($originalFileName);
                $safeFilename = $slugger->slug($url);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                // se mueve el archivo al directorio de almacenamiento
                try {
                    $file->move(
                        $this->getParameter('files_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new Exception('Hubo un problema con el archivo');
                }

                $post->setFile($newFilename);
            }
            
            $post->setUserId($user)->setUrl($url);
            $this->em->persist($post);
            $this->em->flush();
            return $this->redirectToRoute('new_post');
        }
    
        return $this->render('post/index.html.twig', [
            'title' => 'Post',
            'form' => $form->createView(),
            'posts' => $pagination
        ]);
    }

    /**
     * mostrar post por su id
     * @param $id
     */
    #[Route('/post/{id}', name: 'post')]
    public function show($id, UserInterface $user = null): Response
    {
        $post = $this->em->getRepository(Post::class)->findPostbyId($id);
        $commentaries = $this->em->getRepository(PostCommentary::class)->messagesByPost($id);
        $postCommentary = new PostCommentary();
        $form = $this->createForm(PostCommentaryType::class, $postCommentary);

        if ( $form->isSubmitted() && $form->isValid() ) {
            dd($postCommentary);
            $postCommentary->setUserId($user->getId())
                ->setPostId($id);
            $this->em->persist($postCommentary);
            $this->em->flush();
            return $this->redirectToRoute('post');;
        }

        if($post['status'] == false) {
            if ($user == null || $user->getId() != $post['user_id']) {
                return $this->redirectToRoute('new_post');
            }
        }

        $infoArray = [
            'title' => $post['title'],
            'commentaries' => $commentaries,
            'post' => $post,
            'form' => $form->createView(),
        ];

        if ($user) {
            $liked  = $this->em->getRepository(UserLikePost::class)->findUserLikedPost(['post'=>$id,'user'=>$user->getId()]);
            $likedArr = ['liked' => $liked];
            $infoArray+= $likedArr;
            return $this->render('post/post.html.twig', $infoArray);
        } else {
            return $this->render('post/post.html.twig', $infoArray);   
        }

    }

    /**
     * funcion para marcar la vista del post
     */
    /**
     * @Route("/post/watch/", name="watched")
     */
    #[Route('/post/watch', methods: ['POST'], name: 'watched')]
    public function watched(Request $req, UserInterface $user = null): Response
    {
        $postId = $req->request->get('id');
        $post = $this->em->getRepository(Post::class)->find($postId);
        $userId = $user ? $user->getId() : null;
        
        if ($userId != $post->getUserId()) {
            $watched = intval($post->getWatched());
            $post->setWatched($watched+1);
            $this->em->persist($post);
            $this->em->flush();
        }

        return $this->redirectToRoute('post', ['id'=>$postId]);
    }


    #[Route('/update/post', name: 'update_post')]
    public function update()
    {
        $id = 2;
        $post = $this->em->getRepository(Post::class)->find($id);
        $post->setTitle('Titulo actualizado')
            ->setDescription('nueva descripcion')
            ->setUrl('titulo-actualizado')
            ->setUpdateDate(new \DateTime());
        $this->em->persist($post);
        $this->em->flush();

        return new JsonResponse(['success'=>true]);

    }

    /**
     * funcion para deshabilitar post
     * la misma valida que el usuario este logueado y 
     * que sea propietario del post
     */
    // #[Route('/remove/post/{id}', methods: ['DELETE'], name: 'remove_post')]
    #[Route('/remove/post/{id}', name: 'remove_post')]
    public function remove(Post $post, UserInterface $user): Response
    {
        $user = $user->getId();
        if ($user != $post->getUserId()) {
            return new JsonResponse([
                'success'=>false,
                'access' =>'denied'
            ]);
        } else {
            if ($post) {
                $post->setStatus(!$post->isStatus());
                $this->em->persist($post);
                $this->em->flush();
                return $this->redirectToRoute('post',['id'=>$post->getId()]);
            } else {
                return new JsonResponse(['success'=>false]);
            }
        }

    }

    /**
     * Funcion para regisrar likes o dislikes a los post
     * verificando que el usuario no pueda dar dos veces like a un post
     */
    /**
     * @Route("/like/post", options={"expose"=true}, name="like")
     */
    #[Route('/like/post', methods: ['POST'], name: 'like')]
    public function LikePost(Request $req, UserInterface $user)
    {
        // verificar si la jamada viene por ajax
        if ($req->isXmlHttpRequest()) {
            $userId = intval($user->getId());
            $id = intval($req->request->get('id'));
            $type = boolval($req->request->get('type'));
            $post = $this->em->getRepository(Post::class)->find($id);
            $postLike = $this->em->getRepository(UserLikePost::class)->findOneBy(['user_id'=>$userId, 'post_id'=>$post->getId()]);
            $postLiked = $postLike == null ? 'null' : ($postLike->isLikePost() ? 'true' : 'false');
            
            
            $likes = intval($post->getLikes());
            $dislikes = intval($post->getDislikes());

            if($type) {
                // Si es un like

                if ($postLiked == 'true') {
                    /**
                     * si el like ya existe, se elimina el registro de usuario por like por post
                     * y se resta el like del post
                     */
                    $likes = $likes-1;
                    $this->em->remove($postLike);
                    $post->setLikes($likes);
                    
                } elseif ($postLiked == 'false') {
                    /**
                     * en caso de tener un dislike se suma un like y se resta un dislike al post
                     * a su vez se modifica el registro de usuario por like por post 
                     * para que se registre el like
                     */

                    $likes = $likes+1;
                    $dislikes = $dislikes-1;
                    $postLike->setLikePost($type);
                    $post->setLikes($likes)
                    ->setDislikes($dislikes);
                    $this->em->persist($postLike);
                    $this->em->persist($post);
                    
                } else {
                    /**
                     * en caso de no existir, se creara un registro de persona por like por post con valor verdadero para 
                     * poder identificarlo como like y se agrega un like al post
                     *  */ 

                    $likes = $likes+1;
                    $postLike = new UserLikePost();
                    $postLike->setUserId($userId);
                    $postLike->setPostId($id);
                    $postLike->setLikePost($type);
                    $this->em->persist($postLike);
                    $post->setLikes($likes);
                    $this->em->persist($post);

                }
                $this->em->flush();
            } else {
                // si es un dislike

                if ($postLiked == 'false') {
                    /**
                     * si ya existe el dislike elimina el registro de usuario por like por post y quita un dislike del post
                     *  */                    
                    $dislikes = $dislikes-1;
                    $this->em->remove($postLike);
                    $post->setDislikes($dislikes);
                    
                } elseif ($postLiked == 'true') {
                    /**
                     * si tiene un like debe restarse un like y sumarse un dislike
                     * a su vez se modifica el registro de usuario por like por post 
                     * para que se registre el dislike
                     *  */

                    $likes = $likes-1;
                    $dislikes = $dislikes+1;
                    $postLike->setLikePost($type);
                    $post->setLikes($likes)
                        ->setDislikes($dislikes);
                    $this->em->persist($postLike);
                    $this->em->persist($post);
                    
                } else {
                    /**
                     * en caso de no existir, se creara un registro de persona por like por post con valor falso para 
                     * poder identificarlo como dislike y se agrega un dislike al post
                     *  */ 
                    $dislikes = $dislikes+1;
                    $postLike = new UserLikePost();
                    $postLike->setUserId($userId);
                    $postLike->setPostId($id);
                    $postLike->setLikePost($type);
                    $this->em->persist($postLike);
                    $post->setDislikes($dislikes);
                    $this->em->persist($post);

                }

                $this->em->flush();
                
            }

            /**
             * se reornan los valores necesarios para el ajax
             */
            return new JsonResponse([
                'success'  => true,
                'type' => $type,
                'likes'    => $likes,
                'dislikes' => $dislikes
            ]);
        } else {
            return new JsonResponse(['success'=>false]);
        }

    }


}
