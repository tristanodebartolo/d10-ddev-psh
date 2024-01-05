<?php

/**
 * @file
 * Set environment indicator amd drupal site-specific configuration file.
 */

/**
 * Location of the site configuration files.
 */
$settings['config_sync_directory'] = '../config/sync';

/**
 * Allow test modules and themes to be installed.
 *
 * Drupal ignores test modules and themes by default for performance reasons.
 * During development it can be useful to install test extensions for debugging
 * purposes.
 */
$settings['extension_discovery_scan_tests'] = FALSE;

/**
 * Salt for one-time login links, cancel links, form tokens, etc.
 */
$settings['hash_salt'] = 'OMntQftBtHUouTItoImblNSFJgbcHRgzShOeFxgNoxlHHIHcoJbCcCHZOANJszeR';

/**
 * Access control for update.php script.
 */
$settings['update_free_access'] = FALSE;

/**
 * Default environment name.
 */
$environment = 'local';

/**
 * Detect current environment name.
 */
switch (gethostname()) {

  // Dev environment.
  case 'my-host-dev-1':
    $environment = 'dev';
    break;

  // Prod environment.
  case 'my-host-staging-1':
  case 'my-host-staging-2':
    $environment = 'test';
    break;

  // ISOProd environment.
  case 'my-host-preprod-1':
  case 'my-host-preprod-2':
    $environment = 'preprod';
    break;

  // Prod environment.
  case 'my-host-prod-1':
  case 'my-host-prod-2':
    $environment = 'prod';
    break;

  default:
    $environment = 'local';
    break;
}
/**
 * Load common services definition file.
 */
$settings['container_yamls'][] = __DIR__ . "/$environment/services.yml";

/**
 * Include settings.
 */
include __DIR__ . "/$environment/settings.php";

/**
 * Current environment name.
 */
$settings['current_environment_name'] = $environment;

/**
 * Set different memory limit for interfaces.
 */
$limit = php_sapi_name() === 'cli' ? '1200M' : '256M';
ini_set('memory_limit', $limit);

/**
 * Private file path.
 *
 * A local file system path where private files will be stored. This directory
 * must be absolute, outside of the Drupal installation directory and not
 * accessible over the web.
 *
 * Note: Caches need to be cleared when this value is changed to make the
 * private:// stream wrapper available to the system.
 *
 * See https://www.drupal.org/documentation/modules/file for more information
 * about securing private files.
 */
$settings['file_private_path'] = '../private';

/**
 * Outsourcing twig cache files.
 */
// $settings['php_storage']['twig']['directory'] = 'sites/default/tmp';
// $settings['php_storage']['twig']['secret'] = $settings['hash_salt'];
