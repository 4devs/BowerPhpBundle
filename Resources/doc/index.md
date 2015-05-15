Getting Started With The BowerPhp Bundle
===============================

## Installation and usage

Installation and usage are easy:

1. Download the bundle using composer
2. Enable the bundle
3. Use the bundle
4. Configure the bundle
5. Add your script/style to assetic if needed
6. Add a command to capifony if needed

### Step 1: Download the BowerPhp bundle using composer

Add the BowerPhp bundle in your composer.json:

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

Run the command `php bin/console fdevs:bower-php:init -p 'package' -a 'username'`.

Then you can add any package via `php bin/console fdevs:bower-php:install -S jquery`.

You can see the all available CLI commands under `fdevs:bower-php` namespace.


### Step 4: Configure the bundle

``` yaml
f_devs_bower_php:
    cache_dir:            '%kernel.cache_dir%/bowerphp'
    install_dir:          '%kernel.root_dir%/../web/components'
    bower_path:           '%kernel.root_dir%/config/bower.json'
    github_token:         null
```

### Step 5: Add your script/style to assetic if needed

``` yaml
# Assetic Configuration
assetic:
    assets:
    #....
        jquery_js:
            inputs:
                - 'components/jquery/dist/jquery.js'
            output: 'js/jquery.js'
```

### Step 6: Add a command to capifony if needed

``` ruby
#config/deploy.rb
namespace :bower do
    task :install do
        desc "bower Install"
        run "cd #{latest_release} && #{php_bin} #{symfony_console} fdevs:bower-php:install --env=#{symfony_env_prod}"
    end
end

before 'symfony:assetic:dump', 'bower:install'
```
