<?php

/**
 * Regular expressions for Terminus output strings that should be ignored.
 * TODO: Adjust per https://github.com/pantheon-systems/cli/issues/413
 *
 * @return array
 */
function terminus_output_ignore() {
  return array(
    'Warning: There is a newer version of Terminus.*$',
  );
}

function terminus_output_filter($string) {
  foreach (terminus_output_ignore() as $pattern)
    if (preg_match("/$pattern/", $string)) {
      // if there's a match, return false to filter the match from the output
      return FALSE;
    }
  return TRUE;
}

/**
 * Filter terminus output
 * Refer to https://github.com/pantheon-systems/cli/issues/413
 *
 * @param $command
 * @param $output
 * @param $return
 */
function terminus_exec($command, &$output, &$return) {
  $ignore_strings = terminus_output_ignore();
  exec($command, $output, $return);
  //preg_replace("/$ignore_strings/", "");
  $output = array_filter($output, 'terminus_output_filter');
  //$output = array_diff($output, $ignore_strings);
}

$cmd = 'terminus site info --site=websolutions-ob';
terminus_exec($cmd, $output, $return);
print "$cmd\n";
print "Return value: " . $return. "\n";
print_r($output);

exit;

$x = array('status' => 1, 'view modes' => array('page_manager' => array('status' => 1, 'default' => 1, 'choice' => 0,), 'default' => array('status' => 0, 'default' => 0, 'choice' => 0,), 'featured' => array('status' => 0, 'default' => 0, 'choice' => 0,)));

print_r($x);
exit;
// create a new cURL resource
$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, "http://berkeley.edu/");
curl_setopt($ch, CURLOPT_HEADER, 0);

// grab URL and pass it to the browser
curl_exec($ch);

// close cURL resource, and free up system resources
curl_close($ch);


exit;
$hosts = array(
  'auth.berkeley.edu' => gethostbyname('auth.berkeley.edu'),
  'auth-test.berkeley.edu' => gethostbyname('auth-test.berkeley.edu')
);

$port = 443;

foreach ($hosts as $host => $ip) {
  if ($fp = fsockopen($ip, $port, $errCode, $errStr, 2)) {
    print "$host ($ip): SUCCESS\n";
  }
  else {
    print "$host ($ip): Failure.\n";
  }
  fclose($fp);
}

exit;

$to = 'nobody@example.com';
$subject = 'the subject';
$message = 'hello';
$headers = 'From: webmaster@example.com' . "\r\n" .
  'Reply-To: webmaster@example.com' . "\r\n" .
  'X-Mailer: PHP/' . phpversion();


if (!mail($to, $subject, $message, $headers)) {
  print "can't send\n";
}
else {
  print "sent\n";
}

exit;

$url = 'https://pantheon-backups.s3.amazonaws.com/55c57672-38df-448f-aad8-5eb7df7de155/dev/1429574271_backup/orgadd-test-bw_dev_2015-04-20T23-57-51_UTC_files.tar.gz?Signature=p2qUElLdxYSh%2F4SSrBDxKHEE%2BJ4%3D&Expires=1429575032&AWSAccessKeyId=AKIAJEYKXMCPBZQYJYXQ';
$opts = array(
  'http' =>
    array(
      'method' => 'GET',
    )
);

$context = stream_context_create($opts);
$stream = fopen($url, 'r', FALSE, $context);

// header information as well as meta data
// about the stream
var_dump(stream_get_meta_data($stream));

// actual data at $url
var_dump(stream_get_contents($stream));
fclose($stream);

exit;

putenv("DRUSH_SCRIPT=drush8");
print "drush script:" . getenv("DRUSH_SCRIPT") . "\n";

exit;

function thisisfalse() {
  return FALSE;
}

print "FALSE = " . FALSE . "\n";
print "TRUE = " . TRUE . "\n";
if ($x = thisisfalse() === FALSE) {
  print "compare false\n";
  print "x=$x\n";
}
else {
  print "compare not false\n";
  print "x=$x\n";
}

