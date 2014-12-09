<?php
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

  if ($i_handle == false || $o_handle == false) {
    return false;
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
  while(!feof($i_handle)) {
    $line = fgets($i_handle);
    if ($line == "\r\n") break;
    $headers[] = $line;
  }

  /**
   * Look for the Content-Length header, and get the size
   * of the remote file.
   */
  $length = 0;
  foreach($headers as $header) {
    if (stripos($header, 'Content-Length:') === 0) {
      $length = (int)str_replace('Content-Length: ', '', $header);
      break;
    }
  }

  /**
   * Start reading in the remote file, and writing it to the
   * local file one chunk at a time.
   */
  $cnt = 0;
  while(!feof($i_handle)) {
    $buf = '';
    $buf = fread($i_handle, $chunksize);
    $bytes = fwrite($o_handle, $buf);
    if ($bytes == false) {
      return false;
    }
    $cnt += $bytes;

    /**
     * We're done reading when we've reached the conent length
     */
    if ($cnt >= $length) break;
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
$fp = fopen ($file_gz, 'w+');
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_TIMEOUT, 50);
curl_setopt($ch, CURLOPT_FILE, $fp); // write curl response to file
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
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
} catch (Exception $e) {
  print "Error unzipping.\n";
  print $e->getMessage();
}

try {
  //$tar_file->extractTo('/tmp');
  $tar_file = new PharData($file_tar);
  $tar_file->extractTo('/Users/bwood/tmp/test2');
} catch (Exception $e) {
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


for ($i=50; $i<52; $i++) {
  ($i < 10) ? $I = "0$i" : $I = $i;
  $out = NULL;
  //$replace = "-$i.";
  $replace = "-$I`";
  $cmd = preg_replace($pattern, $replace, $cmd);
  print "$cmd\n";
  if (!$test) exec($cmd, $out);
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