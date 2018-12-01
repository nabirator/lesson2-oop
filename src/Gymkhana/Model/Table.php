<?php

namespace Gymkhana\Model;

use Symfony\Component\HttpFoundation\Request;

class Table
{
    public function showTable($data): string
    {
        $markup = '<table><tr><th>#</th><th>Класс</th><th>Участник</th><th>Транспорт</th></tr>';

        foreach ($data as $row) {
            $markup .= '<tr>';
            // ID
            $markup .= '<td>' . $row['id'] . '</td>';
            // CLASS
            $markup .= '<td>' . $row['class'] . '</td>';
            // USER
            $markup .= '<td>' . $row['name'] . '</td>';
            // TRANSPORT
            $markup .= '<td>' . $row['vehicle'] . '</td>';
            $markup .= '</tr>';
        }

        $markup .= '</table></body></html>';
        return $markup;
    }

    public function showTableLinks(Request $request): string
    {
        $path = $request->getPathInfo();
        $markup = '<ul>';
        // Default link
        $markup .= sprintf('<li><a %s>Default</a></li>', $path === '/' ? 'class="active"' : 'href="/"');
        // Start link
        $markup .= sprintf('<li><a %s>Start</a></li>', $path === '/start' ? 'class="active"' : 'href="/start"');
        // Finish link
        $markup .= sprintf('<li><a %s>Finish</a></li>', $path === '/finish' ?
            'class="active finish" onclick="window.location.reload();"' : ' href="/finish"');
        $markup .= '<li><button onclick="window.history.back();">Back</button></li>';
        $markup .= '</ul>';
        return $markup;
    }

    public function showGroupedByClassTable($data): string
    {
        $sorted_db = $data;
        usort($sorted_db, function ($a, $b) {
            return $a['class'] <=> $b['class'];
        });
        $markup = '<table><tr><th>#</th><th>Класс</th><th>Участник</th><th>Транспорт</th></tr>';

        foreach ($sorted_db as $row) {
            $markup .= '<tr>';
            // ID
            $markup .= '<td>' . $row['id'] . '</td>';
            // CLASS
            $markup .= '<td>' . $row['class'] . '</td>';
            // USER
            $markup .= '<td>' . $row['name'] . '</td>';
            // TRANSPORT
            $markup .= '<td>' . $row['vehicle'] . '</td>';
            $markup .= '</tr>';
        }

        $markup .= '</table></body></html>';
        return $markup;
    }

    public function showTimePenaltyTable($data): string
    {
        $data['rounds'] = random_int(1, 5);
        foreach ($data as $key => $value) {
            $time = random_int(0, time());
            if (\is_int($key)) {
                $data[$key]['time'] = date('H:i', $time);
                for ($x = 0; $x < $data['rounds']; $x++) {
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
        }

        $markup = '<table><tr><th>#</th><th>Класс</th><th>Участник</th><th>Транспорт</th>';
        for ($x = 0; $x < $data['rounds']; $x++) {
            $markup .= "<th>Время $x</th><th>Штраф $x</th>";
        }

        $markup .= '</tr>';
        foreach ($data as $row) {
            $markup .= '<tr>';
            // ID
            $markup .= '<td>' . $row['id'] . '</td>';
            // CLASS
            $markup .= '<td>' . $row['class'] . '</td>';
            // USER
            $markup .= '<td>' . $row['name'] . '</td>';
            // TRANSPORT
            $markup .= '<td>' . $row['vehicle'] . '</td>';
            for ($x = 0; $x < $data['rounds']; $x++) {
                // TIME
                $markup .= '<td>' . $row["time$x"] . '</td>';
                // PENALTY
                $markup .= '<td>' . $row["penalty$x"] . '</td>';
            }
            $markup .= '</tr>';
        }

        $markup .= '</table></body></html>';
        return $markup;
    }
}