exit;

function replace_strings($subject, $user_patterns = array(), $replacements = array()) {
  $patterns = array(
    "/RSA host key for IP address '[\d]{1,3}.[\d]{1,3}.[\d]{1,3}.[\d]{1,3}' not in list of known hosts\./"
  );
  $patterns = array_merge($patterns, $user_patterns);
  return preg_replace($patterns, $replacements, $subject);
}


$output = array(
  "RSA host key for IP address '23.253.170.75' not in list of known hosts.",
  'array (',
  '"this",',
  ");"
);

var_dump(replace_strings($output));

exit;

if ($result = drush_upc(0)) {
  if (is_array($result)) {
    print_r($result);
  }
  else {
    print "result=$result\n";
  }
}

function drush_upc($x) {
  if ($x == 1) {
    return TRUE;
  }
  return array('one', 'two');
}

exit;

$dates = '';
for ($i = 0; $i < 5; $i++) {
  exec("date", $output, $return);
  //print $output[0];
  $dates .= $output[0];
}
print $dates . "\n";
exit;

require 'vendor/autoload.php';


exit;

/*

    $instructions = "Enter the original site name (For \"http://dev-example.pantheon.berkeley.edu\" you'd enter \"example\")";
    $site_name_orig = take_input($instructions);
    while (yesno("You entered '" . $site_name_orig . "'. Is that correct? ", TRUE) === FALSE) {
      $site_name_orig = take_input($instructions);
    }
  }
*/
$json = '[{"label":"\'UC Berkeley - Testing\'","name":"uc-berkeley-testing","role":"team_member","id":"430c1947-354f-459a-8755-f38293aef105"}]';
$site_orgs = json_decode($json, TRUE);
if (count($site_orgs) < 1) {
  print "\nError: The target site is not affiliated with an organization.\n";
}
print_r($site_orgs);

exit;

$page = new stdClass();
$page->task = '';
$page->name = '';

exit;


check_psite_jobs('cache_clear_dev', '7a36bd93-864a-4f85-8821-36ef6f865014');
/**
 * Check for completion of a job
 *
 * @param $slot
 * @param $uuid Either the source or target site uuid
 * @param int $poll
 * @return bool
 */
function check_psite_jobs($slot, $uuid, $poll = 60) {
  global $drush;

  $psite_jobs_cmd = "drush psite-jobs $uuid --slot=$slot";
  print $psite_jobs_cmd . "\n";

  $complete = FALSE;
  $i = 0;
  while (($i < $poll) && ($complete == FALSE)) {
    exec($psite_jobs_cmd, $output, $return);
    $date_now = date_now_rfc822();
    $date_min_ago = date_min_ago($date_now);
    if ($i > 19) {
      print "==> Jobs on Pantheon might backlogged. (Check $i of $poll) <==\n";
    }
    print "\n$i: Checking for $slot between $date_min_ago - $date_now...\n";
    //foreach ($output as $out) {
    $out = implode(" ", $output);
    // also look for $date_min_ago on off chance job started at 12:59:59 and $date_now is 13:00:00
    if ((strpos($out, 'Y          SUCCESS') !== FALSE) &&
      ((strpos($out, $date_now) !== FALSE) || (strpos($out, $date_min_ago) !== FALSE))
    ) {
      print "\nMatch:\n\t$out\n\n";
      $complete = TRUE;
      return TRUE;
    }
    print "No match: $out\n";
    unset($output);
    unset($out);
    //}
    sleep(3);
    $i++;
  }

  // Stop trying after $poll attempts
  if (($i == $poll) && ($complete === FALSE)) {
    print "\nError: We never found this job: $slot\n\n";
    print wordwrap("Check this site's dashboard to see if the job has run. If it hasn't there might be a backup in the Pantheon job queue. When you see the job complete on the dashboard, return run this script again beginning with the next step.\n", 80);
    exit(1); //TODO: handle return false
  }
}

