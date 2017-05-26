<?php

namespace Drupal\eureka_footer_content\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;

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
      '#ugslogourl' => Url::fromUri('https://ugs.utexas.edu/'),
      '#ugslogosrc' => '/themes/eurekatheme/images/knockout_formal_Undergraduate_Studies.png',
      '#ugslogoalt' => t('University of Texas School of Undergraduate Studies'),
      '#theme' => 'footer_left',
    ];
  }

}
