<?php

namespace phcode\model;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use JsonSerializable;

#[Entity]
#[Table(name: "courses")]
class Course implements JsonSerializable
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    private ?int $id;
    
    #[Column]
    private ?string $name;
    
    #[Column]
    private ?string $description;
    
    #[ManyToOne(targetEntity: User::class, inversedBy: "courses")]
    public $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function jsonSerialize(): mixed
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description
        ];
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getUserId(): int
    {
        return $this->user->getId();
    }
}
