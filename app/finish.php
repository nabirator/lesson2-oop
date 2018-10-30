<?php

namespace App;
require '../data/database.php';

class finish {
  public function showTimePenaltyTable($database): string {
    foreach ($database as &$row) {
      srand(mktime(0, 0, 0));
      $time = rand( 0, time() );
      $row['time'] = date("H:i", $time);
    }

    $markup = '<table><tr><th>#</th><th>Класс</th><th>Участник</th><th>Транспорт</th><th>Время</th><th>Штраф</th></tr>';
    foreach ($database as $row) {
      $markup .= '<tr>';
      // ID
      $markup .= '<td>' . $row['id'] . '</td>';
      // CLASS
      $markup .= '<td>' . $row['class'] . '</td>';
      // USER
      $markup .= '<td>' . $row['name'] . '</td>';
      // TRANSPORT
      $markup .= '<td>' . $row['vehicle'] . '</td>';
      // TIME
      $markup .= '<td>' . $row['time'] . '</td>';
      // PENALTY
      $markup .= '<td>' . $row['penalty'] . '</td>';
      $markup .= '</tr>';
    }

    $markup .= '</table></body></html>';
    return $markup;
  }
}

echo '<html><body>';
$x = new finish();
echo '<h2>Table with time and penalty</h2>';
echo $x->showTimePenaltyTable($database);
echo '</html></body>';