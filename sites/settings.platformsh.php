<?php
// Configure relationships.
/**
 * @file
 * Platform.sh settings.
 */

// Active mode debug only for dev env.
if (getenv('ENV_ID') == 'dev') {
  $settings['container_yamls'][] = $app_root . '/' . $site_path . '/../development.services.yml';
}else {
  $settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.yml';
}

$platformsh = new \Platformsh\ConfigReader\Config();

if (!isset($platformsh_subsite_id)) {
  $platformsh_subsite_id = 'database';
}

if (getenv('PLATFORM_RELATIONSHIPS')) {
  $relationships = json_decode(base64_decode(getenv('PLATFORM_RELATIONSHIPS')), TRUE);
  if (empty($databases['default']) && !empty($relationships)) {
    foreach ($relationships as $key => $relationship) {
      $drupal_key = ($key === $platformsh_subsite_id) ? 'default' : $key;
      foreach ($relationship as $instance) {
        if (empty($instance['scheme']) || ($instance['scheme'] !== 'mysql' && $instance['scheme'] !== 'pgsql')) {
          continue;
        }
        $database = [
          'driver' => $instance['scheme'],
          'database' => $instance['path'],
          'username' => $instance['username'],
          'password' => $instance['password'],
          'host' => $instance['host'],
          'port' => $instance['port'],
          'prefix' => '',

          // 4-byte UTF-8 support: this will work on new sites. Existing sites
          // may need to back up and convert their database tables before
          // including this configuration.
          // See https://www.drupal.org/node/2754539
          'charset' => 'utf8mb4',
          'collation' => 'utf8mb4_general_ci',
        ];
        if (!empty($instance['query']['compression'])) {
          $database['pdo'][PDO::MYSQL_ATTR_COMPRESS] = TRUE;
        }
        if (!empty($instance['query']['is_master'])) {
          $databases[$drupal_key]['default'] = $database;
        }
        else {
          $databases[$drupal_key]['slave'][] = $database;
        }
      }
    }
  }
}

// Configure private and temporary file paths.
if (getenv('PLATFORM_APP_DIR')) {
  if (!isset($conf['file_private_path'])) {
    $conf['file_private_path'] = getenv('PLATFORM_APP_DIR') . '/private/' . $platformsh_subsite_id;
  }
  if (!isset($conf['file_temporary_path'])) {
    $conf['file_temporary_path'] = getenv('PLATFORM_APP_DIR') . '/tmp/' . $platformsh_subsite_id;
  }
}

// Import variables prefixed with 'drupal:' into $conf.
if (getenv('PLATFORM_VARIABLES')) {
  $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), TRUE);

  $prefix_len = strlen('drupal:');
  $drupal_globals = array('cookie_domain', 'installed_profile', 'drupal_hash_salt', 'base_url');
  foreach ($variables as $name => $value) {
    if (substr($name, 0, $prefix_len) == 'drupal:') {
      $name = substr($name, $prefix_len);
      if (in_array($name, $drupal_globals)) {
        $GLOBALS[$name] = $value;
      }
      else {
        $conf[$name] = $value;
      }
    }
  }
}

// Set a default Drupal hash salt, based on a project-specific entropy value.
if (getenv('PLATFORM_PROJECT_ENTROPY') && empty($drupal_hash_salt)) {
  $drupal_hash_salt = getenv('PLATFORM_PROJECT_ENTROPY');
}

if (!$platformsh->inRuntime()) {
  return;
}

// Set redis configuration.
if ($platformsh->hasRelationship('redis') && !\Drupal\Core\Installer\InstallerKernel::installationAttempted() && extension_loaded('redis')) {
  $redis = $platformsh->credentials('redis');

  // Set Redis as the default backend for any cache bin not otherwise specified.
  $settings['cache']['default'] = 'cache.backend.redis';
  $settings['redis.connection']['host'] = $redis['host'];
  $settings['redis.connection']['port'] = $redis['port'];

  // Apply changes to the container configuration to better leverage Redis.
  // This includes using Redis for the lock and flood control systems, as well
  // as the cache tag checksum. Alternatively, copy the contents of that file
  // to your project-specific services.yml file, modify as appropriate, and
  // remove this line.
  $settings['container_yamls'][] = 'modules/contrib/redis/example.services.yml';

  // Allow the services to work before the Redis module itself is enabled.
  $settings['container_yamls'][] = 'modules/contrib/redis/redis.services.yml';

  // Manually add the classloader path, this is required for the container cache bin definition below
  // and allows to use it without the redis module being enabled.
  $class_loader->addPsr4('Drupal\\redis\\', 'modules/contrib/redis/src');

  // Use redis for container cache.
  // The container cache is used to load the container definition itself, and
  // thus any configuration stored in the container itself is not available
  // yet. These lines force the container cache to use Redis rather than the
  // default SQL cache.
  $settings['bootstrap_container_definition'] = [
    'parameters' => [],
    'services' => [
      'redis.factory' => [
        'class' => 'Drupal\redis\ClientFactory',
      ],
      'cache.backend.redis' => [
        'class' => 'Drupal\redis\Cache\CacheBackendFactory',
        'arguments' => ['@redis.factory', '@cache_tags_provider.container', '@serialization.phpserialize'],
      ],
      'cache.container' => [
        'class' => '\Drupal\redis\Cache\PhpRedis',
        'factory' => ['@cache.backend.redis', 'get'],
        'arguments' => ['container'],
      ],
      'cache_tags_provider.container' => [
        'class' => 'Drupal\redis\Cache\RedisCacheTagsChecksum',
        'arguments' => ['@redis.factory'],
      ],
      'serialization.phpserialize' => [
        'class' => 'Drupal\Component\Serialization\PhpSerialize',
      ],
    ],
  ];
}

$settings['trusted_host_patterns'] = ['.*'];

// Filesystem
$settings['file_private_path'] = '../private';

