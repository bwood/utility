<?php
$usage = <<<EOT

USAGE:

php $argv[0] \

  -L                        # List steps
  -B number                 # Begin with this step number
  -E number                 # End after this step number

  -h                        # print help and exit

EOT;

$longopts = array();
$shortopts = "B:E:L";
$options = getopt($shortopts, $longopts);

if (in_array('h', array_keys($options))) {
  print $usage;
  exit(0);
}

$steps = array();
$functions = get_defined_functions();
foreach ($functions['user'] as $function) {
  if (strpos($function, 'step_') !== FALSE) {
    $steps[] = $function;
  }
}
asort($steps);

if (array_key_exists('L', $options)) {
  $list = TRUE;
}
else {
  $list = FALSE;
}

if (array_key_exists('B', $options)) {
  $begin = $options['B'];
  if ($begin > count($steps)) {
    print "Error: -B ($begin) must be less than or equal to " . count($steps) . "\n";
    exit(1);
  }
  ($begin > 0) ? $begin-- : $begin = 0;
}
else {
  $begin = 0;
}

if (array_key_exists('E', $options)) {
  //is_numeric and <= array_pop($steps)
  $end = $options['E'];
  if ($end > count($steps)) {
    print "Error: -E ($end) must be less than or equal to " . count($steps) . "\n";
    exit(1);
  }
  (($end > 0) && ($end <= count($steps))) ? $end = $end - count($steps) : $end = 0;
}
else {
  $end = NULL;
}

if (abs($end) < $begin) {
  print "Error: -E ($end) must be greater than -B ($begin)\n";
}


$steps = array_slice($steps, $begin, $end);

function step_01() {
  global $list;
  $step_title = "*** " . __FUNCTION__ . " Description of step ***\n";
  if ($list) {
    print $step_title;
    return;
  }

  // Step process code

}

function step_02() {
  global $list;
  $step_title = "*** " . __FUNCTION__ . " Description of step ***\n";
  if ($list) {
    print $step_title;
    return;
  }

  // Step process code

}

function step_03() {
  global $list;
  $step_title = "*** " . __FUNCTION__ . " Description of step ***\n";
  if ($list) {
    print $step_title;
    return;
  }

  // Step process code

}

function step_04() {
  global $list;
  $step_title = "*** " . __FUNCTION__ . " Description of step ***\n";
  if ($list) {
    print $step_title;
    return;
  }

  // Step process code

}

function step_05() {
  global $list;
  $step_title = "*** " . __FUNCTION__ . " Description of step ***\n";
  if ($list) {
    print $step_title;
    return;
  }

  // Step process code

}


foreach ($steps as $step) {
  $step();
}
