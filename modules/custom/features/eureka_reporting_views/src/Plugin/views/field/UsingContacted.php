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
 * @ViewsField("using_contacted")
 */
class UsingContacted extends FieldPluginBase {

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
    $count = 0;
    $display_names = [];
    $faculty = $this->getFacultyFlags($values->uid);
    foreach ($faculty as $key => $f) {
      $contacted[] = $this->getContactedStatus($f->id);
    }
    if (!empty($contacted)) {
      foreach ($contacted as $key => $value) {
        if ($value == 1) {
          $count++;
        }
      }
    }
    return $count;
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
    $query->addField('f', 'id');
    $query->condition('f.uid', $uid);
    $query->condition('f.entity_type', 'user');
    return $query->execute()->fetchAllAssoc('id');
  }

  /**
   * Custom query to retrieve user display_name.
   *
   * @param string $id
   *    The user uid.
   *
   * @return string
   *    The user's display name.
   */
  public function getContactedStatus($id) {
    $query = \Drupal::database()->select('flagging__field_contacted_faculty', 'u');
    $query->addField('u', 'field_contacted_faculty_value');
    $query->condition('u.entity_id', $id);
    $query->range(0, 1);
    return $query->execute()->fetchField();
  }

}
