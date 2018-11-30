<?php

namespace Gymkhana\Model;

class Table
{
    public function showTable($data): string
    {
        // Find longest string in each column
        $columns = [];
        foreach ($data as $row_key => $row) {
            foreach ($row as $cell_key => $cell) {
                $length = \strlen($cell);
                if (empty($columns[$cell_key]) || $columns[$cell_key] < $length) {
                    $columns[$cell_key] = $length;
                }
            }
        }

        // Output table, padding columns
        $table = '';
        foreach ($data as $row_key => $row) {
            foreach ($row as $cell_key => $cell) {
                $table .= str_pad($cell, $columns[$cell_key]) . '   ';
            }
            $table .= PHP_EOL;
        }
        return $table;
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

    /**
     * @param array $data
     *
     * @return string
     * @throws \Exception
     */
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
