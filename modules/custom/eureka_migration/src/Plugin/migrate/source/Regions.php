<?php

namespace Drupal\eureka_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;

/**
 * Defines migration for Regions Terms.
 *
 * @MigrateSource(
 *   id = "regions"
 * )
 */
class Regions extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('regions', 'r');
    $query->fields('r', ['region_id', 'name']);

    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'region_id' => $this->t('Region ID'),
      'name' => $this->t('Region Name'),
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'region_id' => [
        'type' => 'integer',
        'alias' => 'r',
      ],
    ];
  }

}
