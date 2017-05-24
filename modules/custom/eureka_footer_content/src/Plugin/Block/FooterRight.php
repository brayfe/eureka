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
      '#year' => date('Y'),
      '#policy_link' => t('<a href="/basic-page">Site Polices</a>'),
      '#accessibility_link' => t('<a href="https://cio.utexas.edu/policies/web-accessibility">Web Accessibility</a>'),
      '#privacy_link' => t('<a href="https://cio.utexas.edu/policies/web-privacy">Web Privacy Policy</a>'),
    ];
  }

}
