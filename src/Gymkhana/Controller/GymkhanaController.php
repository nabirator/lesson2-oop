<?php

namespace Gymkhana\Controller;

use Gymkhana\Model\Table;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Gymkhana\Model\Database;
use Gymkhana\Service\Renderer;

class GymkhanaController
{
    public function default(Request $request): Response
    {
        $table = new Table();
        $database = new Database();
        $data = $database->loadData();
        $markup = Renderer::headRender();
        $markup .= $table->showTableLinks($request);
        $markup .= '<h2>Table with initial data</h2>';
        $markup .= $table->showTable($data);
        $markup .= Renderer::footerRender();

        return new Response($markup);
    }
    public function start(Request $request): Response
    {
        $table = new Table();
        $database = new Database();
        $data = $database->loadData();
        $markup = Renderer::headRender();
        $markup .= $table->showTableLinks($request);
        $markup .= '<h2>Table sorted by class</h2>';
        $markup .= $table->showGroupedByClassTable($data);
        $markup .= Renderer::footerRender();

        return new Response($markup);
    }

    public function finish(Request $request): Response
    {
        $table = new Table();
        $database = new Database();
        $data = $database->loadData();
//        $rounds = random_int(1, 5);
//        for ($x = 0; $x < $rounds; $x++) {
//            foreach ($data as $key => $value) {
//                $time = random_int(0, time());
//                $data[$key]['time' . $x] = date('H:i:s', $time);
//                $penalty_keys = ['рука', 'нога', 'лицо', 'конус'];
//                $penalty = array_rand($penalty_keys, 1);
//                $penalty_points = random_int(1, 10);
//                $penalty_time = $time + $penalty_points;
//                $result = date('H:i:s', $penalty_time);
//                $data[$key]['penalty' . $x] = $penalty_points . ' ' . $penalty_keys[$penalty];
//                if (!isset($data[$key]['result'])) {
//                    $data[$key]['result'] = $result;
//                }
//            }
//        }
        $markup = Renderer::headRender();
        $markup .= $table->showTableLinks($request);
        $markup .= '<h2>Table with time and penalty</h2>';
        $markup .= $table->showTimePenaltyTable($data);
        $markup .= Renderer::footerRender();

        return new Response($markup);
    }
}
