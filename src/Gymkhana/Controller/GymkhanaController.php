<?php

namespace Gymkhana\Controller;

use Gymkhana\Model\Table;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Gymkhana\Model\Database;

class GymkhanaController
{
    public function start(Request $request)
    {
        $table = new Table();
        $database = new Database();
        $data = $database->loadData();
        $markup = '<html><body>';
        $markup .= $table->showGroupedByClassTable($data);
        $markup .= '</html></body>';

        return new Response($markup);
    }

    public function finish(Request $request)
    {
        $table = new Table();
        $database = new Database();
        $data = $database->loadData();
        $rounds = random_int(1, 5);
        for ($x = 0; $x < $rounds; $x++) {
            foreach ($data as $key => $value) {
                $time = random_int(0, time());
                $data[$key]['time' . $x] = date('H:i:s', $time);
                $penalty_keys = ['рука', 'нога', 'лицо', 'конус'];
                $penalty = array_rand($penalty_keys, 1);
                $penalty_points = random_int(1, 10);
                $penalty_time = $time + $penalty_points;
                $result = date('H:i:s', $penalty_time);
                $data[$key]['penalty' . $x] = $penalty_points . ' ' . $penalty_keys[$penalty];
                if (!isset($data[$key]['result'])) {
                    $data[$key]['result'] = $result;
                }
            }
        }
        $markup = '<html><body>';
        $markup .= '<h2>Table with time and penalty</h2>';
        try {
            $markup .= $table->showTimePenaltyTable($data);
        } catch (Exception $e) {
        }
        $markup .= '</html></body>';

        return new Response($markup);
    }
}
