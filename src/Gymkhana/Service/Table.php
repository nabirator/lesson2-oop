<?php

namespace Gymkhana\Service;

class Table
{
    public function showTable($data): string
    {
        // Creating markup.
        /** @var array $data */
        $markup = '<table class="table table-sm table-bordered text-center"><thead class="thead-dark text-nowrap">
            <tr><th>#</th><th>Класс</th><th>Участник</th><th>Транспорт</th></tr>
            </thead><tbody>';
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

        $markup .= '</tbody></table>';
        return $markup;
    }

    public function showGroupedByClassTable($data): string
    {
        // Prepare data.
        /** @var array $data */
        usort($data, function ($a, $b) {
            return $a['class'] <=> $b['class'];
        });

        // Creating markup.
        $markup = '<table class="table table-sm table-bordered text-center"><thead class="thead-dark text-nowrap">
            <tr><th>#</th><th>Класс</th><th>Участник</th><th>Транспорт</th></tr></thead><tbody>';

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

        $markup .= '</tbody></table>';
        return $markup;
    }

    public function showTimePenaltyTable(&$data): string
    {
        // Prepare data.
        /** @var array $data */
        $default_max_time = 7200; // 2 hours.
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
                            $data[$key]['result'] = $default_max_time;
                            $data[$key]['result_title'] = '';
                        }
                        break;
                    case ($random_result < 15):
                        // 10% possibility to not count result.
                        $data[$key]['time'][$x] = 'Не засчитано';
                        $data[$key]['penalty'][$x] = '';
                        if (!isset($data[$key]['result'])) {
                            $data[$key]['result'] = $default_max_time;
                            $data[$key]['result_title'] = 'Не засчитано';
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

        // @TODO Duplication of code, refactoring required.
        // Creating markup.
        $markup = '<table class="table table-sm table-bordered text-center"><thead class="thead-dark text-nowrap">
            <tr><th>#</th><th>Класс</th><th>Участник</th>';
        $rounds = \count($data[0]['time']);
        for ($x = 1; $x <= $rounds; $x++) {
            $markup .= '<th colspan="2">Заезд ' . $x . '</th>';
        }
        $markup .= '<th>Лучший заезд</th><th>Транспорт</th>';
        $markup .= '</tr></thead><tbody>';

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

            return 0;
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
            $markup .= '<td>' . ($row['result'] !== $default_max_time ?
                date('H:i:s', $row['result']) :
                $row['result_title']) . '</td><td>' . $row['vehicle'] . '</td>';
            $markup .= '</tr>';
        }

        $markup .= '</tbody></table>';
        return $markup;
    }

    public static function showWinnersByClass($class, $data):string
    {
        // @TODO Duplication of code, refactoring required.
        // Creating markup.
        /** @var array $data */
        $markup = '<table class="table table-sm table-bordered text-center"><thead class="thead-dark text-nowrap">
            <tr><th>#</th><th>Класс</th><th>Участник</th>';
        $rounds = \count($data[0]['time']);
        for ($x = 1; $x <= $rounds; $x++) {
            $markup .= '<th colspan="2">Заезд ' . $x . '</th>';
        }
        $markup .= '<th>Лучший заезд</th><th>Транспорт</th>';
        $markup .= '</tr></thead><tbody>';

        // Group by class and sort by best result.

        usort($data, function ($a, $b) {
            if ($a['result'] === $b['result']) {
                return 0;
            }
            return ($a['result']>$b['result']) ? 1 : -1;
        });

        $place = 1;
        foreach ($data as $row) {
            if ($place <= 3
                && $row['class'] === $class) {
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
                $place++;
            }
        }

        $markup .= '</tbody></table>';
        return $markup;
    }

    public function showWinnersByGroup($data):string
    {
        $markup = '';
        foreach (array_unique(array_column($data, 'class')) as $class) {
            $markup .= '<div class="row justify-content-md-center no-gutters border border-bottom-0">'
                . "<h2>Table winners by class $class</h2>"
                . '</div>';
            $markup .= self::showWinnersByClass($class, $data);
        }
        return $markup;
    }
}
