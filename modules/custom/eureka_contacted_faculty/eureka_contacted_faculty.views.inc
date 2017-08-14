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

/**
 * Implements hook_views_data_alter().
 */
/*function eureka_contacted_faculty_views_data_alter(array &$data) {
  $flags = \Drupal::service('flag')->getAllFlags();
  $entity_type_manager = \Drupal::entityTypeManager();

  foreach ($flags as $flag) {
    $entity_type_id = $flag->getFlaggableEntityTypeId();

    $entity_type = $entity_type_manager->getDefinition($entity_type_id);
    if ($entity_type->hasHandlerClass('views_data')) {
      $base_table = $entity_type_manager->getHandler($entity_type_id, 'views_data')->getViewsTableForEntityType($entity_type);
      $data[$base_table]['flag_relationship'] = [
        'title' => t('@entity_label flag', ['@entity_label' => $entity_type->getLabel()]),
        'help' => t('Limit results to only those entity flagged by a certain flag; Or display information about the flag set on a entity.'),
        'relationship' => [
          'group' => t('Flag'),
          'label' => t('Flags'),
          'base' => 'flagging',
          'base field' => 'entity_id',
          'relationship field' => $entity_type->getKey('id'),
          'id' => 'flag_relationship',
          'flaggable' => $entity_type_id,
        ],
      ];
      kint($data);
    }

  }
}
*/
