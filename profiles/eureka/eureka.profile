<?php

/**
 * @file
 * Defines the Eureka Profile install screen by modifying the install form.
 */

use \Drupal\node\Entity\Node;
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
    'eureka_install_content' => [
      'display_name' => t('Install Content'),
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
    'eureka_filtered_html_format',
    'eureka_page_content_type',
    'eureka_footer_content',
    'eureka_footer_block_settings',
    'eureka_main_nav_menu_settings',
    'eureka_faculty_profile_taxonomies',
    'eureka_profile_entity',
    'eureka_project_ct',
    'eureka_search_setup',
    'eureka_search_views',
    'eureka_search_facets',
    'simplify_global_settings',
    'url_aliases',
    'eureka_role_faculty',
    'eureka_taxonomy_views',
    'eureka_flipcard_block_settings',
    'eureka_role_anonymous',
  ];
  foreach ($modules as $module) {
    $batch['operations'][] = ['eureka_install_module', (array) $module];
  }
  return $batch;
}

/**
 * Install task callback; Adds default terms to taxonomy.
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
 * Install task callback. Adds default content.
 *
 * @param array $install_state
 *   The current install state.
 */
function eureka_install_content(array &$install_state) {
  // Create node object with attached file.
  $node = Node::create([
    'type'        => 'page',
    'title'       => 'Homepage',
    'field_body' => [
      'value' => _homepage_content(),
      'format' => 'filtered_html',
    ],
  ]);
  $node->save();
}

/**
 * Content callback for eureka_install_content().
 *
 * @return string
 *   Currently, just the text for the homepage.
 */
function _homepage_content() {
  return 'Eureka is a searchable database supporting undergraduate participation in research and creative activity across The University of Texas at Austin. It features profiles for UT Austin faculty members with information about their research interests, as well as a listing of posted openings for undergraduates on research projects.

UT Austin is one of the top public research universities in the United States, and our researchers are leaders in a variety of fields. From nanotechnology to musical composition, child welfare to popular culture, faculty members and students conduct innovative research every day. Students from all disciplines can use Eureka to identify research interests and connect with faculty researchers. We encourage students to attend a weekly information session or advising through the Office of Undergraduate Research before making contact with professors.

Eureka was originally created by Connexus: Connections in Undergraduate Studies, in collaboration with the College of Natural Sciences, the Cockrell School of Engineering, the College of Liberal Arts, the Office of the Vice President for Research, the Office of the Executive Vice President and Provost, and the Center for Teaching and Learning. Since 2006, Eureka has been part of the portfolio of the School of Undergraduate Studies, which sponsored a rewrite of the site in 2017.

For more information about EUREKA, please contact the <a href="https://ugs.utexas.edu/about/contact">Office of Undergraduate Research</a>.';

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
  \Drupal::service('config.factory')->getEditable('system.site')->set('page.front', '/homepage')->save();
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
