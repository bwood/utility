//<?php

// Create an Entity
$e = entity_create('node', array(
    'type' => 'openberkeley_content_page',
    'path' => array('alias' => 'blocked/security-block-lists', 'pathauto' => FALSE)
/*
    //is menu the right key?
    'menu' => array(
      'menu_name' => 'main-menu',
      'link_path' => 'blocked',
      'router_path' => 'blocked',
      'link_title' => 'Security Block Lists',
      'weight' => 0,
      'customized' => 1,
    ),
*/
  )
);
$e->uid = 1;
$entity = entity_metadata_wrapper('node', $e);
$entity->title = 'Security Block Lists';
$entity->body->set(array('value' => "<p>UC Berkeley Security maintains block lists viewable by certain trusted users.</p>"));
$entity->body->format = 'panopoly_wysiwyg_text';
$entity->language(LANGUAGE_NONE);
$entity->save();

/*
$item = array(
  'menu_name' => 'main-menu',
  'link_path' => 'blocked',
  'router_path' => 'Blocked',
  'link_title' => 'Security Block Lists',
  'weight' => 0,
  'customized' => 1,
);
$mlid = menu_link_save($item);
*/

/*
$path_data = array(
  'source' => 'node/' . entity_id('node', $entity),
  'alias' => 'blocked'
);
path_save($path_data);
*/