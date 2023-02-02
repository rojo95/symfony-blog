<?php

namespace App\Entity;

use App\Repository\UserLikePostRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserLikePostRepository::class)]
class UserLikePost
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $user_id = null;

    #[ORM\Column]
    private ?int $post_id = null;

    #[ORM\Column]
    private ?bool $like_post = null;

    public function __construct($user_id = null, $post_id = null, $like_post = null)
    {
        $this->$user_id   = $user_id;        
        $this->$post_id   = $post_id;        
        $this->$like_post = $like_post;        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getPostId(): ?int
    {
        return $this->post_id;
    }

    public function setPostId(int $post_id): self
    {
        $this->post_id = $post_id;

        return $this;
    }

    public function isLikePost(): ?bool
    {
        return $this->like_post;
    }

    public function setLikePost(bool $like_post): self
    {
        $this->like_post = $like_post;

        return $this;
    }
}
