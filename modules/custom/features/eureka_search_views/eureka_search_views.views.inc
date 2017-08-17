<?php

/**
 * @file
 * Register Views field handler for faculty bookmark flag.
 */

/**
 * Implements hook_views_data_alter().
 */
function eureka_search_views_views_data_alter(array &$data) {
  $data['search_api_index_project_index']['bookmark_project_views_field'] = array(
    'title' => t('Bookmark Project'),
    'field' => array(
      'title' => t('Bookmark Project'),
      'help' => t('Adds ability to bookmark items'),
      'id' => 'bookmark_project_views_field',
    ),
  );
  $data['search_api_index_faculty_index']['bookmark_faculty_views_field'] = array(
    'title' => t('Bookmark Faculty'),
    'field' => array(
      'title' => t('Bookmark Faculty'),
      'help' => t('Adds ability to bookmark items'),
      'id' => 'bookmark_faculty_views_field',
    ),
  );
}
