<?php

/**
 * @file
 * Defines the Eureka Profile install screen by modifying the install form.
 */

use Drupal\taxonomy\Entity\Term;

/**
 * Implements hook_install_tasks().
 */
function eureka_install_tasks() {
  return [
    'eureka_install_extensions' => [
      'display_name' => t('Install Extensions'),
      'display' => TRUE,
      'type' => 'batch',
    ],
    'eureka_install_terms' => [
      'display_name' => t('Install Terms'),
      'type' => 'normal',
    ],
    'eureka_modify_configuration' => [
      'display_name' => t('Modify Configuration'),
      'type' => 'normal',
    ],
  ];
}

/**
 * Implements hook_install_tasks_alter().
 */
function eureka_install_tasks_alter(array &$tasks, array $install_state) {
  $tasks['install_finished']['function'] = 'eureka_post_install_redirect';
}

/**
 * Install task callback; Adds defualt terms to taxonomy.
 *
 * @param array $install_state
 *   The current install state.
 */
function eureka_install_terms(array &$install_state) {
  $vid = 'intern_criteria';
  $terms = [
    'Student research assistants',
    'Students conducting independent research or a thesis',
    'Majors from their affiliated departments',
    'Any interested and motivated student, regardless of academic background',
    'Freshmen',
    'Sophomores',
    'Juniors',
    'Seniors',
    'Students who have already taken classes with this professor',
    'Students in the Freshman Research Initiative program',
    'Students in the McNair Scholars program',
    'Students in the Bridging Disciplines Programs',
    'Students in the Discovery Scholars program',
    'Students in the Presidential Scholars program',
    'Students in the University Leadership Network',
  ];

  foreach ($terms as $weight => $term) {
    $tid = Term::create([
      'name' => $term,
      'vid' => $vid,
      'weight' => $weight,
    ])->save();
  }
}

/**
 * Install task callback. Modifies core configuration.
 *
 * @param array $install_state
 *   The current install state.
 */
function eureka_modify_configuration(array &$install_state) {
  // Disable the default 'taxonomy_term' view.
  \Drupal::entityTypeManager()->getStorage('view')
    ->load('taxonomy_term')
    ->setStatus(FALSE)
    ->save();
}

/**
 * Install task callback; prepares a batch job to install Eureka extensions.
 *
 * @param array $install_state
 *   The current install state.
 *
 * @return array
 *   The batch job definition.
 */
function eureka_install_extensions(array &$install_state) {
  $batch = [];
  $modules = [
    'features',
    'config_update',
    'settings_eurekatheme',
    'eureka_footer_content',
    'eureka_footer_block_settings',
    'eureka_main_nav_menu_settings',
    'eureka_faculty_profile_taxonomies',
    'eureka_profile_entity',
    'eureka_project_ct',
    'simplify_global_settings',
    'url_aliases',
    'eureka_taxonomy_views',
  ];
  foreach ($modules as $module) {
    $batch['operations'][] = ['eureka_install_module', (array) $module];
  }
  return $batch;
}

/**
 * Batch API callback. Installs a module.
 *
 * @param string|array $module
 *   The name(s) of the module(s) to install.
 */
function eureka_install_module($module) {
  \Drupal::service('module_installer')->install((array) $module);
}

/**
 * Redirects the user to a particular URL after installation.
 *
 * @param array $install_state
 *   The current install state.
 *
 * @return array
 *   A renderable array with a success message and a redirect header, if the
 *   extender is configured with one.
 */
function eureka_post_install_redirect(array &$install_state) {
  $redirect = \Drupal::request()->getSchemeAndHttpHost();

  $output = [
    '#title' => t('Ready to go!'),
    'info' => [
      '#markup' => t('Congratulations, you installed your site! If you are not redirected in 5 seconds, <a href="@url">click here</a> to proceed to your site.', [
        '@url' => $redirect,
      ]),
    ],
    '#attached' => [
      'http_header' => [
        ['Cache-Control', 'no-cache'],
      ],
    ],
  ];

  // The installer doesn't make it easy (possible?) to return a redirect
  // response, so set a redirection META tag in the output.
  $meta_redirect = [
    '#tag' => 'meta',
    '#attributes' => [
      'http-equiv' => 'refresh',
      'content' => '0;url=' . $redirect,
    ],
  ];
  $output['#attached']['html_head'][] = [$meta_redirect, 'meta_redirect'];

  return $output;
}
