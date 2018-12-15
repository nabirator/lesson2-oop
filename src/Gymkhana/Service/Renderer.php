<?php

namespace Gymkhana\Service;

use Symfony\Component\HttpFoundation\Request;
use Gymkhana\Model\Database;

class Renderer
{
    public static function headRender(): string
    {
        $markup = '<html><head>';
        $markup .= '<link rel="stylesheet" type="text/css" href="css/style.css">';
        $markup .= '</head><body>';
        return $markup;
    }

    public static function footerRender(): string
    {
        return '</body></html>';
    }

    public static function basePage(Request $request, $header, $method): string
    {
        $table = new Table();
        $database = new Database();
        $data = $database->loadData();
        $markup = self::headRender();
        $markup .= self::showMenuLinks($request);
        $markup .= "<h2>Table $header</h2>";
        $markup .= $table->{$method}($data);
        if ($method === 'showTimePenaltyTable') {
            $markup .= $table->showWinnersByGroup($data);
        }
        $markup .= self::footerRender();
        return $markup;
    }

    public static function createMenuItem($currentPath, $itemPath = '/', $title = 'title'): string
    {
        $format = '<li><a %s>' . $title . '</a></li>';
        $arguments = $currentPath === $itemPath ? 'class="active"' : 'href="' . $itemPath .'"';
        return sprintf($format, $arguments);
    }

    public static function showMenuLinks(Request $request): string
    {
        $path = $request->getPathInfo();
        $markup = '<ul class="menu">';
        // Default link
        $markup .= self::createMenuItem($path, '/', 'Default');
        // Start link
        $markup .= self::createMenuItem($path, '/start', 'Start');
        // Finish link
        $markup .= self::createMenuItem($path, '/finish', 'Finish');

        $markup .= '<li><button onclick="window.location.reload();">Refresh</button></li>';
        $markup .= '<li><button onclick="window.history.back();">Back</button></li>';
        $markup .= '</ul>';
        return $markup;
    }
}
