<?php

namespace Drupal\eureka_bookmark_dashboard\Controller;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use \Drupal\user\Entity\User;

/**
 * Controller for the user bookmark dashboard.
 */
class DashboardController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function content() {
    $build = [
      '#type' => 'markup',
    ];
    $user = User::load(\Drupal::currentUser()->id());
    $roles = $user->getRoles();
    if (!in_array('faculty', $roles)) {
      if ($user->get('field_terms_of_ser')->value == FALSE) {
        $text = $this->notification_button();
        $build['tos']['#markup'] = render($text);
        $build['#attached']['library'][] = 'eureka_notifications/tos';
      }
    }
    return $build;
  }

  /**
   * Custom callback for Terms of Service link.
   */
  public function notification_button() {
    $title = 'Terms of Service';
    $link = [
      '#weight' => 1000,
      '#type' => 'inline_template',
      '#template' => '{{ link }}',
      '#context' => [
        'link' => Link::createFromRoute(
          $title,
          'eureka_notifications.acknowledgement_form',
          [],
          [
            'attributes' => [
              'title' => $title,
              'id' => 'terms-of-service',
              'class' => ['use-ajax', 'button', 'button--small'],
              'data-dialog-type' => 'modal',
              'data-dialog-options' => Json::encode(['width' => 1000, 'max-height' => 'none']),
            ],
          ]
        ),
      ],
    ];
    return $link;
  }

}
