<?php
/**
 * Craft3 Assets Autoversioning plugin for Craft CMS 3.x
 *
 * A Twig extension for CraftCMS (Craft3.x) that helps you cache-bust your assets
 *
 * @link      https://www.codemonauts.com
 * @copyright Copyright (c) 2018 Codemonauts
 */

namespace codemonauts\autoversioning;

use codemonauts\autoversioning\twigextensions\AutoversioningTwigExtension;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;

use yii\base\Event;

/**
 * Class Craft3AssetsAutoversioning
 *
 * @author    Codemonauts
 * @package   Craft3AssetsAutoversioning
 * @since     0.1
 *
 */
class Autoversioning extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var Autoversioning
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '0.1';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Craft::$app->view->registerTwigExtension(new AutoversioningTwigExtension());

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                }
            }
        );

        Craft::info(
            Craft::t(
                'craft3-assets-autoversioning',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

}
