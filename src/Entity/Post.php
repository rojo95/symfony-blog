<?php

namespace App\Entity;

use App\Repository\PostRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[UniqueEntity('title')]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 40, unique: true)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creation_date = null;

    #[ORM\Column]
    private ?int $watched = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column]
    private ?int $user_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $file = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $update_date = null;

    #[ORM\Column]
    private ?bool $status = null;

    #[ORM\Column]
    private ?int $type = null;

    #[ORM\Column]
    private ?int $likes = null;

    #[ORM\Column]
    private ?int $dislikes = null;

    public function __construct(
        $title = null,
        $description = null,
        $watched = 0,
        $url = null,
        $user_id = null,
        $file = null,
        $update_date = null,
        $status = true,
        $type = null,
        $likes = 0,
        $dislikes = 0
    )
    {
        $this->title         = $title;
        $this->description   = $description;
        $this->creation_date = new DateTime();
        $this->watched       = $watched;
        $this->url           = $url;
        $this->user_id       = $user_id;
        $this->file          = $file;
        $this->update_date   = $update_date;
        $this->status        = $status;
        $this->type          = $type;
        $this->likes         = $likes;
        $this->dislikes      = $dislikes;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): self
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function getWatched(): ?int
    {
        return $this->watched;
    }

    public function setWatched(int $watched): self
    {
        $this->watched = $watched;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
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
    
    public function getFile(): ?string
    {
        return $this->file;
    }
    
    public function setFile(?string $file): self
    {
        $this->file = $file;
        
        return $this;
    }

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->update_date;
    }

    public function setUpdateDate(?\DateTimeInterface $update_date): self
    {
        $this->update_date = $update_date;

        return $this;
    }
    
    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    
    public function getType(): ?int
    {
        return $this->type;
    }
    
    public function setType(int $type): self
    {
        $this->type = $type;
        
        return $this;
    }

    public function getLikes(): ?int
    {
        return $this->likes;
    }

    public function setLikes(int $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    public function getDislikes(): ?int
    {
        return $this->dislikes;
    }

    public function setDislikes(int $dislikes): self
    {
        $this->dislikes = $dislikes;

        return $this;
    }

}
