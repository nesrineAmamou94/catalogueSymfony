<?php
/**
 * Created by PhpStorm.
 * User: nesrine
 * Date: 25/03/19
 * Time: 18:06
 */

namespace App\Controller;

use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class PropertyController extends AbstractController
{
    /**
     * @param PropertyRepository $propertyRepository
     * @return Response
     */
    public function index(PropertyRepository $propertyRepository) : Response
    {
//        $repository = $this->getDoctrine()->getRepository(Property::class); // on peut aussi utiliser ça et dans ce cas pas besoin d'ajouter PropertyRepository en paramètre
        $biens = $propertyRepository->findAllVisible();

        return $this->render("property\index.html.twig", [
            'current_menu' => 'properties',
            'biens' => $biens
        ]);
    }

    /**
     * @param int $id
     * @param string $slug
     * @param PropertyRepository $propertyRepository
     * @return Response
     */
    public function showProperty($id, $slug, PropertyRepository $propertyRepository) : Response
    {
        $property = $propertyRepository->find($id);
        switch ($property):
            case null:
                return $this->redirectToRoute('home');
            default:
                if($slug !== $property->getSlugify()) {
                    return $this->redirectToRoute('property.show', ['id' => $property->getId(), 'slug' => $property->getSlugify()]);
                }

                return $this->render("property\property.html.twig", [
                    'current_menu' => 'properties',
                    'property' => $property
                ]);
        endswitch;
    }
}