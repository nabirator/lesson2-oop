<?php

namespace Gymkhana\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Gymkhana\Service\Renderer;

class GymkhanaController
{
    /**
     * @param Request $request
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function default(Request $request): Response
    {
        $markup = Renderer::basePage(
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
        $markup = Renderer::basePage(
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
        $markup = Renderer::basePage(
            $request,
            'Table with time and penalty grouped by class and sorted by result',
            'showTimePenaltyTable'
        );
        return new Response($markup);
    }
}
