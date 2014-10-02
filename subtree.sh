#!/bin/bash
MODULE=$1
PATH="profiles/openberkeley/modules/openberkeley"
DIR="$PATH/$MODULE"

export PATH=~/bin:~/bin/drush:~/bin/utility:/usr/bin:/usr/local/bin/git:~/workspace/scripts:/Applications/acquia-drupal/mysql/bin:/usr/local/bin:/opt/local/bin:/opt/local/sbin:$PATH:/Users/bwood/pear/bin

if [ ! -d "$DIR" ] || [ ! -w "$DIR" ]; then
  echo "Not a valid path or not writable: $DIR"
  exit 1
fi

git rm -rf $DIR
git commit -am "Convert $MODULE to subtree"
git subtree add --prefix=$DIR $MODULE-upstream master --squash
