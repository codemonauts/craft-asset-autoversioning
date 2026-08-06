<?php

namespace codemonauts\autoversioning\twigextensions;

use codemonauts\autoversioning\Autoversioning;
use craft\helpers\App;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AutoversioningTwigExtension extends AbstractExtension
{
    private ?string $_ciBuildId = null;

    private bool $_ciResolved = false;

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

        $buildId = $this->getBuildId($fullPath);

        if ($settings->useQueryParam) {
            $separator = str_contains($file, '?') ? '&' : '?';
            return $file . $separator . 'v=' . $buildId;
        }

        return preg_replace('{\\.([^./]+)$}', ".$buildId.\$1", $file);
    }

    /**
     * Returns the version ID for a file.
     *
     * A configured CI build ID is global for the whole deployment, so it is
     * resolved once and reused. Without it, each file falls back to its own
     * modification time — which must be looked up per file, not cached across
     * files (see issue #4).
     */
    private function getBuildId(string $fullPath): string
    {
        if (!$this->_ciResolved) {
            $ciFile = App::parseEnv(Autoversioning::$settings->ciFile);
            if (is_string($ciFile) && $ciFile !== '' && is_file($ciFile)) {
                $this->_ciBuildId = trim((string)file_get_contents($ciFile));
            }
            $this->_ciResolved = true;
        }

        return $this->_ciBuildId ?? (string)filemtime($fullPath);
    }
}
