<?php

use Gymkhana\Table;

require __DIR__. '/../data/database.php';
require_once __DIR__.'/../vendor/autoload.php';

$table = new Table();
$sorted_db = $database;
usort($sorted_db, function($a, $b) {
  return $a['class'] <=> $b['class'];
});
if (PHP_SAPI === 'cli') {
  // In cli-mode
  echo $table->showTable($sorted_db);
}
else {
  // Not in cli-mode // From WEB.
  echo '<html><body>';
  echo $table->showGroupedByClassTable($sorted_db);
  echo '</html></body>';
}
