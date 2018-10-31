<?php

use Gymkhana\Table;

require __DIR__ . '/../data/database.php';
require_once __DIR__ . '/../vendor/autoload.php';

$table = new Table();
$sorted_db = $database;
foreach ($sorted_db as $key => $value) {
  $time = random_int( 0, time() );
  $sorted_db[$key]['time'] = date('H:i', $time);
  $penalty_keys = ['рука', 'нога', 'лицо', 'конус'];
  $penalty = array_rand($penalty_keys, 1);
  $sorted_db[$key]['penalty'] = random_int(1, 10) . ' ' . $penalty_keys[$penalty];
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
