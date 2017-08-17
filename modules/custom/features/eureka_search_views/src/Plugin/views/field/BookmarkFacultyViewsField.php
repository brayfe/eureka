<?php

namespace Drupal\eureka_search_views\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * A handler to provide a field that is completely custom by the administrator.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("bookmark_faculty_views_field")
 */
class BookmarkFacultyViewsField extends FieldPluginBase {

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
    // Redirect anonymous users to the login page.
    if (\Drupal::currentUser()->isAnonymous()) {
      // @todo: when we switch to SAML, this link will need to be updated.
      $content['#markup'] = '<a href="/user/login?destination=search/faculty" class="btn btn-info btn-primary">
          <span class="glyphicon glyphicon-bookmark"></span> Bookmark
        </a>';
    }
    else {
      $user = $values->_object->toArray();
      $entity = \Drupal::entityTypeManager()->getStorage('user')->load($user['uid'][0]['value']);
      $flag = \Drupal::entityTypeManager()->getStorage('flag')->load('profile_flag');
      $link_type_plugin = $flag->getLinkTypePlugin();
      $link = $link_type_plugin->getAsFlagLink($flag, $entity);
      $content['#markup'] = '<span class="btn btn-info btn-primary">
        <span class="glyphicon glyphicon-bookmark">' . render($link) . '</span>';
    }

    return $content;
  }

}
