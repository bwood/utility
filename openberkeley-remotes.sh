#!/bin/bash
PROTOCOL='git@'            # change if you use https:// or something else
DOMAIN='github-bwood.com'  # change depending on your use of ssh-config
USER='bwood'               # this is your github username
# the $USER uri's below assume you have these forks on GitHub
git remote add -f openberkeley_admin-upstream $PROTOCOL$DOMAIN:ucb-ist-drupal/openberkeley_admin.git 
git remote add -f openberkeley_admin $PROTOCOL$DOMAIN:$USER/openberkeley_admin.git 
git remote add -f openberkeley_base $PROTOCOL$DOMAIN:$USER/openberkeley_base.git 
git remote add -f openberkeley_base-upstream $PROTOCOL$DOMAIN:ucb-ist-drupal/openberkeley_base.git 
git remote add -f openberkeley_faq $PROTOCOL$DOMAIN:$USER/openberkeley_faq.git 
git remote add -f openberkeley_faq-upstream $PROTOCOL$DOMAIN:ucb-ist-drupal/openberkeley_faq.git 
git remote add -f openberkeley_news $PROTOCOL$DOMAIN:$USER/openberkeley_news.git 
git remote add -f openberkeley_news-upstream $PROTOCOL$DOMAIN:ucb-ist-drupal/openberkeley_news.git 
git remote add -f openberkeley_pages $PROTOCOL$DOMAIN:$USER/openberkeley_pages.git 
git remote add -f openberkeley_pages-upstream $PROTOCOL$DOMAIN:ucb-ist-drupal/openberkeley_pages.git 
git remote add -f openberkeley_starter $PROTOCOL$DOMAIN:$USER/openberkeley_starter.git 
git remote add -f openberkeley_starter-upstream $PROTOCOL$DOMAIN:ucb-ist-drupal/openberkeley_starter.git 
git remote add -f ucberkeley_cas $PROTOCOL$DOMAIN:$USER/ucberkeley_cas-7-test.git 
git remote add -f ucberkeley_cas-upstream $PROTOCOL$DOMAIN:ucb-ist-drupal/ucberkeley_cas-7.git 
git remote add -f upstream $PROTOCOL$DOMAIN:ucb-ist-drupal/openberkeley-drops-7.git 
