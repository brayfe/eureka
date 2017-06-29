<?php

namespace Drupal\eureka_migration\Plugin\migrate\process;

use Drupal\Component\Utility\UrlHelper;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * This plugin converts a url string to url format.
 *
 * @MigrateProcessPlugin(
 *   id = "create_url"
 * )
 */
class CreateUrl extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    // If value is null or already a valid url, then do nothing.
    if (is_null($value) || UrlHelper::isValid($value, TRUE)) {
      return $value;
    }
    // Determine if this is a valid url ending.
    if ($this->checkEnding($value)) {
      return 'http://' . $value;
    }

    // An invalid url should be ignored.
    return '';
  }

  /**
   * Function checkEnding.
   *
   * Confirms the given value has a valid url ending.
   *
   * @param string $value
   *   The string to validate.
   *
   * @return bool
   *   Boolean indicating if $value has a valid url ending.
   */
  public function checkEnding(string $value) {
    $valid_endings = [
      '.com',
      '.net',
      '.org',
      '.edu',
    ];

    foreach ($valid_endings as $ending) {
      if (strpos($value, $ending) !== FALSE) {
        return TRUE;
      }
    }

    return FALSE;
  }

}
