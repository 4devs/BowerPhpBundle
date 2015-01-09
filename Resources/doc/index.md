Getting Started With BowerPhp Bundle
===============================

## Installation and usage

Installation and usage is a quick:

1. Download bundle using composer
2. Enable the bundle
3. Use the bundle
4. Configure the bundle


### Step 1: Download Locale bundle using composer

Add BowerPhp bundle in your composer.json:

```json
{
    "require": {
        "fdevs/bower-php-bundle": "*"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update fdevs/bower-php-bundle
```

Composer will install the bundle to your project's `vendor/fdevs` directory.


### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new FDevs\BowerPhpBundle\FDevsBowerPhpBundle(),
    );
}
```

### Step 3: Use the bundle

run command `php bin/console fdevs:bower-php:init -p 'package' -a 'username'`

add your package `php bin/console fdevs:bower-php:install -S jquery`


### Step 4: Configure the bundle

``` yaml
f_devs_bower_php:
    cache_dir:            '%kernel.cache_dir%/bowerphp'
    install_dir:          '%kernel.root_dir%/../web/components'
    bower_path:           '%kernel.root_dir%/config/bower.json'
    github_token:         null
```
