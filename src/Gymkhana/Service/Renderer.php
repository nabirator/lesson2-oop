<?php

namespace Gymkhana\Service;

use Symfony\Component\HttpFoundation\Request;
use Gymkhana\Model\Database;
use Gymkhana\Model\Table;

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
        $markup .= $table->showTableLinks($request);
        $markup .= "<h2>Table $header</h2>";
        $markup .= $table->{$method}($data);
        $markup .= self::footerRender();
        return $markup;
    }
}
