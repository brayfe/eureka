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
      '#ugslogo' => t('<a href="https://ugs.utexas.edu/"><img alt="The University of Texas School of Undergraduate Studies" src="/themes/eurekatheme/images/knockout_formal_Undergraduate_Studies.png" /></a>'),
      '#theme' => 'footer_left',
    ];
  }

}
