Work in Progress.

Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require fvchs/sonata-ajax-block-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

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
        $bundles = array(
            // ...

            new Fvchs\SonataAjaxBlockBundle\FvchsSonataAjaxBlockBundle(),
        );

        // ...
    }

    // ...
}
```

# Configuration

* Replace the sonata.block.renderer to enable ajax loading

        # app/config/sonata_block.yml
        
        services:
            sonata.block.renderer: '@fvchs.sonata_ajax_block.renderer'
            
* Add this to your routing configuration

        #  routing.yml
        fvchs_sonata_ajax_block:
            resource: "@FvchsSonataAjaxBlockBundle/Resources/config/routing.yml"
            prefix:   /
            
            
* Mark Blocks with the ajax attribute

        # app/config/sonata_admin.yml
        
        sonata_admin:
            dashboard:
                blocks:
                    - { type: my.block.one, settings: { attr: [ajax: true] } }
                    - { type: my.block.two }
                    
    The first block in this example will be lazy loaded via ajax. The second block will stay static.
    