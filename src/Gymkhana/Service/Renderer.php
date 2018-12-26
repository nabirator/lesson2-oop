<?php

namespace Gymkhana\Service;

use Symfony\Component\HttpFoundation\Request;
use Gymkhana\Model\Database;
use Twig_Environment;
use Twig_Loader_Filesystem;

class Renderer
{
    public static function basePage(Request $request, $header, $method): string
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

        $table = new Table();
        $database = new Database();
        $data = $database->loadData();
        $markup = $twig->render('header.html.twig');
        $markup .= $twig->render('menu.html.twig', [
            'links' => $links,
            'current_path' => $path
        ]);
        $markup .= "<h2>Table $header</h2>";
        $markup .= $table->{$method}($data);
        if ($method === 'showTimePenaltyTable') {
            $markup .= $table->showWinnersByGroup($data);
        }
        $markup .= $twig->render('footer.html.twig');
        return $markup;
    }
}
