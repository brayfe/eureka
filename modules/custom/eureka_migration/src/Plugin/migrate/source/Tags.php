<?php

namespace Drupal\eureka_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;

/**
 * Defines migration for Tags Terms.
 *
 * @MigrateSource(
 *   id = "tags"
 * )
 */
class Tags extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('tags', 't');
    $query->fields('t', ['tag_id', 'name']);

    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'tag_id' => $this->t('Tag ID'),
      'name' => $this->t('Tag Name'),
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'tag_id' => [
        'type' => 'integer',
        'alias' => 't',
      ],
    ];
  }

}
