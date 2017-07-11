<?php

namespace Drupal\utexas_flipcard_block\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides block plugin definitions for mymodule blocks.
 *
 * @see \Drupal\utexas_flipcard_block\Plugin\Block\FlipCardBlock
 */
class UTexasFlipCardBlockDerivative extends DeriverBase implements ContainerDeriverInterface {

  /**
   * The base plugin ID.
   *
   * @var string
   */
  protected $basePluginId;

  /**
   * Constructs a new UTexasFlipCardBlockDerivative.
   *
   * @param string $base_plugin_id
   *   The base plugin ID.
   */
  public function __construct($base_plugin_id) {
    $this->basePluginId = $base_plugin_id;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static($base_plugin_id);
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $blocks = array(
      'utexas_flipcard_block_first' => t('Flipcard Block: First'),
      'utexas_flipcard_block_second' => t('Flipcard Block: Second'),
    );
    foreach ($blocks as $block_id => $block_label) {
      $this->derivatives[$block_id] = $base_plugin_definition;
      $this->derivatives[$block_id]['admin_label'] = $block_label;
    }
    return $this->derivatives;
  }

}
