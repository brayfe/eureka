<?php

namespace Drupal\eureka_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Defines migration for Countries Terms.
 *
 * @MigrateSource(
 *   id = "countries"
 * )
 */
class Countries extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('alpha_countries', 'ac');
    $query->fields('ac', ['country_id', 'country_name', 'code']);

    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'country_id' => $this->t('Country ID'),
      'country_name' => $this->t('Country Name'),
      'code' => $this->t('Country Code'),
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'country_id' => [
        'type' => 'integer',
        'alias' => 'ac',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    $cid = $row->getSourceProperty('country_id');
    if ($cid == 33 || $cid == 11) {
      return FALSE;
    }

    return parent::prepareRow($row);
  }

}
