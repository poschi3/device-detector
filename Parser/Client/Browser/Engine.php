<?php declare(strict_types=1);

/**
 * Device Detector - The Universal Device Detection library for parsing User Agents
 *
 * @link http://piwik.org
 *
 * @license http://www.gnu.org/licenses/lgpl.html LGPL v3 or later
 */
namespace DeviceDetector\Parser\Client\Browser;

use DeviceDetector\Parser\Client\ClientParserAbstract;

/**
 * Class Engine
 *
 * Client parser for browser engine detection
 */
class Engine extends ClientParserAbstract
{
    protected $fixtureFile = 'regexes/client/browser_engine.yml';
    protected $parserName  = 'browserengine';

    /**
     * Known browser engines mapped to their internal short codes
     *
     * @var array
     */
    protected static $availableEngines = [
        'WebKit',
        'Blink',
        'Trident',
        'Text-based',
        'Dillo',
        'iCab',
        'Elektra',
        'Presto',
        'Gecko',
        'KHTML',
        'NetFront',
        'Edge',
        'NetSurf',
    ];

    /**
     * Returns list of all available browser engines
     * @return array
     */
    public static function getAvailableEngines(): array
    {
        return self::$availableEngines;
    }

    public function parse(): ?array
    {
        foreach ($this->getRegexes() as $regex) {
            $matches = $this->matchUserAgent($regex['regex']);
            if ($matches) {
                break;
            }
        }

        if (empty($matches) || empty($regex)) {
            return null;
        }

        $name = $this->buildByMatch($regex['name'], $matches);

        foreach (self::getAvailableEngines() as $engineName) {
            if (strtolower($name) == strtolower($engineName)) {
                return ['engine' => $engineName];
            }
        }

        // This Exception should never be thrown. If so a defined browser name is missing in $availableEngines
        throw new \Exception('Detected browser engine was not found in $availableEngines. Tried to parse user agent: '.$this->userAgent); // @codeCoverageIgnore
    }
}
