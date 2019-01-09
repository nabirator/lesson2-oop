<?php

namespace Gymkhana\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Gymkhana\Service\Renderer;

/**
 * Class GymkhanaController
 * @package Gymkhana\Controller
 */
class GymkhanaController
{
    /**
     * @var Renderer
     */
    private $render;

    /**
     * GymkhanaController constructor.
     */
    public function __construct()
    {
        if (!$this->render) {
            $this->render = new Renderer();
        }
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function default(Request $request): Response
    {
        $markup = $this->render->basePage(
            $request,
            'Table with initial data',
            'showTable'
        );
        return new Response($markup);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function start(Request $request): Response
    {
        $markup = $this->render->basePage(
            $request,
            'Table sorted by class',
            'showGroupedByClassTable'
        );
        return new Response($markup);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function finish(Request $request): Response
    {
        $markup = $this->render->basePage(
            $request,
            'Table with time and penalty grouped by class and sorted by result',
            'showTimePenaltyTable'
        );
        return new Response($markup);
    }
}
