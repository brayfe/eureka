<?php

namespace Drupal\eureka_bookmark_dashboard\Controller;

use Drupal\Core\Controller\ControllerBase;

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
    return $build;
  }

}
