<?php
/**
 * Created by PhpStorm.
 * User: nesrine
 * Date: 01/04/19
 * Time: 10:59
 */

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminPropertyController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $propertyRepository;

    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * AdminPropertyController constructor.
     * @param PropertyRepository $propertyRepository
     * @param ObjectManager $em
     */
    public function __construct(PropertyRepository $propertyRepository, ObjectManager $em)
    {
        $this->propertyRepository = $propertyRepository;
        $this->em = $em;
    }

    /**
     * @return Response
     */
    public function index() : Response
    {
        $properties = $this->propertyRepository->findAll();
        return $this->render('admin/property/index.html.twig', [
            'properties' => $properties
        ]);
    }

    /**
     * @param Property $property    // pas besoin d'appeler le PropertyRepository, il suffit d'ajouter Property en paramètre
     * @param string $slug
     * @return Response
     */
    public function edit(Property $property, $slug, Request $request) : Response
    {
        switch ($property):
            case null:
                return $this->redirectToRoute('admin.property.show');
            default:
                if($slug !== $property->getSlugify()) {
                    return $this->redirectToRoute('admin.property.edit', ['id' => $property->getId(), 'slug' => $property->getSlugify()]);
                }

                $form = $this->createForm(PropertyType::class, $property);
                $form->handleRequest($request); // s'occupe de setter les nouvelles valeurs dans $property

                if ($form->isSubmitted() && $form->isValid()) {
                    $this->em->flush();
                    return $this->redirectToRoute('admin.property.show');
                }
                return $this->render("admin/property/edit.html.twig", [
                    'property' => $property,
                    'form' => $form->createView()
                ]);
        endswitch;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function create(Request $request) : Response
    {
        $property = new Property();

        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($property);
            $this->em->flush();
            return $this->redirectToRoute('admin.property.show');
        }

        return $this->render('admin/property/create.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }
}