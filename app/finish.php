<?php

use Gymkhana\Table;

require __DIR__ . '/../data/database.php';
require_once __DIR__ . '/../vendor/autoload.php';

if (PHP_SAPI === 'cli') {
  // In cli-mode
  $table = new Table();
  //  echo $x->table($database);
  foreach ($database as $key => $value) {
    $time = random_int( 0, time() );
    $database[$key]['time'] = date('H:i', $time);
  }
  echo $table->showTable($database);
}
else {
  // Not in cli-mode // From WEB.
  echo '<html><body>';
  $table = new Table();
  echo '<h2>Table with time and penalty</h2>';
  try {
    echo $table->showTimePenaltyTable($database);
  } catch (Exception $e) {
  }
  echo '</html></body>';
}
