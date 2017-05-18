<?php

namespace Drupal\eureka_footer_content\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a Footer Block.
 *
 * @Block(
 *   id = "footer_left_block",
 *   admin_label = @Translation("Footer Left Block"),
 * )
 */
class FooterLeft extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
return [
      '#theme' => 'footer_content_left',
      '#name' => 'Rick Hastie',
      '#year' => date('Y'),
      // '#attached' => [
      //   'css' => [
      //     drupal_get_path('module', 'acme') . '/assets/css/acme.css'
      //   ]
      // ]
    ];
  }

}
