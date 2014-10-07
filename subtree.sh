#!/bin/bash
MODULE=$1
REPO=$2
REF=$3
PATH="profiles/openberkeley/modules/openberkeley"
DIR="$PATH/$MODULE"

export PATH=~/bin:~/bin/drush:~/bin/utility:/usr/bin:/usr/local/bin/git:~/workspace/scripts:/Applications/acquia-drupal/mysql/bin:/usr/local/bin:/opt/local/bin:/opt/local/sbin:$PATH:/Users/bwood/pear/bin

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

git rm -rf $DIR
git commit -am "Convert $MODULE to subtree"
git subtree add --prefix=$DIR $REPO $REF --squash
echo ""
echo "Executed:"
echo "git subtree add --prefix=$DIR $REPO $REF --squash"
