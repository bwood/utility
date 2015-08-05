<?php
$feature = features_get_features('ucberkeley_cas');
var_dump($feature);
$components = array_keys($feature->info['features']);
print_r($components);