function date_now_rfc822() {
  // DATE_RFC822 without the seconds
  date_default_timezone_set('UTC');
  return date("D, d M y H:i");
}

function date_min_ago($date) {
  $parts = explode(":", $date);
  $min_ago = $parts[1] - 1;
  if ($min_ago < 10) {
    $min_ago = "0" . $min_ago;
  }
  return $parts[0] . ":" . $min_ago;
}


exit;

check_psite_jobs('update_database_dev', 0);

function check_psite_jobsZZ($slot, $uuid, $poll = 60) {
  global $drush;

  $psite_jobs_cmd = "$drush psite-jobs $uuid --slot=$slot";
  //print $psite_jobs_cmd . "\n";

  $complete = FALSE;
  $i = 0;
  while (($i < $poll) && ($complete == FALSE)) {
    //exec($psite_jobs_cmd, $output, $return);

    $output = array(
      'Slot                 Name               Env  Completed  Status  Duration  Updated',
      'update_database_dev  Run update.php in  dev  N                  13.28s    Tue, 23 Dec 14 00:22:37 +0000',
      'Slot                 Name               Env  Completed  Status   Duration  Updated',
      'update_database_dev  Run update.php in  dev  Y          SUCCESS  14.77s    Tue, 23 Dec 14 00:23:03 +0000',
    );

//    $date_now = date_now_rfc822();
//    $date_min_ago = date_min_ago($date_now);
    $date_now = 'Tue, 23 Dec 14 00:23';
    $date_min_ago = 'Tue, 23 Dec 14 00:22';
    if ($i > 19) {
      print "==> Jobs on Pantheon might backlogged. (Check $i of $poll) <==\n";
    }
    print "\n$i: Checking for $slot between $date_min_ago - $date_now...\n";
    foreach ($output as $out) {
      // also look for $date_min_ago on off chance job started at 12:59:59 and $date_now is 13:00:00
      if ((strpos($out, 'Y          SUCCESS') !== FALSE) &&
        ((strpos($out, $date_now) !== FALSE) || (strpos($out, $date_min_ago) !== FALSE))
      ) {
        print "\nMatch:\n\t$out\n\n";
        $complete = TRUE;
        return TRUE;
      }
      print "No match: $out\n";
      unset($output);
      unset($out);
    }
    sleep(3);
    $i++;
  }

  // Stop trying after $poll attempts
  if (($i == $poll) && ($complete === FALSE)) {
    print "\nError: We never found this job: $slot\n\n";
    print wordwrap("Check this site's dashboard to see if the job has run. If it hasn't there might be a backup in the Pantheon job queue. When you see the job complete on the dashboard, return run this script again beginning with the next step.\n", 80);
    exit(1); //TODO: handle return false,
  }
}

exit;


date_default_timezone_set('UTC');
// DATE_RFC822 without the seconds
$date_now = date("D, d M y H:i");
$parts = explode(":", $date_now);
$min_ago = $parts[1] - 1;
$date_min_ago = $parts[0] . ":" . $min_ago;
print "$date_now\n";
print "$date_min_ago\n";
print "$min_ago\n";
exit;

/**
 * Copy remote file over HTTP one small chunk at a time.
 *
 * @param $infile The full URL to the remote file
 * @param $outfile The path where to save the file
 */
