<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Exception\ResourceValidationException;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationList;

class RecipeController extends AbstractFOSRestController
{
    /**
    * @Get(path="/api/recipes",
    * name="show_recipes"
    * )
    * @View
    */
    public function recipes()
    {
        $recipes = $this->getDoctrine()
            ->getRepository(Recipe::class)
            ->findAll();

        return $recipes;
    }

    /**
    * @Get("/api/recipes/{id}",
    * name="show_recipe",
    * requirements={"id"="\d+"}
    * )
    * @View
    */
    public function recipe(Recipe $recipe)
    {
        return $recipe;
    }
}