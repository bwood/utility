#!/bin/bash
GIT_PROTOCOL='git@'            # change if you use https:// or something else
GIT_DOMAIN='github-bwood.com'  # change depending on your use of ssh-config
GIT_USER='bwood'               # this is your github username
# the $GIT_USER uri's below assume you have these forks on GitHub
git remote add -f openberkeley_admin-upstream $GIT_PROTOCOL$GIT_DOMAIN:ucb-ist-drupal/openberkeley_admin.git 
git remote add -f openberkeley_admin $GIT_PROTOCOL$GIT_DOMAIN:$GIT_USER/openberkeley_admin.git 
git remote add -f openberkeley_base $GIT_PROTOCOL$GIT_DOMAIN:$GIT_USER/openberkeley_base.git 
git remote add -f openberkeley_base-upstream $GIT_PROTOCOL$GIT_DOMAIN:ucb-ist-drupal/openberkeley_base.git 
git remote add -f openberkeley_core_override $GIT_PROTOCOL$GIT_DOMAIN:$GIT_USER/openberkeley_core_override.git 
git remote add -f openberkeley_core_override-upstream $GIT_PROTOCOL$GIT_DOMAIN:ucb-ist-drupal/openberkeley_core_override.git 
git remote add -f openberkeley_faq $GIT_PROTOCOL$GIT_DOMAIN:$GIT_USER/openberkeley_faq.git 
git remote add -f openberkeley_faq-upstream $GIT_PROTOCOL$GIT_DOMAIN:ucb-ist-drupal/openberkeley_faq.git 
git remote add -f openberkeley_news $GIT_PROTOCOL$GIT_DOMAIN:$GIT_USER/openberkeley_news.git 
git remote add -f openberkeley_news-upstream $GIT_PROTOCOL$GIT_DOMAIN:ucb-ist-drupal/openberkeley_news.git 
git remote add -f openberkeley_pages $GIT_PROTOCOL$GIT_DOMAIN:$GIT_USER/openberkeley_pages.git 
git remote add -f openberkeley_pages-upstream $GIT_PROTOCOL$GIT_DOMAIN:ucb-ist-drupal/openberkeley_pages.git 
git remote add -f openberkeley_starter $GIT_PROTOCOL$GIT_DOMAIN:$GIT_USER/openberkeley_starter.git 
git remote add -f openberkeley_starter-upstream $GIT_PROTOCOL$GIT_DOMAIN:ucb-ist-drupal/openberkeley_starter.git 
# while ucberkeley_cas is owned by bwood these next two will be the same
git remote add -f ucberkeley_cas $GIT_PROTOCOL$GIT_DOMAIN:$GIT_USER/ucberkeley_cas-7.git 
git remote add -f ucberkeley_cas-upstream $GIT_PROTOCOL$GIT_DOMAIN:$GIT_USER/ucberkeley_cas-7.git 
git remote add -f upstream $GIT_PROTOCOL$GIT_DOMAIN:ucb-ist-drupal/openberkeley-7.git 
