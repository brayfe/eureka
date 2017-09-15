<?php

/**
 * @file
 * Local settings file.
 */

$databases['default']['default'] = array (
  'database' => 'eureka',
  'username' => 'root',
  'password' => 'root',
  'prefix' => '',
  'host' => 'localhost',
  'port' => '8889',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
  'collation' => 'utf8mb4_general_ci',
);

$databases['legacy']['default'] = array (
  'database' => 'eureka_legacy',
  'username' => 'root',
  'password' => 'root',
  'prefix' => '',
  'host' => 'localhost',
  'port' => '8889',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);

$settings['hash_salt'] = 'ltAz5VM-nECwubssZ8d0Vla048ed7A2jRkKxUunCWhoMDlYAKTc6NlTHj5cNpzphn4d1oPZ-yw';
$settings['skip_permissions_hardening'] = TRUE;
$config['system.file']['path']['temporary'] = '/tmp';

/**
 * Enable local development services.
 */
$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/development.services.yml';
