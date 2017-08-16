<?php

/**
 * @file
 * Register Views field handler for faculty bookmark flag.
 */

/**
 * Implements hook_views_data_alter().
 */
function eureka_contacted_faculty_views_data_alter(array &$data) {
  $data['flagging']['contacted_faculty'] = array(
    'title' => t('Contacted Faculty'),
    'field' => array(
      'title' => t('Contacted Faculty'),
      'help' => t('Logs whether a faculty member has been contacted.'),
      'id' => 'contacted_faculty',
    ),
  );
}
