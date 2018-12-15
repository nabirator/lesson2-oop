<?php

namespace Gymkhana\Service;

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

        $markup .= '</table>';
        return $markup;
    }

    public function showGroupedByClassTable($data): string
    {
        usort($data, function ($a, $b) {
            return $a['class'] <=> $b['class'];
        });
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

        $markup .= '</table>';
        return $markup;
    }

    public function showTimePenaltyTable($data): string
    {
        date_default_timezone_set('UTC');
        $rounds = random_int(1, 5);
        foreach ($data as $key => $value) {
            for ($x = 1; $x <= $rounds; $x++) {
                $random_result = random_int(0, 99);
                switch (true) {
                    case ($random_result < 5):
                        // 5% possibility to empty result.
                        $data[$key]['time'][$x] = '';
                        $data[$key]['penalty'][$x] = '';
                        if (!isset($data[$key]['result'])) {
                            $data[$key]['result'] = '';
                        }
                        break;
                    case ($random_result >= 90):
                        // 10% possibility to not count result.
                        $data[$key]['time'][$x] = 'Не засчитано';
                        $data[$key]['penalty'][$x] = '';
                        if (!isset($data[$key]['result'])) {
                            $data[$key]['result'] = 'Не засчитано';
                        }
                        break;
                    default:
                        // Random time from 40 minutes to 1 hour 30 minutes.
                        $data[$key]['time'][$x] = random_int(2400, 5400);
                        $penalty_keys = ['рука', 'нога', 'лицо', 'конус'];
                        $penalty = array_rand($penalty_keys, 1);
                        $is_penalty = random_int(0, 1);
                        $penalty_points = random_int(1, 4);
                        if ($is_penalty) {
                            $result = $data[$key]['time'][$x] + $penalty_points;
                            $data[$key]['penalty'][$x] = $penalty_points . ' ' . $penalty_keys[$penalty];
                        } else {
                            $result = $data[$key]['time'][$x];
                            $data[$key]['penalty'][$x] = '';
                        }
                        if (!isset($data[$key]['result'])
                            || (\is_int($result)
                            && (!\is_int($data[$key]['result']) || $data[$key]['result'] > $result))) {
                            $data[$key]['result'] = $result;
                        }
                }
            }
        }

        $markup = '<table><tr><th>#</th><th>Класс</th><th>Участник</th>';
        $rounds = \count($data[0]['time']);
        for ($x = 1; $x <= $rounds; $x++) {
            $markup .= '<th colspan="2">Заезд ' . $x . '</th>';
        }
        $markup .= '<th>Лучший заезд</th><th>Транспорт</th>';
        $markup .= '</tr>';

        // Group by class and sort by best result.

        usort($data, function ($a, $b) {
            if ($a['class'] > $b['class']) {
                return 1;
            }

            if ($a['class'] < $b['class']) {
                return -1;
            }

            if ($a['class'] === $b['class']) {
                if ($a['result'] === $b['result']) {
                    return 0;
                }
                return ($a['result']>$b['result']) ? 1 : -1;
            }
        });

        foreach ($data as $row) {
            $markup .= '<tr>';
            // ID
            $markup .= '<td>' . $row['id'] . '</td>';
            // CLASS
            $markup .= '<td>' . $row['class'] . '</td>';
            // USER
            $markup .= '<td>' . $row['name'] . '</td>';
            for ($x = 1; $x <= $rounds; $x++) {
                // TIME
                $markup .= '<td>' . (\is_int($row['time'][$x]) ?
                    date('H:i:s', $row['time'][$x]) :
                    $row['time'][$x]) . '</td>';
                // PENALTY
                $markup .= '<td>' . $row['penalty'][$x] . '</td>';
            }
            $markup .= '<td>' . (\is_int($row['result']) ?
                date('H:i:s', $row['result']) :
                $row['result']) . '</td><td>' . $row['vehicle'] . '</td>';
            $markup .= '</tr>';
        }

        $markup .= '</table>';
        return $markup;
    }
}
