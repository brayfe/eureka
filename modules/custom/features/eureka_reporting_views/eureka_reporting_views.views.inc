<?php

/**
 * @file
 * Contains eureka_reporting_views\eureka_reporting_views.views.inc..
 *
 * Provide a custom views field data that isn't tied to any other module.
 */

/**
 * Implements hook_views_data_alter().
 */
function eureka_reporting_views_views_data_alter(array &$data) {
  $data['users']['bookmarked_faculty'] = array(
    'title' => t('Bookmarked Faculty'),
    'field' => array(
      'title' => t('Bookmarked faculty'),
      'help' => t('Show which faculty profiles a user has bookmarked'),
      'id' => 'bookmarked_faculty',
    ),
  );
}
