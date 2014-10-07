#!/bin/bash

cleanup() {
  # Before proceeding we'll weed out unneeded modules, themes, libraries etc which
  # exist in the site that we are upgrading. This clean up is required if you
  # have used Pantheon Apply Updates to merge in the latest platform code
  SITE_NAME=`echo $ALIAS | cut -d '.' -f2`
  SITE_UUID=`$DRUSH $DRUSH_OPTS psite-uuid $SITE_NAME | cut -d ':' -f 2`

  # change to sftp mode
  $DRUSH $DRUSH_OPTS psite-cmode $SITE_UUID dev sftp
  echo -n "Pausing to permit Pantheon server magic..."
  sleep 20

  # clean up modules, themes, libraries on the site being upgraded
  cat rrmdir.php rrmdir-post.php | $DRUSH $DRUSH_OPTS $ALIAS scr - && (
  echo -n "..."
  sleep 20
  # commit the changes resulting from the delete
  $DRUSH $DRUSH_OPTS psite-commit $SITE_UUID dev --message="Remove profiles/panopoly and clean up modules, themes, libraries in preparation for upgrade." || (
    echo ""
    echo "This server is sadly unmagical.  Try the following:"
    $DRUSH $DRUSH_OPTS psite-dash $SITE_UUID
    exit 0 #you can't exit here
    # return 1 # you can't return here.
    # ABORT=1 # variable not visible outside of ()?
  )
)
  sleep 10
  # change to git mode
  $DRUSH $DRUSH_OPTS psite-cmode $SITE_UUID dev git
}

cleanup

exit

########## Opt2

cleanup() {
  # Before proceeding we'll weed out unneeded modules, themes, libraries etc which
  # exist in the site that we are upgrading. This clean up is required if you
  # have used Pantheon Apply Updates to merge in the latest platform code
  ERROR=0
  SITE_NAME=`echo $ALIAS | cut -d '.' -f2`
  SITE_UUID=`$DRUSH $DRUSH_OPTS psite-uuid $SITE_NAME | cut -d ':' -f 2`

  # change to sftp mode
  $DRUSH $DRUSH_OPTS psite-cmode $SITE_UUID dev sftp
  echo -n "Pausing to permit Pantheon server magic..."
  #sleep 20
  echo -n "..."
  # clean up modules, themes, libraries on the site being upgraded
  cat rrmdir.php rrmdir-post.php | $DRUSH $DRUSH_OPTS $ALIAS scr -
  #sleep 20
  # commit the changes resulting from the delete
  COMMIT_STATUS=$DRUSH $DRUSH_OPTS psite-commit $SITE_UUID dev --message="Remove profiles/panopoly and clean up modules, themes, libraries in preparation for upgrade."

  if ! $DRUSH $DRUSH_OPTS psite-commit $SITE_UUID dev --message="Remove profiles/panopoly and clean up modules, themes, libraries in preparation for upgrade."; then
    # if status is non-zero, indicating error...
    echo ""
    echo "This server is sadly unmagical.  Try the following:"

    $DRUSH $DRUSH_OPTS psite-dash $SITE_UUID
    return 1
  else
    sleep 10
    # change to git mode
    $DRUSH $DRUSH_OPTS psite-cmode $SITE_UUID dev git
  fi
}

if [ "$?" -eq 1 ]; then
  echo "Aborting."
  exit 1
fi

