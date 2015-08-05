<?php

 /*

 //add
// force CAS auth at the "blocked" path
$security_pages = ucberkeley_security_feeds_pages();
$cas_pages = variable_get('cas_pages', '');
if (!empty($cas_pages)) {
  $cas_pages = $cas_pages . "\n" . implode("\n", $security_pages);
}
else {
  $cas_pages = implode("\n", $security_pages);
}
variable_set('cas_pages', $cas_pages);
*/

// /*
//remove
$security_pages = ucberkeley_security_feeds_pages();
$cas_pages = explode("\n", variable_get('cas_pages', ''));
$non_security_pages = array_diff($cas_pages, $security_pages);
variable_set('cas_pages', implode("\n", $non_security_pages));
// */

function ucberkeley_security_feeds_pages() {
  return array('blocked');
}
