# Assets Autoversioning plugin for Craft CMS 3.x

A really basic Twig extension for CraftCMS (Craft3.x) that helps you cache-bust your assets

![Screenshot](resources/img/codemonauts-logo.png)

## Why?

To force the browser to download the new asset file after a update.

## Strategies

The plugin allows you to add a twigextention to your assets, which adds the build number or the filemtime to the filename.

## Requirements

This plugin requires Craft CMS 3.0.0 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell composer to load the plugin:

        composer require https://github.com/codemonauts/craft-asset-autoversioning

3. In the control panel, go to Settings → Plugins and click the “install” button for Craft3 Assets Autoversioning.

4. To use within your build process just save your build ID to a file named "build.txt" outside your document root.

## USAGE

1. User twigextension in your template
    
    Example : 
    
        link(rel='stylesheet' href="{{ versioning('/css/styles.css')}}")
        
    Will end as something like that
        
        <link rel="stylesheet" href="/css/styles.12345678.css">
        

## SERVER SETUP

Make sure the server knows how to handle those files. We need to add a simple rule to our server. 

Due to the fact, we actually want to serve our visitor the original "styles.css",

we just tell the server to ignore the version string, and just serve up the actual file.

So any requests that come in for styles.VERSIONNURMBER.css will cause the server to serve up the file styles.css.

For Apache, we do this: 

    <IfModule mod_rewrite.c>
        RewriteEngine On
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteRule ^(.*?\/)*?([a-z\.\-]+)(\d+)\.(bmp|css|cur|gif|ico|jpe?g|js|png|svgz?|webp|webmanifest)$ $1$2$4 [L]
    </IfModule>

And for Nginx we do this:

    location ~* (.+)\.(?:\d+)\.(js|css|png|jpg|jpeg|gif|webp)$ {
      try_files $uri $1.$2;
    }

            

Brought to you by [Codemonauts](https://codemonauts.com)
