<?php
/**
 * Craft3 Assets Autoversioning plugin for Craft CMS 3.x
 *
 * A Twig extension for CraftCMS (Craft3.x) that helps you cache-bust your assets
 *
 * @link      https://www.codemonauts.com
 * @copyright Copyright (c) 2018 Codemonauts
 */

namespace codemonauts\autoversioning\twigextensions;

use Craft;

/**
 * @author    Codemonauts
 * @package   Craft3AssetsAutoversioning
 * @since     0.1
 */
class AutoversioningTwigExtension extends \Twig_Extension
{
    // Public Methods
    // =========================================================================

    private $_buildId = null;


    public function getName()
    {
        return 'Auto-Versioning';
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('versioning', [$this, 'versioningFile']),
        ];
    }

    public function versioningFile($file)
    {
        if ($this->_buildId === null)
        {
            if (file_exists($_SERVER['DOCUMENT_ROOT'].'/../build.txt'))
            {
                $build = $this->_buildId = trim(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/../build.txt'));
            }
            else
            {
                $this->_buildId = false;
            }
        }

        if ($this->_buildId === false)
        {
            if (strpos($file, '/') !== 0 || !file_exists($_SERVER['DOCUMENT_ROOT'] . $file))
            {
                return $file;
            }

            $build = filemtime($_SERVER['DOCUMENT_ROOT'] . $file);
        }
        else
        {
            $build = $this->_buildId;
        }

        return preg_replace('{\\.([^./]+)$}', ".$build.\$1", $file);
    }

}
