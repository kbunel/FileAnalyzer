FileAnalyzer is a tool to get information from files in your Symfony project

Installation
============

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
$ composer require kbunel/file-analyzer --dev
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require kbunel/file-analyzer --dev
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            // ...
            new FileAnalyzer\FileAnalyzerBundle();
        );

        // ...
    }

    // ...
}
```

FileAnalyzer configuration
============

After running the command, there is many chances that some files haven't been identified.

To get the list of unidentified files, run: after running the command

```console
php kbunel:app:analyze --kind=unknown_kind
```

This will output files that doesnt have been identified, you can specify add a new kind and get them from the path content, for example, if services from the folder `src\AppBundle\Service` has not been identified, you can add the configuration below and the FileAnalyzer will check the path to add it if it hasnt been found before. You can add as many as you want.

```yaml
file_analyzer:
    from_path:
        - { kind: 'service', in_path: 'Service' }
        - { kind: 'model', in_path: 'Model' }
```

Command
============

To analyze your files in your Symfony project, run:

```console
$ php bin/console kbunel:app:analyze
```

Available options
----------------------------------

##### Specify path to analyze:

```console
$ php bin/console kbunel:app:analyze src/Controllers
```

##### Get path files with a specific kind:

```console
$ php bin/console kbunel:app:analyze --kind=unknown_kind
```
