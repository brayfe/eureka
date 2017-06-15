<?php

namespace Drupal\eureka_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Defines migration for Users.
 *
 * @MigrateSource(
 *   id = "users"
 * )
 */
class Users extends SqlBase {

  protected $oldCountries = [
    15,
    16,
    22,
    42,
    54,
    60,
    68,
    88,
    107,
    119,
    121,
    123,
    127,
    156,
    166,
    169,
    182,
    185,
    219,
    221,
    249,
    256,
    258,
  ];

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('users', 'u');
    $query->leftJoin('faculty', 'f', 'u.user_id = f.user_id');
    $query->leftJoin('faculty_tags', 'ft', 'ft.faculty_id = f.faculty_id');
    $query->fields('u', ['user_id', 'username']);
    $query->fields('f', [
      'faculty_id',
      'user_id',
      'first_name',
      'last_name',
      'research_interests',
      'website',
      'is_active',
      'created',
      'modified',
    ]);
    $query->addExpression('GROUP_CONCAT(DISTINCT ft.tag_id)', 'tag_list');
    $query->groupBy('u.user_id');
    $query->groupBy('u.username');
    $query->groupBy('faculty_id');
    $query->groupBy('f.user_id');
    $query->groupBy('first_name');
    $query->groupBy('last_name');
    $query->groupBy('research_interests');
    $query->groupBy('website');
    $query->groupBy('is_active');
    $query->groupBy('created');
    $query->groupBy('modified');

    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'user_id' => $this->t('User ID'),
      'username' => $this->t('Faculty EID'),
      'faculty_id' => $this->t('Faculty ID'),
      'first_name' => $this->t('Faculty First Name'),
      'last_name' => $this->t('Faculty Last Name'),
      'research_interests' => $this->t('Research Interests'),
      'website' => $this->t('Research Website'),
      'is_active' => $this->t('Is Faculty Active'),
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
      'user_id' => [
        'type' => 'integer',
        'alias' => 'u',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    // If user is Faculty.
    if ($row->getSourceProperty('faculty_id')) {
      $fid = $row->getSourceProperty('faculty_id');
      // Set user role to Faculty.
      $row->setDestinationProperty('roles', 'faculty');
      // Populate Affiliated Departments.
      $row->setSourceProperty('department_id', $this->getDepartments($fid));
      // Populate Projects.
      $row->setSourceProperty('project_id', $this->getProjects($fid));
      // Populate Regions of Academic Interest.
      $row->setSourceProperty('region_id', $this->getRegions($fid));
      // Populate Countries of Academic Interest.
      $row->setDestinationProperty('field_countries_acad_interest', $this->getCountries($fid));
      // Populate Research Units.
      $row->setSourceProperty('institution_id', $this->getUnits($fid));
    }

    $row->setSourceProperty('email_host', '@eid.utexas.edu');

    return parent::prepareRow($row);
  }

  /**
   * Return Departments associated with Faculty member.
   *
   * @param int $faculty_id
   *   The id of the Faculty member for which you want departments.
   *
   * @return string
   *   A concatenated list of department ids.
   */
  private function getDepartments($faculty_id) {
    $query = $this->select('faculty_departments', 'fd');
    $query->fields('fd', ['faculty_id'])
      ->condition('faculty_id', $faculty_id)
      ->addExpression('GROUP_CONCAT(DISTINCT fd.department_id)', 'depts');
    $query->groupBy('faculty_id');

    return $query->execute()->fetchField(1);
  }

  /**
   * Return Projects associated with Faculty member.
   *
   * @param int $faculty_id
   *   The id of the Faculty member for which you want projects.
   *
   * @return string
   *   A concatenated list of project ids.
   */
  private function getProjects($faculty_id) {
    $query = $this->select('projects', 'p');
    $query->fields('p', ['faculty_id'])
      ->condition('faculty_id', $faculty_id)
      ->addExpression('GROUP_CONCAT(DISTINCT p.project_id)', 'projects');
    $query->groupBy('faculty_id');

    return $query->execute()->fetchField(1);
  }

  /**
   * Return Regions associated with Faculty member.
   *
   * @param int $faculty_id
   *   The id of the Faculty member for which you want regions.
   *
   * @return string
   *   A concatenated list of region ids.
   */
  private function getRegions($faculty_id) {
    $query = $this->select('faculty_regions', 'fr');
    $query->fields('fr', ['faculty_id'])
      ->condition('faculty_id', $faculty_id)
      ->addExpression('GROUP_CONCAT(DISTINCT fr.region_id)', 'regions');
    $query->groupBy('faculty_id');

    return $query->execute()->fetchField(1);
  }

  /**
   * Return Countries associated with Faculty member.
   *
   * @param int $faculty_id
   *   The id of the Faculty member for which you want countries.
   *
   * @return array
   *   An array of tids corresponding to the already migrated country codes.
   */
  private function getCountries($faculty_id) {
    $countries = [];

    $query = $this->select('faculty_countries', 'fc');
    $query->fields('fc', ['country_id'])
      ->condition('faculty_id', $faculty_id);
    $result = $query->execute()->fetchAllKeyed();

    // Loop through countries to ensure they
    // Aren't part of the skipped countries.
    foreach ($result as $cid => $value) {
      if (!in_array($cid, $this->oldCountries)) {
        // Check if $cid is one of the duplicate countries.
        // If so, reassign to original country_id.
        if ($cid == 11) {
          $cid = 10;
        }
        elseif ($cid == 33) {
          $cid = 32;
        }

        // Query to find the migrated term.
        $query = \Drupal::database()->select('migrate_map_countries', 'mmc');
        $query->fields('mmc', ['destid1'])
          ->condition('sourceid1', $cid);
        $tid = $query->execute()->fetchField();

        $countries[] = $tid;
      }
    }

    return $countries;
  }

  /**
   * Return Research Units associated with Faculty member.
   *
   * @param int $faculty_id
   *   The id of the Faculty member for which you want units.
   *
   * @return string
   *   A concatenated list of unit ids.
   */
  private function getUnits($faculty_id) {
    $query = $this->select('faculty_institutions', 'fi');
    $query->fields('fi', ['faculty_id'])
      ->condition('faculty_id', $faculty_id)
      ->addExpression('GROUP_CONCAT(DISTINCT fi.institution_id)', 'units');
    $query->groupBy('faculty_id');

    return $query->execute()->fetchField(1);
  }

}
