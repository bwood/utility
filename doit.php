<?php

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