function copyfile_chunked($infile, $outfile) {
  $chunksize = 10 * (1024 * 1024); // 10 Megs

  /**
   * parse_url breaks a part a URL into it's parts, i.e. host, path,
   * query string, etc.
   */
  $parts = parse_url($infile);
  $i_handle = fsockopen($parts['host'], 80, $errstr, $errcode, 5);
  $o_handle = fopen($outfile, 'wb');

  if ($i_handle == FALSE || $o_handle == FALSE) {
    return FALSE;
  }

  if (!empty($parts['query'])) {
    $parts['path'] .= '?' . $parts['query'];
  }

  /**
   * Send the request to the server for the file
   */
  $request = "GET {$parts['path']} HTTP/1.1\r\n";
  $request .= "Host: {$parts['host']}\r\n";
  $request .= "User-Agent: Mozilla/5.0\r\n";
  $request .= "Keep-Alive: 115\r\n";
  $request .= "Connection: keep-alive\r\n\r\n";
  fwrite($i_handle, $request);

  /**
   * Now read the headers from the remote server. We'll need
   * to get the content length.
   */
  $headers = array();
  while (!feof($i_handle)) {
    $line = fgets($i_handle);
    if ($line == "\r\n") {
      break;
    }
    $headers[] = $line;
  }

  /**
   * Look for the Content-Length header, and get the size
   * of the remote file.
   */
  $length = 0;
  foreach ($headers as $header) {
    if (stripos($header, 'Content-Length:') === 0) {
      $length = (int) str_replace('Content-Length: ', '', $header);
      break;
    }
  }

  /**
   * Start reading in the remote file, and writing it to the
   * local file one chunk at a time.
   */
  $cnt = 0;
  while (!feof($i_handle)) {
    $buf = '';
    $buf = fread($i_handle, $chunksize);
    $bytes = fwrite($o_handle, $buf);
    if ($bytes == FALSE) {
      return FALSE;
    }
    $cnt += $bytes;

    /**
     * We're done reading when we've reached the conent length
     */
    if ($cnt >= $length) {
      break;
    }
  }

  fclose($i_handle);
  fclose($o_handle);
  return $cnt;
}


$infile = $argv[1];
$outfile = $argv[2];
copyfile_chunked($infile, $outfile);
// Success


exit();

/*
 * Download and unarchive pantheon backup with exec
*/
$url = $argv[1];
$archive = $argv[2];

$file_gz = '/tmp/' . $archive;
//$file_tar = preg_replace("/\.gz$/", "", $file_gz);

set_time_limit(0);
$fp = fopen($file_gz, 'w+');
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_TIMEOUT, 50);
curl_setopt($ch, CURLOPT_FILE, $fp); // write curl response to file
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
curl_exec($ch); // get curl response
curl_close($ch);
fclose($fp);

exit;

$cd_files_mount_cmd = "cd /tmp/test";
$tar_cmd = "tar --strip-components 1 -z -x -f $file_gz";
exec("$cd_files_mount_cmd;$tar_cmd");

exit;

/*
 * Download and unarchive pantheon backup PharData
*/
$url = $argv[1];
$remote_file = $argv[2];
$file_gz = '/tmp/' . $remote_file;
$file_tar = preg_replace("/\.gz$/", "", $file_gz);
/*
set_time_limit(0);
$fp = fopen ($tmp_file, 'w+');
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_TIMEOUT, 50);
curl_setopt($ch, CURLOPT_FILE, $fp); // write curl response to file
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_exec($ch); // get curl response
curl_close($ch);
fclose($fp);
*/

// decompress from gz
try {
  $gzip_file = new PharData($file_gz);
  //$tar_file = $gzip_file->decompress(); // creates /path/to/my.tar
  $gzip_file->decompress(); //now $file_tar exists
}
catch (Exception $e) {
  print "Error unzipping.\n";
  print $e->getMessage();
}

try {
  //$tar_file->extractTo('/tmp');
  $tar_file = new PharData($file_tar);
  $tar_file->extractTo('/Users/bwood/tmp/test2');
}
catch (Exception $e) {
  print "Error untarring.\n";
  print $e->getMessage();
}

/*
 PharData buggy?  Always get
 Extraction from phar "/private/tmp/openucb-469-ebill-bw_dev_2014-12-04T18-48-06_UTC_files.tar" failed:
 Cannot extract "files_dev/ebillscreerm /tmp/openucb-469-ebill-bw_dev_2014-12-04T18-48-06_UTC_files.tar
 */

exit;
// couldn't find a php way to strip files_dev/ when extracting
if (!rename('/tmp/files_dev', '/tmp/files')) {
  print "Error: Failed copying to /tmp/files.\n";
}

exit;

