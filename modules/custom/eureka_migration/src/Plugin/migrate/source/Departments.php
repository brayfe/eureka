<?php

namespace Drupal\eureka_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Defines migration for Departments Terms.
 *
 * @MigrateSource(
 *   id = "departments"
 * )
 */
class Departments extends SqlBase {

  private $termWeight = 0;

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('departments', 'd');
    $query->fields('d', ['department_id', 'name', 'college_id', 'sort'])
      ->orderBy('college_id', 'ASC')
      ->orderBy('sort', 'ASC');

    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'department_id' => $this->t('Department ID'),
      'name' => $this->t('Department Name'),
      'college_id' => $this->t('College of Department'),
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'department_id' => [
        'type' => 'integer',
        'alias' => 'd',
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
