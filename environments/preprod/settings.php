<?php

// phpcs:ignoreFile

/**
 * @file
 * Drupal site-specific configuration file.
 */

/**
 * Database settings:
 */
$databases['default']['default'] = array (
  'database' => '',
  'username' => '',
  'password' => '',
  'prefix' => '',
  'host' => '',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
  'init_commands' => [
    'isolation_level' => 'SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED',
  ],
);

/**
 * Deployment identifier.
 *
 * Drupal's dependency injection container will be automatically invalidated and
 * rebuilt when the Drupal core version changes. When updating contributed or
 * custom code that changes the container, changing this identifier will also
 * allow the container to be invalidated as soon as code is deployed.
 */
# $settings['deployment_identifier'] = \Drupal::VERSION;

/**
 * Access control for update.php script.
 */
$settings['update_free_access'] = FALSE;

/**
 * Reverse Proxy Configuration:
 */
// $settings['reverse_proxy'] = TRUE;

/**
 * Specify every reverse proxy IP address in your environment.
 * This setting is required if $settings['reverse_proxy'] is TRUE.
 */
// $settings['reverse_proxy_addresses'] = [''];

/**
 * Load services definition file.
 */
$settings['container_yamls'][] = __DIR__ . '/services.yml';

/**
 * Trusted host configuration.
 */
$settings['trusted_host_patterns'][] = '^test\.my-site\.fr';
$settings['trusted_host_patterns'][] = '^test\.back\.my-site\.fr';

/**
 * The default list of directories that will be ignored by Drupal's file API.
 */
$settings['file_scan_ignore_directories'] = [
  'node_modules',
  'bower_components',
];

/**
 * Redis configs.
 */

// $settings['redis.connection']['interface'] = 'PhpRedis';
// $settings['redis.connection']['host'] = '';
// $settings['redis.connection']['port'] = '';
// $settings['redis.connection']['base'] = 0;
// $settings['cache']['default'] = 'cache.backend.redis';
// $settings['cache_prefix'] = '';
// $settings['redis_compress_length'] = 100;
// $settings['redis_compress_level'] = 1;
// $settings['container_yamls'][] = 'modules/contrib/redis/redis.services.yml';
// $settings['container_yamls'][] = 'modules/contrib/redis/example.services.yml';

$settings['container_yamls'][] = __DIR__ . '/services.yml';
/**
 * Elastic search connector.
 */
// $config['elasticsearch_connector.cluster.default']['url'] = '';

/**
 * Set split config settings.
 */
$config["config_split.config_split.local"]["status"] = FALSE;
$config["config_split.config_split.dev"]["status"] = FALSE;
$config["config_split.config_split.staging"]["status"] = FALSE;
$config["config_split.config_split.preprod"]["status"] = TRUE;
$config["config_split.config_split.prod"]["status"] = FALSE;

/**
 * Reverse proxy settings.
 */
// $settings['reverse_proxy'] = TRUE;
// $settings['reverse_proxy_addresses'] = [$_SERVER['REMOTE_ADDR']];

