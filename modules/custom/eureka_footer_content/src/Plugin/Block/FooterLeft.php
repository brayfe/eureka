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
      '#theme' => 'footer_left',
    ];
  }

}
