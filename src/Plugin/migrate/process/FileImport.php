<?php

namespace Drupal\doghouse_content_migrate\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;
use Drupal\migrate\MigrateSkipProcessException;

/**
 * Import a file as a side-effect of a migration.
 *
 * You can optionally set destination_dir to change where the file will be saved
 * to. Valid schemas are public:// or private://.
 *
 * @MigrateProcessPlugin(
 *   id = "file_import"
 * )
 */
class FileImport extends ProcessPluginBase {

  /**
   * Where the file will be saved.
   *
   * @var string
   */
  protected $destinationDir = 'public://';

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    // Override destination dir.
    if (isset($this->configuration['destination_dir'])) {
      $this->destinationDir = $this->configuration['destination_dir'];
    }

    // Skip this item if there's no URL.
    if (empty($value)) {
      throw new MigrateSkipProcessException();
    }

    // Ensure destination exists.
    if (!is_dir($this->destinationDir)) {
      drupal_mkdir($this->destinationDir);
    }

    // Build the destination.
    $destination = $this->destinationDir . '/' . basename($value);

    // Save the file.
    $file = system_retrieve_file($value, $destination, TRUE, FILE_EXISTS_REPLACE);

    // If no file, skip.
    if (empty($file)) {
      throw new MigrateSkipProcessException();
    }

    // Return fid.
    return $file->id();
  }

}