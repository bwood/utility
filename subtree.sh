#!/bin/bash
export PATH=~/bin:~/bin/drush:~/bin/utility:/usr/local/bin:/usr/bin:~/workspace/scripts:/Applications/acquia-drupal/mysql/bin:/usr/local/bin:/opt/local/bin:/opt/local/sbin:$PATH:/Users/bwood/pear/bin

MODULE=$1
REPO=$2
REF=$3
PREFIX=$4
PREFIX_DEFAULT="profiles/openberkeley/modules"

if [ -z "$MODULE" ]; then
  echo "Setup a module directory as a git subtree."
  echo ""
  echo "Usage:"
  echo ""
  echo "$0 [module-name] [repo-alias-or-url] [branch] [prefix-dir: default=$PREFIX_DEFAULT]"
  echo ""
  echo "E.g:"
  echo ""
  echo "$0 openberkeley_twitter openberkeley_twitter master"
  echo "$0 ucberkeley_uhs git@github-bwood.com:bwood/ucberkeley_uhs.git master sites/all/modules"
  echo ""
  exit 0
fi

if [ -z "$PREFIX" ]; then
  PREFIX=$PREFIX_DEFAULT
  if [[ $MODULE =~ "openberkeley_" ]]; then
    SUBDIR="openberkeley/"
  elif [[ $MODULE =~ "ucberkeley_" ]]; then
    SUBDIR="ucb/"
  else
    echo "Can't determine SUBDIR for this module."
    exit 1
  fi
else
  SUBDIR=""
fi

DIR="$PREFIX/$SUBDIR$MODULE"

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


# If the directory exists remove it so we can do the subtree add
if [ -d "$DIR" ]; then
  # use rm. sometimes git rm -rf misses subdirectories. strange.  
  rm -rf $DIR
  git add $DIR
  git commit -am "Convert $MODULE to subtree"
fi

git subtree add --prefix=$DIR $REPO $REF --squash
echo ""
echo "Executed:"
echo "git subtree add --prefix=$DIR $REPO $REF --squash"
