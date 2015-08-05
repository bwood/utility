<?php

$names = array(
  'Anna Gazdowicz',
  'Brian Wood',
  'Caroline Boyden',
  'Kathleen Lu'
);
$uids = array(213108, 304629, 248324, 267087);
$i = 1;

while ($i < 5) {

  foreach ($names as $name) {
    $user = user_load_by_name($name);
    print "Removing " . $user->uid . "\n";
    user_delete($user->uid);
  }

  foreach ($uids as $uid) {
    print "==> Timestamp: " . date("c") . "\n";
    // begin: adapted from cas.user.inc
    $options = array(
      'invoke_cas_user_presave' => TRUE,
      'admin' => TRUE,
    );

    $account = cas_user_register($uid, $options);

    // Terminate if an error occurred while registering the user.
    if (!$account) {
      print "Error saving user account.\n";
      return;
    }

    // Make the user an administrator
    $role = user_role_load_by_name("administrator");
    $edit['roles'] = array($role->rid => $role->name);
    user_save($account, $edit);

    $uri = entity_uri('user', $account);
    print t('Created a new administrator account for <a href="@url">%name</a>. No e-mail has been sent.' . "\n", array(
      '@url' => url($uri['path'], $uri['options']),
      '%name' => $account->name
    ));
    // end: adapted from cas.user.inc
  }

  $i++;
  sleep(2);
}

