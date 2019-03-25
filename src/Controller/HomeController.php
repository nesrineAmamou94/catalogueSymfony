<?php

namespace App\Controller;

use App\Repository\PropertyRepository;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class HomeController
{
    /**
     * Environment/Twig fait partie de la liste affichée par la commande debug:autowiring => elle peut être chargée automatiquement => pas besoin de déclarer HomeController dans services.yml
     * Il suffit d'étendre de AbstractController pour éviter de faire $this->twig->render(...)
     * @var Environment
     */
    private $twig;

    /**
     * HomeController constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param PropertyRepository $propertyRepository
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index(PropertyRepository $propertyRepository)
    {
        $properties = $propertyRepository->findlatest();
        return new Response($this->twig->render('pages\home.html.twig', ['properties' => $properties]));
    }
}