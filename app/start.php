<?php

namespace App;
require '../data/database.php';

class start {

  public function showTable($database) {
    $markup = '<table><tr><th>#</th><th>Класс</th><th>Участник</th><th>Транспорт</th></tr>';

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
      $markup .= '</tr>';
    }

    $markup .= '</table></body></html>';
    return $markup;
  }

  public function showGroupedByClassTable($database) {
    $sorted_db = $database;
    usort($sorted_db, function($a, $b) {
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
}

echo '<html><body>';
$x = new start();
echo '<h2>Initial table</h2>';
echo $x->showTable($database);
echo '<h2>Sorted by class table</h2>';
echo $x->showGroupedByClassTable($database);
echo '</html></body>';