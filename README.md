# Assets Autoversioning plugin for Craft CMS 3.x

![Icon](resources/autoversioning.png)

A really basic Twig extension for CraftCMS that helps you cache-bust your assets.

## Background

To force the browser to download the new asset file after a update, the plugin allows you to add a Twig function, which adds the build number or the filemtime to the filename.

## Requirements

 * Craft CMS >= 3.0.0

## Installation
### Project

Open your terminal and go to your Craft project:

``` shell
cd /path/to/project
composer require codemonauts/craft-asset-autoversioning
```

In the control panel, go to Settings → Plugins and click the “install” button for *Assets Autoversioning*.

### CI/CD Pipeine
The build number which gets added to the asset URL is read from a file called `build.txt` which must exist in your project folder. Use for example something like this in your deployment script (Example is for CodeShip):

``` shell
echo -n "${CI_BUILD_NUMBER}" > build.txt
```

If the file doesn't exists, the filemtime of the asset is used.

### Webserver

Because the clients will from now on start to request files like `/css/styles.12345678.css` we need to tell the webserver how to rewrite these URLs so that the original `/css/styles.css` will get served.

**Apache** 

``` apacheconfig
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*?\/)*?([a-z\.\-]+)(\d+)\.(bmp|css|cur|gif|ico|jpe?g|js|png|svgz?|webp|webmanifest)$ $1$2$4 [L]
</IfModule>
```

**NGINX**

``` nginx
location ~* (.+)\.(?:\d+)\.(js|css|png|jpg|jpeg|gif|webp)$ {
  try_files $uri $1.$2;
}
```

## Usage

Use the new Twig function ```versioning()``` in your template. For example in pug:

``` twig
<link rel="stylesheet" href="{{ versioning('/css/styles.css')}}">
``` 

will result in something like this:

``` html
<link rel="stylesheet" href="/css/styles.12345678.css">
```

With ❤ by [codemonauts](https://codemonauts.com)
