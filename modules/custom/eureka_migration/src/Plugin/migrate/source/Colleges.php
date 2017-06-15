<?php

namespace Drupal\eureka_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Defines migration for Colleges Terms.
 *
 * @MigrateSource(
 *   id = "colleges"
 * )
 */
class Colleges extends SqlBase {

  private $termWeight = 0;

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('colleges', 'c');
    $query->fields('c', ['college_id', 'name', 'sort'])
      ->orderBy('sort', 'ASC');

    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'name' => $this->t('College Name'),
      'college_id' => $this->t('College ID'),
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'college_id' => [
        'type' => 'integer',
        'alias' => 'c',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    $row->setDestinationProperty('weight', $this->termWeight);
    $this->termWeight++;

    return parent::prepareRow($row);
  }

}
