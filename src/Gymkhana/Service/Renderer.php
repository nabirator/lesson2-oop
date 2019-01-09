<?php

namespace Gymkhana\Service;

use Symfony\Component\HttpFoundation\Request;
use Gymkhana\Model\Database;
use Twig_Environment;
use Twig_Loader_Filesystem;

/**
 * Class Renderer
 * @package Gymkhana\Service
 */
class Renderer
{
    /**
     * 2 hours.
     */
    private const MAX_TIME = 7200;

    /**
     * @param Request $request
     * @param $header
     * @param $method
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function basePage(Request $request, $header, $method): string
    {
        // Initialize Twig
        $loader = new Twig_Loader_Filesystem('../templates');
        $twig = new Twig_Environment($loader, array(
            'cache' => '../twig_cache',
            'auto_reload' => true
        ));

        // Initialize variables
        $links = [
            'Default' => '/',
            'Start' => '/start',
            'Finish' => '/finish'
        ];
        $path = $request->getPathInfo();
        $options = [];
        $options['max_time'] = self::MAX_TIME;
        $options['title'] = $header;

        $table = new Table();
        $database = new Database();
        $data = $database->loadData();
        $markup = $twig->render('header.html.twig');
        $markup .= $twig->render('menu.html.twig', [
            'links' => $links,
            'current_path' => $path
        ]);
        $markup .= $table->{$method}($data, $twig, $options);
        if ($method === 'showTimePenaltyTable') {
            $markup .= $table->showWinnersByGroup($data, $twig, $options);
        }
        $markup .= $twig->render('footer.html.twig');
        return $markup;
    }
}
