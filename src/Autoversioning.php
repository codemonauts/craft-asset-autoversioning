<?php

declare(strict_types=1);

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
     * @inheritDoc
     */
    public bool $hasCpSettings = true;

    /**
     * @inheritDoc
     */
    public string $schemaVersion = '0.1';

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();

        if (!Craft::$app->getRequest()->getIsConsoleRequest()) {
            Craft::$app->view->registerTwigExtension(new AutoversioningTwigExtension($this->getSettings()));
        }
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
    public function getSettings(): Settings
    {
        /** @var Settings */
        return parent::getSettings();
    }

    /**
     * @inheritDoc
     */
    protected function settingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate('craft3-assets-autoversioning/settings', [
            'settings' => $this->getSettings(),
        ]);
    }
}
