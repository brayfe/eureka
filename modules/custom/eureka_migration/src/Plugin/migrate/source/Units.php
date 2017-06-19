<?php

namespace Drupal\eureka_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;

/**
 * Defines migration for Institutions Terms.
 *
 * @MigrateSource(
 *   id = "units"
 * )
 */
class Units extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('institutions', 'i');
    $query->fields('i', ['institution_id', 'name', 'description', 'website']);

    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'institution_id' => $this->t('College ID'),
      'name' => $this->t('Institution Name'),
      'description' => $this->t('Description'),
      'website' => $this->t('Website'),
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'institution_id' => [
        'type' => 'integer',
        'alias' => 'i',
      ],
    ];
  }

}
