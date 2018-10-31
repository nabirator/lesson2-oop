<?php

use Gymkhana\Table;

require __DIR__ . '/../data/database.php';
require_once __DIR__ . '/../vendor/autoload.php';

$table = new Table();
$sorted_db = $database;
$rounds = random_int(1, 5);
for ($x = 0; $x < $rounds; $x++) {
  foreach ($sorted_db as $key => $value) {
    $time = random_int( 0, time() );
    $sorted_db[$key]['time' . $x] = date('H:i:s', $time);
    $penalty_keys = ['рука', 'нога', 'лицо', 'конус'];
    $penalty = array_rand($penalty_keys, 1);
    $penalty_points = random_int(1, 10);
    $penalty_time = $time + $penalty_points;
    $result = date('H:i:s', $penalty_time);
    $sorted_db[$key]['penalty' . $x] = $penalty_points . ' ' . $penalty_keys[$penalty];
    if (!isset($sorted_db[$key]['result'])) {
      $sorted_db[$key]['result'] = $result;
    }
  }
}
if (PHP_SAPI === 'cli') {
  // In cli-mode
  //  echo $x->table($database);
  echo $table->showTable($sorted_db);
}
else {
  // Not in cli-mode // From WEB.
  echo '<html><body>';
  echo '<h2>Table with time and penalty</h2>';
  try {
    echo $table->showTimePenaltyTable($sorted_db);
  } catch (Exception $e) {
  }
  echo '</html></body>';
}
