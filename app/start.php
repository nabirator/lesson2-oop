<?php

use Gymkhana\Table;

require __DIR__. '/../data/database.php';
require_once __DIR__.'/../vendor/autoload.php';

if (PHP_SAPI === 'cli') {
  // In cli-mode
  $table = new Table();
  $sorted_db = $database;
  usort($sorted_db, function($a, $b) {
    return $a['class'] <=> $b['class'];
  });
  echo $table->showTable($sorted_db);
}
else {
  // Not in cli-mode // From WEB.
  echo '<html><body>';
  $table = new Table();
  echo $table->showGroupedByClassTable($database);
  echo '</html></body>';
}
