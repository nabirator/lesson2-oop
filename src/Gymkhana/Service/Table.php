<?php

namespace Gymkhana\Service;

class Table
{
    /**
     * @param array $data
     * @param \Twig_Environment $twig
     * @param array $options
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function showTable(array $data, \Twig_Environment $twig, array $options): string
    {
        return $twig->render('table.html.twig', [
            'data' => $data,
            'title'=> $options['title']
        ]);
    }

    /**
     * @param array $data
     * @param \Twig_Environment $twig
     * @param array $options
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function showGroupedByClassTable(array $data, \Twig_Environment $twig, array $options): string
    {
        // Sort data by class.
        usort($data, function ($a, $b) {
            return $a['class'] <=> $b['class'];
        });

        return $twig->render('table.html.twig', [
            'data' => $data,
            'title'=> $options['title']
        ]);
    }

    /**
     * @param array $data
     * @param \Twig_Environment $twig
     * @param array $options
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     */
    public function showTimePenaltyTable(array &$data, \Twig_Environment $twig, array $options): string
    {
        // Prepare data.
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
                            $data[$key]['result'] = $options['max_time'];
                            $data[$key]['result_title'] = '';
                        }
                        break;
                    case ($random_result < 15):
                        // 10% possibility to not count result.
                        $data[$key]['time'][$x] = 'Не засчитано';
                        $data[$key]['penalty'][$x] = '';
                        if (!isset($data[$key]['result'])) {
                            $data[$key]['result'] = $options['max_time'];
                            $data[$key]['result_title'] = 'Не засчитано';
                        }
                        break;
                    default:
                        // Random time from 40 minutes to 1 hour 30 minutes, magic numbers.
                        $data[$key]['time'][$x] = random_int(2400, 5400);
                        $penalty_keys = ['рука', 'нога', 'лицо', 'конус', 'финиш'];
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
                        break;
                }
            }
        }

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
                return ($a['result'] > $b['result']) ? 1 : -1;
            }
            return 0;
        });

        return $twig->render('table.html.twig', [
            'data' => $data,
            'rounds' => $rounds,
            'max_time' => $options['max_time'],
            'title' => $options['title']
        ]);
    }

    /**
     * @param $data
     * @param \Twig_Environment $twig
     * @param array $options
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function showWinnersByGroup($data, \Twig_Environment $twig, array $options): string
    {
        $markup = '';
        foreach (array_unique(array_column($data, 'class')) as $class) {
            $options['class'] = $class;
            $options['title'] = 'Table winners by class ' . $class;
            $markup .= $this->showWinnersByClass($data, $twig, $options);
        }
        return $markup;
    }

    /**
     * @param array $data
     * @param \Twig_Environment $twig
     * @param array $options
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function showWinnersByClass(array $data, \Twig_Environment $twig, array $options): string
    {
        // Group by class and sort by best result.
        usort($data, function ($a, $b) {
            if ($a['result'] === $b['result']) {
                return 0;
            }
            return ($a['result'] > $b['result']) ? 1 : -1;
        });
        $winners_data = [];
        $rounds = \count($data[0]['time']);
        $place = 1;
        foreach ($data as $row) {
            if ($place <= 3
                && $row['class'] === $options['class']) {
                $winners_data[] = $row;
                $place++;
            }
        }

        return $twig->render('table.html.twig', [
            'data' => $winners_data,
            'rounds' => $rounds,
            'max_time' => $options['max_time'],
            'title' => $options['title']
        ]);
    }
}
