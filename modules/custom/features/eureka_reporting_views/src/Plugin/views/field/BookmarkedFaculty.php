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
 * @ViewsField("bookmarked_faculty")
 */
class BookmarkedFaculty extends FieldPluginBase {

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
    $display_names = [];
    $faculty = $this->getFacultyFlags($values->uid);
    foreach ($faculty as $key => $f) {
      $display_names[$f->entity_id] = $this->getDisplayName($f->entity_id);
    }
    if (!empty($display_names)) {
      $total = count($display_names);
      return $total . ': ' . implode(', ', $display_names);
    }
    return '';
  }

  /**
   * Custom query to retrieve faculty flagged by a specific user.
   *
   * @param string $uid
   *    The user uid.
   *
   * @return string
   *    The user's display name.
   */
  public function getFacultyFlags($uid) {
    $query = \Drupal::database()->select('flagging', 'f');
    $query->addField('f', 'entity_id');
    $query->condition('f.uid', $uid);
    $query->condition('f.entity_type', 'user');
    return $query->execute()->fetchAllAssoc('entity_id');
  }

  /**
   * Custom query to retrieve user display_name.
   *
   * @param string $uid
   *    The user uid.
   *
   * @return string
   *    The user's display name.
   */
  public function getDisplayName($uid) {
    $query = \Drupal::database()->select('user__field_display_name', 'u');
    $query->addField('u', 'field_display_name_value');
    $query->condition('u.entity_id', $uid);
    $query->range(0, 1);
    return $query->execute()->fetchField();
  }

}
