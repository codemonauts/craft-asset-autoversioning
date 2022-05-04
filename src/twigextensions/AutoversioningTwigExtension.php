<?php

namespace codemonauts\autoversioning\twigextensions;

use codemonauts\autoversioning\Autoversioning;
use craft\helpers\App;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AutoversioningTwigExtension extends AbstractExtension
{
    private ?string $_buildId = null;

    public function getFunctions(): array
    {
        return [
            new TwigFunction('versioning', [$this, 'versioningFile']),
        ];
    }

    public function versioningFile($file): string
    {
        $settings = Autoversioning::$settings;

        if (!$settings->active) {
            return $file;
        }

        $fullPath = \Craft::getAlias('@webroot') . $file;

        if (!str_starts_with($file, '/') || !file_exists($fullPath)) {
            return $file;
        }

        if ($this->_buildId === null) {
            $ciFile = App::parseEnv($settings->ciFile);
            if ($ciFile === '' || !file_exists($ciFile)) {
                $this->_buildId = filemtime($fullPath);
            } else {
                $this->_buildId = trim(file_get_contents($ciFile));
            }
        }

        if ($settings->useQueryParam) {
            return $file . '?v=' . $this->_buildId;
        }

        return preg_replace('{\\.([^./]+)$}', ".$this->_buildId.\$1", $file);
    }
}
