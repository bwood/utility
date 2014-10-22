#!/bin/bash
export PATH=~/bin:~/bin/drush:~/bin/utility:/usr/local/bin:/usr/bin:~/workspace/scripts:/Applications/acquia-drupal/mysql/bin:/usr/local/bin:/opt/local/bin:/opt/local/sbin:$PATH:/Users/bwood/pear/bin

MODULE=$1
REPO=$2
REF=$3
PROJDIR="profiles/openberkeley/modules"

if [[ $MODULE =~ "openberkeley_" ]]; then
  SUBDIR="openberkeley"
elif [[ $MODULE =~ "ucberkeley_" ]]; then
  SUBDIR="ucb"
else
  echo "Can't determine SUBDIR for this module."
  exit 1
fi

DIR="$PROJDIR/$SUBDIR/$MODULE"

if [ ! -d "$DIR" ] || [ ! -w "$DIR" ]; then
  echo "Not a valid path or not writable: $DIR"
  exit 1
fi

if [[ $REPO =~ "https://" ]]; then
  echo "Please use the ssh version of the git url so your key will be used for authentication."
  exit 1
fi

if [ -z "$REPO" ]; then
  REPO=$MODULE-upstream
fi

if [ -z "$REF" ]; then
  REF="master"
fi

# use rm. sometimes git rm -rf misses subdirectories. strange.
rm -rf $DIR
git add --all
git commit -am "Convert $MODULE to subtree"
git subtree add --prefix=$DIR $REPO $REF --squash
echo ""
echo "Executed:"
echo "git subtree add --prefix=$DIR $REPO $REF --squash"
