<?php

declare(strict_types=1);

namespace codemonauts\autoversioning\twigextensions;

use codemonauts\autoversioning\models\Settings;
use Craft;
use craft\helpers\App;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AutoversioningTwigExtension extends AbstractExtension
{
    /**
     * @var string|null Cached CI build ID (global for the whole deployment).
     */
    private ?string $_ciBuildId = null;

    /**
     * @var bool Whether the CI build ID has been resolved yet this request.
     */
    private bool $_ciResolved = false;

    public function __construct(private readonly Settings $settings)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('versioning', [$this, 'versioningFile']),
        ];
    }

    public function versioningFile(string $file): string
    {
        if (!$this->settings->active) {
            return $file;
        }

        $fullPath = Craft::getAlias('@webroot') . $file;

        if (!str_starts_with($file, '/') || !file_exists($fullPath)) {
            return $file;
        }

        $buildId = $this->getBuildId($fullPath);

        if ($this->settings->useQueryParam) {
            $separator = str_contains($file, '?') ? '&' : '?';
            return $file . $separator . 'v=' . $buildId;
        }

        return preg_replace('{\.([^./]+)$}', ".$buildId.\$1", $file);
    }

    private function getBuildId(string $fullPath): string
    {
        if (!$this->_ciResolved) {
            $ciFile = App::parseEnv($this->settings->ciFile);
            if (is_string($ciFile) && $ciFile !== '' && is_file($ciFile)) {
                $this->_ciBuildId = trim((string)file_get_contents($ciFile));
            }
            $this->_ciResolved = true;
        }

        return $this->_ciBuildId ?? (string)filemtime($fullPath);
    }
}
