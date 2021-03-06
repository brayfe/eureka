<?php

/**
 * @file
 * Drupal site-specific configuration file.
 */

/**
 * Load services definition file.
 */
$settings['container_yamls'][] = __DIR__ . '/services.yml';

/**
 * Include the Pantheon-specific settings file.
 *
 *      The settings.pantheon.php file makes some changes
 *      that affect all envrionments that this site
 *      exists in.  Always include this file, even in
 *      a local development environment, to insure that
 *      the site settings remain consistent.
 */
include __DIR__ . "/settings.pantheon.php";

/**
 * If there is a site settings file, then include it.
 */
$site_settings = __DIR__ . "/settings.site.php";
if (file_exists($site_settings)) {
  include $site_settings;
}

/**
 * If there is a local settings file, then include it.
 */
$local_settings = __DIR__ . "/settings.local.php";
if (file_exists($local_settings)) {
  include $local_settings;
}

/**
 * Specify which install profile to use.
 */
$settings['install_profile'] = 'eureka';
