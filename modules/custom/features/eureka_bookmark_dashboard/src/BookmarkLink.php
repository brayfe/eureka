<?php

namespace Drupal\eureka_bookmark_dashboard;

/**
 * Class BookmarkLink.
 *
 * @package Drupal\eureka_bookmark_dashboard
 */
class BookmarkLink {

  /**
   * TODO: update variable declarations.
   */
  public static function getLink($type, $id) {
    $path = '/node/' . $id;
    $entity_type = 'node';
    if ($type == 'profile_flag') {
      $path = '/user/' . $id;
      $entity_type = 'user';
    }

    $alias = \Drupal::service('path.alias_manager')->getAliasByPath($path);

    // Redirect anonymous users to the login page.
    if (\Drupal::currentUser()->isAnonymous()) {
      // @todo: when we switch to SAML, this link will need to be updated.
      $link['#markup'] = '<a href="/user/login?destination=' . $alias . '" class="btn btn-info btn-primary">
          <span class="glyphicon glyphicon-star-empty"></span> Sign in to Bookmark
        </a>';
    }
    else {
      $entity = \Drupal::entityTypeManager()->getStorage($entity_type)->load($id);
      $flag = \Drupal::entityTypeManager()->getStorage('flag')->load($type);
      $link_type_plugin = $flag->getLinkTypePlugin();
      $link = $link_type_plugin->getAsFlagLink($flag, $entity);
    }

    $link['#attached']['library'][] = 'eureka_search_views/eureka-search-views';

    return $link;
  }

}
