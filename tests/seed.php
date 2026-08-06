<?php

/**
 * Lab-Seeder für craft3-assets-autoversioning
 */

use craft\console\Controller;
use yii\console\ExitCode;

return function (Controller $c): int {
    $plugins = Craft::$app->getPlugins();
    $plugin = $plugins->getPlugin('craft3-assets-autoversioning');
    if (!$plugin) {
        $c->stderr("Plugin craft3-assets-autoversioning ist nicht installiert.\n");
        return ExitCode::UNSPECIFIED_ERROR;
    }

    if (!$plugins->savePluginSettings($plugin, [
        'active' => true,
        'ciFile' => '@root/build.txt',
        'useQueryParam' => false,
    ])) {
        $c->stderr('Failed to save plugin settings: ' . print_r($plugin->getSettings()->getErrors(), true) . "\n");
        return ExitCode::UNSPECIFIED_ERROR;
    }
    $c->stdout("Saved plugin settings\n");

    $path = Craft::getAlias('@root') . '/build.txt';
    if (file_put_contents($path, random_int(100000, 999999) . "\n") === false) {
        $c->stderr("Failed to write build.txt: {$path}\n");
        return ExitCode::UNSPECIFIED_ERROR;
    }
    $c->stdout("Wrote build.txt: {$path}\n");

    $c->stdout("Seed complete.\n");
    return ExitCode::OK;
};
