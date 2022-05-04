<?php

namespace codemonauts\autoversioning\models;

use craft\base\Model;

class Settings extends Model
{
    /**
     * @var string File to check for build ID
     */
    public string $ciFile = '@root/build.txt';

    /**
     * @var bool Switch to activate versioning
     */
    public bool $active = true;

    /**
     * @var bool Use query parameter instead of path
     */
    public bool $useQueryParam = false;
}
