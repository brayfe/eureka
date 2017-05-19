<?php

namespace Drupal\eureka_footer_content\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a Footer Block.
 *
 * @Block(
 *   id = "footer_right_block",
 *   admin_label = @Translation("Footer Right Block"),
 * )
 */
class FooterRight extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
return [
      '#theme' => 'footer_right',
      '#name' => 'Mark Fullmer',
      '#year' => date('Y'),
      // '#attached' => [
      //   'css' => [
      //     drupal_get_path('module', 'acme') . '/assets/css/acme.css'
      //   ]
      // ]
    ];
  }

}
