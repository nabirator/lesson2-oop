<?php

namespace Gymkhana\Service;

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
}
