<?php

namespace App;
require '../data/database.php';

class start {
  public function table($data) {

    // Find longest string in each column
    $columns = [];
    foreach ($data as $row_key => $row) {
      foreach ($row as $cell_key => $cell) {
        $length = strlen($cell);
        if (empty($columns[$cell_key]) || $columns[$cell_key] < $length) {
          $columns[$cell_key] = $length;
        }
      }
    }

    // Output table, padding columns
    $table = '';
    foreach ($data as $row_key => $row) {
      foreach ($row as $cell_key => $cell)
        $table .= str_pad($cell, $columns[$cell_key]) . '   ';
      $table .= PHP_EOL;
    }
    return $table;

  }

  public function tableSorted($data) {
    $sorted_db = $data;
    usort($sorted_db, function($a, $b) {
      return $a['class'] <=> $b['class'];
    });

    // Find longest string in each column
    $columns = [];
    foreach ($sorted_db as $row_key => $row) {
      foreach ($row as $cell_key => $cell) {
        $length = strlen($cell);
        if (empty($columns[$cell_key]) || $columns[$cell_key] < $length) {
          $columns[$cell_key] = $length;
        }
      }
    }

    // Output table, padding columns
    $table = '';
    foreach ($sorted_db as $row_key => $row) {
      foreach ($row as $cell_key => $cell)
        $table .= str_pad($cell, $columns[$cell_key]) . '   ';
      $table .= PHP_EOL;
    }
    return $table;

  }

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

if (PHP_SAPI === 'cli') {
  // In cli-mode
  $x = new start();
  echo $x->table($database);
  echo $x->tableSorted($database);
}
else {
  // Not in cli-mode // From WEB.
  echo '<html><body>';
  $x = new start();
  echo '<h2>Initial table</h2>';
  echo $x->showTable($database);
  echo '<h2>Sorted by class table</h2>';
  echo $x->showGroupedByClassTable($database);
  echo '</html></body>';
}
