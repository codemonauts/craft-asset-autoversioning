# Assets Autoversioning plugin for CraftCMS 3.x

A really basic Twig extension for CraftCMS that helps you cache-bust your assets.

![Screenshot](resources/img/codemonauts-logo.png)

## Why?

To force the browser to download the new asset file after a update.

## Strategies

The plugin allows you to add a twigextention to your assets, which adds the build number or the filemtime to the filename.

## Requirements

 * Craft CMS >= 3.0.0

## Installation
### Project

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell composer to load the plugin:

        composer require https://github.com/codemonauts/craft-asset-autoversioning

3. In the control panel, go to Settings → Plugins and click the “install” button for Craft3 Assets Autoversioning.

### CI/CD Pipeine
The build number which gets added to the asset URL is read from a file called `build.txt` which must exist in your project folder. Use for example something like this in your deployment script (Example is for CodeShip):

        echo -n "${CI_BUILD_NUMBER}" > build.txt
        
### Webserver

Because the clients will from now on start to request files like `/css/styles.12345678.css` we need to tell the webserver how to rewrite these URLs so that the original `/css/styles.css` will get served.

**Apache** 

    <IfModule mod_rewrite.c>
        RewriteEngine On
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteRule ^(.*?\/)*?([a-z\.\-]+)(\d+)\.(bmp|css|cur|gif|ico|jpe?g|js|png|svgz?|webp|webmanifest)$ $1$2$4 [L]
    </IfModule>

**NGINX**

    location ~* (.+)\.(?:\d+)\.(js|css|png|jpg|jpeg|gif|webp)$ {
      try_files $uri $1.$2;
    }


## USAGE

1. User twigextension in your template
    
    Example : 
    
        link(rel='stylesheet' href="{{ versioning('/css/styles.css')}}")
        
    Will result in something like this
        
        <link rel="stylesheet" href="/css/styles.12345678.css">
     

Brought to you by [Codemonauts](https://codemonauts.com)
