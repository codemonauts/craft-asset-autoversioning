<?php

namespace codemonauts\autoversioning;

use codemonauts\autoversioning\models\Settings;
use codemonauts\autoversioning\twigextensions\AutoversioningTwigExtension;

use Craft;
use craft\base\Model;
use craft\base\Plugin;
use craft\helpers\UrlHelper;

class Autoversioning extends Plugin
{
    /**
     * @var Autoversioning
     */
    public static Autoversioning $plugin;

    /**
     * @var \codemonauts\autoversioning\models\Settings|null
     */
    public static ?Settings $settings;

    /**
     * @inheritDoc
     */
    public bool $hasCpSettings = true;

    /**
     * @var string
     */
    public string $schemaVersion = '0.1';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        self::$plugin = $this;

        self::$settings = self::$plugin->getSettings();

        // Register Twig extension
        Craft::$app->view->registerTwigExtension(new AutoversioningTwigExtension());
    }

    /**
     * @inheritDoc
     */
    public function afterInstall(): void
    {
        parent::afterInstall();

        if (Craft::$app->getRequest()->getIsConsoleRequest()) {
            return;
        }

        Craft::$app->getResponse()->redirect(
            UrlHelper::cpUrl('settings/plugins/craft3-assets-autoversioning')
        )->send();
    }

    /**
     * @inheritDoc
     */
    protected function createSettingsModel(): ?Model
    {
        return new Settings();
    }

    /**
     * @inheritDoc
     */
    protected function settingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate('craft3-assets-autoversioning/settings', [
                'settings' => $this->getSettings(),
            ]
        );
    }
}
