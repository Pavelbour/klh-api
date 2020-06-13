<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RecipeRepository::class)
 */
class Recipe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * 
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(max=50)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * 
     * @Assert\Type("string")
     * @Assert\Length(max=100)
     */
    private $subtitle;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(max=255)
     */
    private $list_of_ingredients;

    /**
     * @ORM\Column(type="text", nullable=true)
     * 
     * @Assert\Type("string")
     */
    private $description;

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

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(string $subtitle): self
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getListOfIngredients(): ?string
    {
        return $this->list_of_ingredients;
    }

    public function setListOfIngredients(string $list_of_ingredients): self
    {
        $this->list_of_ingredients = $list_of_ingredients;

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
}
