//<?php
$query = new EntityFieldQuery();

$query->entityCondition('entity_type', 'node')
  ->fieldCondition('body', 'value', 'base64', 'CONTAINS');
  //->range(0, 1);

$result = $query->execute();
//var_dump($result);
foreach (array_keys($result['node']) as $nid) {
  print "http://" . $_SERVER['HTTP_HOST'] . "/node/$nid\n";
}