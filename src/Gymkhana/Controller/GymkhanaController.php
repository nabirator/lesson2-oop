<?php

namespace Gymkhana\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Gymkhana\Service\Renderer;

class GymkhanaController
{
    public function default(Request $request): Response
    {
        $markup = Renderer::basePage($request, 'with initial data', 'showTable');
        return new Response($markup);
    }

    public function start(Request $request): Response
    {
        $markup = Renderer::basePage($request, 'sorted by class', 'showGroupedByClassTable');
        return new Response($markup);
    }

    public function finish(Request $request): Response
    {
        $markup = Renderer::basePage($request, 'with time and penalty', 'showTimePenaltyTable');
        return new Response($markup);
    }
}
