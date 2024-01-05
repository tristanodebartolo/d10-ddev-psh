# Start projet Drupal with Ddev and platform.sh

## Prerequisites

- Docker
- ddev

## Init projet

Before launching the project, change config elements in `.ddev/config.yaml`

- Name of the site

```yml
name: my-site
```

- Drupal version

```yml
type: drupal10
```

- the additional_hostnames host

```yml
additional_hostnames:
  - back.my-site
```

- the web_environment

```yml
web_environment:
  - ENV_ID=local
```

## Start ddev

```bach
ddev start
```

## Install dependencies

```bach
composer install
```

## Essential modules

This project contains modules essential for the proper functioning of Drupal

```
"drupal/allowed_formats": "^3.0",
"drupal/config_split": "^2.0",
"drupal/crop": "^2.3",
"drupal/ds": "^3.16",
"drupal/editor_advanced_link": "^2.2",
"drupal/image_widget_crop": "^2.4",
"drupal/inline_entity_form": "^3.0@RC",
"drupal/layout_options": "^1.4",
"drupal/layout_paragraphs": "^2.0",
"drupal/link_attributes": "^2.1",
"drupal/media_library_edit": "^3.0",
"drupal/paragraphs": "^1.16",
"drupal/pathauto": "^1.12",
"drupal/redirect": "^1.9",
"drupal/remove_http_headers": "^2.1",
"drupal/token": "^1.13",
"drupal/twig_tweak": "^3.2",
"drupal/ui_patterns": "^1.7",
"drupal/ui_patterns_field_formatters": "^1.8",
"drupal/ui_patterns_settings": "^2.1",
"drupal/ui_skins": "^1.0@alpha",
"drupal/ui_suite_dsfr": "^1.0@beta",
"drupal/viewsreference": "^2.0@beta",
"drush/drush": "^12.4",
"gouvernementfr/dsfr": "^1.9",
"platformsh/config-reader": "^2.4"
```

### Update dependencies

👀 If this is your first time launching the site, please update Drupal core and its dependencies

```bach
composer update
```

### Dev Modules

```
"drupal/devel": "*",
"kint-php/kint": "^5.1"
```


## The ddev environment

If you want to know more about your ddev project

```
ddev describe
```

```
┌────────────────────────────────────────────────────────────────────────────────────┐
│ Project: my-site ~/Documents/Workspace/Projects/my-site                            │
│ https://my-site.ddev.site                                                          │
│ Docker provider: docker 20.10.21                                                   │
│ Router: traditional                                                                │
├────────────┬──────┬─────────────────────────────────────────┬──────────────────────┤
│ SERVICE    │ STAT │ URL/PORT                                │ INFO                 │
├────────────┼──────┼─────────────────────────────────────────┼──────────────────────┤
│ web        │ OK   │ https://my-site.ddev.site               │ drupal10 PHP8.1      │
│            │      │ InDocker: web:443,80,8025               │ nginx-fpm            │
│            │      │ Host: 127.0.0.1:51032,51033             │ docroot:'web'        │
│            │      │                                         │ Mutagen enabled (ok) │
│            │      │                                         │ NodeJS:18            │
├────────────┼──────┼─────────────────────────────────────────┼──────────────────────┤
│ db         │ OK   │ InDocker: db:3306                       │ mariadb:10.4         │
│            │      │ Host: 127.0.0.1:51031                   │ User/Pass: 'db/db'   │
│            │      │                                         │ or 'root/root'       │
├────────────┼──────┼─────────────────────────────────────────┼──────────────────────┤
│ adminer    │ OK   │ https://my-site.ddev.site:9101          │                      │
│            │      │ InDocker: adminer:8080                  │                      │
├────────────┼──────┼─────────────────────────────────────────┼──────────────────────┤
│ PHPMyAdmin │ OK   │ https://my-site.ddev.site:8037          │                      │
│            │      │ InDocker: dba:80,80                     │                      │
│            │      │ `ddev launch -p`                        │                      │
├────────────┼──────┼─────────────────────────────────────────┼──────────────────────┤
│ Mailhog    │      │ MailHog: https://my-site.ddev.site:8026 │                      │
│            │      │ `ddev launch -m`                        │                      │
├────────────┼──────┼─────────────────────────────────────────┼──────────────────────┤
│ All URLs   │      │ https://my-site.ddev.site,              │                      │
│            │      │ https://back.my-site.ddev.site,         │                      │
│            │      │ https://127.0.0.1:51032,                │                      │
│            │      │ http://my-site.ddev.site,               │                      │
│            │      │ http://back.my-site.ddev.site,          │                      │
│            │      │ http://127.0.0.1:51033                  │                      │
└────────────┴──────┴─────────────────────────────────────────┴──────────────────────┘
```

## Install your site with drush

### Default installation

```bath
ddev drush site:install -y
```
or
````
ddev drush site:install -y --account-name=admin --account-pass=admin --site-name=My-sites --site-mail=my-site@hello-world.com --account-mail=tristan@hello-world.com
````

Wait and Enjoy

```
 You are about to:
 * DROP all tables in your 'db' database.

 Do you want to continue? (yes/no) [yes]:
 > yes

 [notice] Starting Drupal installation. This takes a while.
 [notice] Performed install task: install_select_language
 [notice] Performed install task: install_select_profile
 [notice] Performed install task: install_load_profile
 [notice] Performed install task: install_verify_requirements
 [notice] Performed install task: install_verify_database_ready
 [notice] Performed install task: install_base_system
 [notice] Performed install task: install_bootstrap_full
 [notice] Performed install task: install_profile_modules
 [notice] Performed install task: install_profile_themes
 [notice] Performed install task: install_install_profile
 [notice] Performed install task: install_configure_form
 [notice] Performed install task: install_finished
 [success] Installation complete.  User name: admin  User password: csry8x7Cbg
```