// ring the terminal bell?
echo "\007";

exit;

/*
 * migrate-ob.php
 * fork a process
 */

print "\n*** Okay! Let's run pre-upgrade.sh. ***\n";

if (strpos(file_get_contents($pantheon_aliases), $site_name) === FALSE) {
  print "$site_name not found in your $pantheon_aliases. Refetching aliases...\n";
  exec("$drush paliases");
}

if (strpos(file_get_contents($pantheon_aliases), $site_name) === FALSE) {
  print "Error $site_name STILL not found in your $pantheon_aliases. Aborting.\n";
  exit(1);
}
//$pre_upgrade_cmd = "$clone_path/$site_name-premigrate/profiles/openberkeley/scripts/ob_0.1_upgrade/pre-upgrade.sh @pantheon.$site_name.dev";
$pre_upgrade_file = "$clone_path/pre-upgrade.sh";

if (file_exists($pre_upgrade_file)) {
  if (!chmod($pre_upgrade_file, 0755)) {
    print "Please make $pre_upgrade_file executable.";
    yesno("Yes when you are done. (No to abort.)");
  }
}
else {
  print "Please copy pre-upgrade.sh to $clone_path and make it executable.";
  yesno("Yes when you are done. (No to abort.)");
}
$pre_upgrade_cmd = "$pre_upgrade_file @pantheon.$site_name.dev";
print $pre_upgrade_cmd . "\n";
//exec($pre_upgrade_cmd, $output, $return);
//unset($output);
$handle = popen($pre_upgrade_cmd, "r");
echo "'$handle'; " . gettype($handle) . "\n";
$read = fread($handle, 2096);
echo $read;
pclose($handle);


exit;

$data = 'a:8:{s:4:"name";s:14:"message_notify";s:4:"info";a:6:{s:4:"name";s:14:"Message notify";s:7:"package";s:7:"Message";s:7:"version";s:7:"7.x-2.5";s:7:"project";s:14:"message_notify";s:9:"datestamp";s:10:"1366630876";s:16:"_info_file_ctime";i:1383255563;}s:9:"datestamp";s:10:"1366630876";s:8:"includes";a:1:{s:14:"message_notify";s:14:"Message notify";}s:12:"project_type";s:6:"module";s:14:"project_status";b:1;s:10:"sub_themes";a:0:{}s:11:"base_themes";a:0:{}}';
print_r(unserialize($data));

exit();

/** run terminus commands on multiple pantheon sites **/

#  $cmd = 'drush psite-backup `drush psite-uuid ucb-train-editor-101` dev';
$pattern = '/-\d+`/';
$replace = "-$i`";
$test = FALSE;
$cmd = 'drush @pantheon.ucb-train-editor-100.dev sqlq "select mail from users order by mail" > /Users/bwood/tmp/ucb-train-editor-100.emails.txt';
$cmd = 'drush @pantheon.ucb-train-editor-100.dev en panopoly_wysiwyg -y';
$pattern = '/-\d+\./';
$cmd = 'drush psite-delete `drush psite-uuid ucb-train-editor-00` -y';
$pattern = '/-\d+`/';


for ($i = 50; $i < 52; $i++) {
  ($i < 10) ? $I = "0$i" : $I = $i;
  $out = NULL;
  //$replace = "-$i.";
  $replace = "-$I`";
  $cmd = preg_replace($pattern, $replace, $cmd);
  print "$cmd\n";
  if (!$test) {
    exec($cmd, $out);
  }
  //print implode("\n", $out);
  //print "\n";
}
exit();


/** CURL **/
// create a new cURL resource
$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, "http://dev-ucb-train-editor-112.pantheon.berkeley.edu/");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

// grab URL and pass it to the browser
$page = curl_exec($ch);

// close cURL resource, and free up system resources
curl_close($ch);

print $page . "\n";
exit(0);


if ((strpos($page, "UCB Editor Training 101")) !== FALSE) {
  echo "site installed\n";
}
else {
  echo "site didn't install.\n";
}
?>