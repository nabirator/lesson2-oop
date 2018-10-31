<?php
namespace Gymkhana;

class Table {
  public function showTable($database): string {
    // Find longest string in each column
    $columns = [];
    foreach ($database as $row_key => $row) {
      foreach ($row as $cell_key => $cell) {
        $length = \strlen($cell);
        if (empty($columns[$cell_key]) || $columns[$cell_key] < $length) {
          $columns[$cell_key] = $length;
        }
      }
    }

    // Output table, padding columns
    $table = '';
    foreach ($database as $row_key => $row) {
      foreach ($row as $cell_key => $cell) {
        $table .= str_pad($cell, $columns[$cell_key]) . '   ';
      }
      $table .= PHP_EOL;
    }
    return $table;
  }

  public function showGroupedByClassTable($database): string {
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

  /**
   * @param $database
   *
   * @return string
   * @throws \Exception
   */
  public function showTimePenaltyTable($database): string {
    foreach ($database as $key => $value) {
      $time = random_int( 0, time() );
      $database[$key]['time'] = date('H:i', $time);
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
