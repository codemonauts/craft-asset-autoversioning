<?php

/**
 * Lab-Seeder für craft3-assets-autoversioning (Craft 5).
 *
 * Wird vom generischen Runner über `php craft seed/run craft3-assets-autoversioning`
 * geladen. Idempotent. Das Plugin hat keine Felder — sein „Content" ist die
 * Konfiguration: gespeicherte Plugin-Settings plus die build.txt, die die Twig-
 * Extension zur Laufzeit als Build-ID liest. Beide Schritte sind versionsagnostisch
 * (savePluginSettings() und @root-Alias sind in Craft 4 und 5 identisch).
 *
 * 1) Persistiert die Plugin-Settings (Defaults) in DB + Project-Config.
 * 2) Legt @root/build.txt mit einer Random-Zahl als einziger Zeile an — dieselbe
 *    Pfadauflösung, die das Plugin aus der ciFile-Setting (@root/build.txt) macht.
 */

use craft\console\Controller;
use yii\console\ExitCode;

return function (Controller $c): int {
    // 1) Plugin-Settings speichern (Defaults explizit persistieren)
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

    // 2) build.txt im @root anlegen (Random-Zahl als einzige Zeile)
    $path = Craft::getAlias('@root') . '/build.txt';
    if (file_put_contents($path, random_int(100000, 999999) . "\n") === false) {
        $c->stderr("Failed to write build.txt: {$path}\n");
        return ExitCode::UNSPECIFIED_ERROR;
    }
    $c->stdout("Wrote build.txt: {$path}\n");

    $c->stdout("Seed complete.\n");
    return ExitCode::OK;
};
