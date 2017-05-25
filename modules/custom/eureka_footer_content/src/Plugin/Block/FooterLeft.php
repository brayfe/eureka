<?php

namespace Drupal\eureka_footer_content\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
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

    //$ugslogosrc = Url::fromRoute('themes.eurekatheme.images.knockout_formal_Undergraduate_Studies.png');
    $ugslogourl = Url::fromUri('https://ugs.utexas.edu/');

    return [
          '#ugslogourl' => $ugslogourl,
          //'#ugslogosrc' => $ugslogosrc,
          '#theme' => 'footer_left',
        ];
  }

}
