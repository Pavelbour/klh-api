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
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationList;

class RecipeController extends AbstractFOSRestController
{
    /**
    * Get the list of all the recipies.
    * 
    * @SWG\Response(
    * response=200,
    * description="Get the list of all the recipies."
    *)
    *
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
    * Returns the recipe with specified id.
    * 
    * @SWG\Response(
    *  response=200,
    *  description="Returns the recipe with specified id."  
    * )
    *
    * @SWG\Response(
    *  response=400,
    *  description="The recipe with specified id not found." 
    * )
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

    /**
     * Add a new recipe to the database.
     * 
     * @SWG\Response(
     *  response=201,
     *  description="Add a new recipe to the database."
     * )
     * 
     * @Post("api/recipes/post",
     * name="post_recipe"
     * )
     * @View(StatusCode=201)
     * @ParamConverter("recipe", converter="fos_rest.request_body")
     */
    public function postRecipe(Recipe $recipe, ConstraintViolationList $violations)
    {
        if (count($violations)) {
            $message = "The JSON sent contains invalid data. Here are the errors you need to correct: ";
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }

            throw new ResourceValidationException($message);
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($recipe);
        $em->flush();

        return $this->view(
            $recipe,
            Response::HTTP_CREATED,
            [
                "Location" => $this->generateUrl("post_recipe", ["id" => $recipe->getId(), UrlGeneratorInterface::ABSOLUTE_URL])
            ]
        );
    }

    /**
    * Modify the specified recipe.
    * 
    * @SWG\Response(
    *  response=200,
    *  description="Modify the specified recipe."
    *)
    *
    * @SWG\Response(
    *  response=400,
    *  description="The recipe with specified id not found."
    *)
    *
    * @Put("/api/recipes/update/{id}",
    * name="update_recipe",
    * requirements={"id"="\d+"}
    * )
    *
    * @ParamConverter("recipe", converter="fos_rest.request_body")
    */
    public function updateRecipe(Recipe $recipeToUpdate, Recipe $recipe, ConstraintViolationList $violations)
    {
        $em = $this->getDoctrine()->getManager();
        
        if (count($violations)) {
            $message = "The JSON sent contains invalid data. Here are the errors you need to correct: ";
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }

            throw new ResourceValidationException($message);
        }

        $recipeToUpdate->setTitle($recipe->getTitle());
        $recipeToUpdate->setListOfIngredients($recipe->getListOfIngredients());
        if ($recipe->getSubtitle()) {
            $recipeToUpdate->setSubtitle($recipe->getSubtitle());
        }
        $em->flush();
        if ($recipe->getDescription()) {
            $recipeToUpdate->setDescription($recipe->getDescription());
        }

        return $this->view(
            $recipe,
            Response::HTTP_CREATED,
            [
                "Location" => $this->generateUrl("post_recipe", ["id" => $recipe->getId(), UrlGeneratorInterface::ABSOLUTE_URL])
            ]
        );
    }

    /**
    * Delete the specified recipe.
    * 
    * @SWG\Response(
    *  response=200,
    *  description="Delete the specified recipe."
    *)
    *
    * @SWG\Response(
    *  response=400,
    *  description="The recipe with specified id not found."
    *)
    *
    * @Delete("/api/recipes/delete/{id}",
    * name="delete_recipe",
    * requirements={"id"="\d+"}
    * )
    * @View
    */
    public function deleteRecipe(Recipe $recipe)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($recipe);
        $em->flush();

        return $recipe;
    }
}