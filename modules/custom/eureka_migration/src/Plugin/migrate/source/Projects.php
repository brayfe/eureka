<?php

namespace Drupal\eureka_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Defines migration for Projects.
 *
 * @MigrateSource(
 *   id = "projects"
 * )
 */
class Projects extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('projects', 'p');
    $query->leftJoin('project_tags', 'pt', 'p.project_id = pt.project_id');
    $query->fields('p', [
      'faculty_id',
      'creator_id',
      'project_id',
      'name',
      'close_date',
      'description',
      'qualifications',
      'timeline',
      'duties',
      'website',
      'contact_name',
      'contact_email',
      'contact_phone',
      'created',
      'modified',
    ]);
    $query->addExpression('GROUP_CONCAT(DISTINCT pt.tag_id)', 'tag_list');
    $query->groupBy('p.faculty_id');
    $query->groupBy('p.creator_id');
    $query->groupBy('p.project_id');
    $query->groupBy('p.name');
    $query->groupBy('close_date');
    $query->groupBy('description');
    $query->groupBy('qualifications');
    $query->groupBy('timeline');
    $query->groupBy('duties');
    $query->groupBy('website');
    $query->groupBy('contact_name');
    $query->groupBy('contact_email');
    $query->groupBy('contact_phone');
    $query->groupBy('created');
    $query->groupBy('modified');

    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'faculty_id' => $this->t('Faculty ID'),
      'creator_id' => $this->t('Creator ID'),
      'project_id' => $this->t('Project ID'),
      'name' => $this->t('Project Name'),
      'close_date' => $this->t('Close Date'),
      'description' => $this->t('Project Description'),
      'qualifications' => $this->t('Project Qualifications'),
      'timeline' => $this->t('Project Timeline'),
      'duties' => $this->t('Project Duties'),
      'website' => $this->t('Project Website'),
      'contact_name' => $this->t('Contact Name'),
      'contact_email' => $this->t('Contact Email'),
      'contact_phone' => $this->t('Contact Phone Number'),
      'created' => $this->t('Create Date'),
      'modified' => $this->t('Modified Date'),
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'project_id' => [
        'type' => 'integer',
        'alias' => 'p',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    // Converty Faculty ID to User ID.
    $fid = $row->getSourceProperty('faculty_id');
    $query = $this->select('faculty', 'f');
    $query->fields('f', ['user_id'])
      ->condition('faculty_id', $fid);
    $uid = $query->execute()->fetchField();

    $row->setSourceProperty('project_lead', $uid);

    return parent::prepareRow($row);
  }

}
