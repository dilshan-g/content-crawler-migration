<?php

namespace Drupal\doghouse_content_migrate\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Parse the body and apply replacements.
 *
 * @MigrateProcessPlugin(
 *   id = "body_parser"
 * )
 */
class BodyParser extends ProcessPluginBase {


  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    $value = $this->replaceImg($value, '/sites/default/files/');
    return $value;
  }

  public function replaceImg($content, $path) {
    preg_match_all('@src="([^"]+)"@' , $content, $match);
    $src = array_pop($match);
    foreach ($src as $img_src) {
      $content = str_replace($img_src, $path . basename($img_src), $content);
    }
    return $content;
  }

}