<?php

namespace Drupal\eureka_footer_content\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a Footer Block.
 *
 * @Block(
 *   id = "footer_upper_block",
 *   admin_label = @Translation("Footer Upper Block"),
 * )
 */
class FooterUpper extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
return [
      '#theme' => 'footer_upper',
    ];
  }

}
