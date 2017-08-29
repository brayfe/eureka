<?php

namespace Drupal\eureka_reporting_views\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * A handler to provide a field that is completely custom by the administrator.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("bookmarked_projects")
 */
class BookmarkedProjects extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function usesGroupBy() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Do nothing -- to override the parent query.
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();

    $options['hide_alter_empty'] = ['default' => FALSE];
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    $project_names = [];
    $projects = $this->getProjectFlags($values->uid);
    foreach ($projects as $key => $p) {
      $project_names[$p->entity_id] = $this->getProjectTitle($p->entity_id);
    }
    if (!empty($project_names)) {
      $total = count($project_names);
      return $total . ': ' . implode(', ', $project_names);
    }
    return '';
  }

  /**
   * Custom query to retrieve projects flagged by a specific user.
   *
   * @param string $uid
   *    The user uid.
   *
   * @return string
   *    The user's display name.
   */
  public function getProjectFlags($uid) {
    $query = \Drupal::database()->select('flagging', 'f');
    $query->addField('f', 'entity_id');
    $query->condition('f.uid', $uid);
    $query->condition('f.entity_type', 'node');
    return $query->execute()->fetchAllAssoc('entity_id');
  }

  /**
   * Custom query to retrieve project title.
   *
   * @param string $nid
   *    The project nid.
   *
   * @return string
   *    The project title.
   */
  public function getprojectTitle($nid) {
    $query = \Drupal::database()->select('node_field_data', 'n');
    $query->addField('n', 'title');
    $query->condition('n.nid', $nid);
    $query->range(0, 1);
    return $query->execute()->fetchField();
  }

}
