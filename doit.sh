#!/bin/bash

# openucb-957. I ended up not using this.

BACKUP_CAS_SERVER="cas-p4.calnet.berkeley.edu"

echo ""
echo "This script can enable or disable the backup CAS server 
($BACKUP_CAS_SERVER) for sites. "
echo ""
echo "What do you want to do:"
echo "[1] Enable $BACKUP_CAS_SERVER"
echo "[2] Disable $BACKUP_CAS_SERVER"
read ACTION
if [ "$ACTION" == 1 ]; then
  SERVER=$BACKUP_CAS_SERVER;
  MODULE_ACTION="dis"
  DONE_ACTION="ENABLED"
elif [ "$ACTION" == 2 ]; then
  # If ucberkeley_envconf is enabled, we don't really need a value.
  SERVER="" 
  MODULE_ACTION="en"
  DONE_ACTION="DISABLED"
else 
  echo "Please choose 1 or 2."
fi

echo ""
echo "Enter the shortname (e.g. webaccess-ob7) of the site(s). 
Separate multiple names with spaces:" 
IFS=' ' 
read -a SHORTNAMES
echo ""
echo "Which environment?"
echo "[1] live"
echo "[2] test"
echo "[3] dev"
echo "[4] live and test"
echo "[5] live, test and dev"
read ENV
echo ""
if [ "$ENV" == 1 ];then
  ENV=( live )
fi
if [ "$ENV" == 2 ];then
  ENV=( test )
fi
if [ "$ENV" == 3 ];then
  ENV=( dev )
fi
if [ "$ENV" == 4 ];then
  ENV=( live test )
fi
if [ "$ENV" == 5 ];then
  ENV=( live test dev )
fi

for SHORTNAME in "${SHORTNAMES[@]}"; do
  for ENVIRONMENT in "${ENV[@]}"; do
    # wake up the non-live sites
    if [ $ENVIRONMENT != "live" ]; then
      terminus site wake --site=$SHORTNAME --env=$ENVIRONMENT
    fi

    # disable envconf
    drush --strict=0  @pantheon.$SHORTNAME.$ENVIRONMENT $MODULE_ACTION ucberkeley_envconf -y
    #echo "    drush --strict=0  @pantheon.$SHORTNAME.$ENVIRONMENT $MODULE_ACTION ucberkeley_envconf -y"
  
    # On disable set the CAS server so that 'drush vget cas_server' shows the right thing.
    if [ $ENVIRONMENT == "live" ] && [ $DONE_ACTION == "DISABLED" ]; then
     SERVER="auth.berkeley.edu"
    elif [ $ENVIRONMENT != "live" ] && [ $DONE_ACTION == "DISABLED" ]; then
     SERVER="auth-test.berkeley.edu"
    fi

    # set cas_server
    drush --strict=0  @pantheon.$SHORTNAME.$ENVIRONMENT vset cas_server $SERVER 
    #echo "    drush --strict=0  @pantheon.$SHORTNAME.$ENVIRONMENT vset cas_server $SERVER"

    echo ""
    echo "Done: $SHORTNAME.$ENVIRONMENT: Backup CAS server $DONE_ACTION"
    echo ""
  done
done

echo ""
echo "Disregard the harmless syntax error which occurs on Enable actions."
echo ""


exit
####

#grep a file every 15 sec
i=1
while [ $i -lt 401 ]; do
  echo "check $i"
  grep 'WD ldap_server' /Users/bwood/tmp/openucb-768_out.txt
  ((i++))
  sleep 15
done
exit
######

// openucb-768 test ldap errors

uids=(213108 304629 248324 267087)
names=('Anna Gazdowicz' 'Brian Wood' 'Caroline Boyden' 'Kathleen Lu')

i=1
while [ $i -lt 20 ]; do
  echo "Attempt: $i"
  for name in "${names[@]}"; do
    echo "drush @ob7 ucan \"$name\""
    drush @ob7 ucan "$name" -y
  done

  for uid in "${uids[@]}"; do
    echo "drush @ob7 cas-user-create $uid"
    drush @ob7 cas-user-create $uid -y
  done;
  ((i++))
done

exit
######

OLDIFS=$IFS
IFS=','
COMMANDS=ls,find,make\ \-f\ \$this,yes
read -a array <<< "$COMMANDS"
echo ${array[2]}
IFS=$OLDIFS

exit
#######


BUILD=nomk-noup
echo `expr substr "$BUILD" 1 4`
echo `expr substr "$BUILD" 6`

exit
#######

cmd=$(drush psite-tunnel 6af40837-246b-44d2-9b8a-228d2cebba39 dev mysql)
echo $cmd
exit
########

# remove datestamps from all .info files
if [ $(PAGER=cat man sed |grep -c -e "^BSD") -gt 0 ]; then
  # FreeBSD sed (e.g. MacOS)
  echo "bsd"
  find modules -name "*.info" -print0 |xargs -0 sed -i "" -e 's/datestamp = \"[[:digit:]]\{1,\}\".*//' -e 's/Information added by drush on [[:digit:]]\{4\}-[[:digit:]]\{2\}-[[:digit:]]\{2\}/Information added by drush/'
else
  # not BSD sed
  find modules -name "*.info" -print0 |xargs -0 sed -i -e 's/datestamp = \"[[:digit:]]\{1,\}\".*//' -e 's/Information added by drush on [[:digit:]]\{4\}-[[:digit:]]\{2\}-[[:digit:]]\{2\}/Information added by drush/'
fi


exit
###########

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

