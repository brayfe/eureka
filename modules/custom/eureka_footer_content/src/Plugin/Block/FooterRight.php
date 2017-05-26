<?php

namespace Drupal\eureka_footer_content\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

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

    $access_url = Url::fromUri('https://cio.utexas.edu/policies/web-accessibility');
    $access_link = Link::fromTextAndUrl('Web Accessibility', $access_url);

    $privacy_url = Url::fromUri('https://cio.utexas.edu/policies/web-privacy');
    $privacy_link = Link::fromTextAndUrl('Web Privacy Policy', $privacy_url);

    return [
      '#theme' => 'footer_right',
      '#year' => date('Y'),
      '#accessibility_link' => $access_link,
      '#privacy_link' => $privacy_link,
    ];
  }

}
