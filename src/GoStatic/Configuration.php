<?php

namespace GoStatic;

use Symfony\Component\Yaml\Yaml;

/**
 * Created by PhpStorm.
 * User: aguidet
 * Date: 30/07/16
 * Time: 2:46 PM
 */
class Configuration
{
    const CONFIG_DIR = '.gostatic';
    const CONFIG_FILE = 'configuration.yml';
    const CACHE_DIR = '.gostatic_cache';

    const KEY_CACHE = 'cache';
    const KEY_TTL = 'ttl';
    const KEY_EXCLUDE = 'exclude';
    const KEY_ONLY = 'only';

    private $configurationDirectory = '';
    private $cacheDirectory = '';

    /**
     * @var Yaml
     */
    private $params;

    public static function load()
    {
        $configuration = new self();
        $configuration->loadConfigurationFile();

        return $configuration;
    }

    /**
     * @param $params
     * @return Configuration
     */
    public static function create($params)
    {
        $configuration = new self();
        $configuration->save($params);

        return $configuration;
    }

    private function __construct()
    {
        $this->setCacheDirectory(self::CACHE_DIR);
        $this->setConfigurationDirectory(self::CONFIG_DIR);

        // path detection
        // src/GoStatic/file
        $rootDirectory = __DIR__ . '/../..';
        if (is_dir($rootDirectory.'/vendor')) {
            $this->setConfigurationDirectory($rootDirectory.'/'.self::CONFIG_DIR);
            $this->setCacheDirectory($rootDirectory.'/'.self::CACHE_DIR);
        }

        $rootDirectory = __DIR__ . '/../../..';
        if (is_dir($rootDirectory.'/vendor')) {
            $this->setConfigurationDirectory($rootDirectory.'/'.self::CONFIG_DIR);
            $this->setCacheDirectory($rootDirectory.'/'.self::CACHE_DIR);
        }
    }

    private function loadConfigurationFile()
    {
        if (file_exists($this->getConfigurationDirectory().'/'.self::CONFIG_FILE)) {
            $this->params = Yaml::parse(file_get_contents($this->getConfigurationDirectory().'/'.self::CONFIG_FILE));
        } else {
            throw new \RuntimeException("project not initialized correctly, please execute bin/gostatic go:init");
        }
    }

    private function save($params)
    {
        if (is_dir($this->getConfigurationDirectory())) {
            throw new \RuntimeException("configuration file already exists !!!");
        }
        mkdir($this->getConfigurationDirectory());
        mkdir($this->getCacheDirectory());

        $yamlString = Yaml::dump($params);
        file_put_contents($this->getConfigurationDirectory().'/'.self::CONFIG_FILE, $yamlString);
    }

    /**
     * @return Yaml
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return string
     */
    public function getConfigurationDirectory()
    {
        return $this->configurationDirectory;
    }

    /**
     * @param string $configurationDirectory
     */
    public function setConfigurationDirectory($configurationDirectory)
    {
        $this->configurationDirectory = $configurationDirectory;
    }

    /**
     * @return string
     */
    public function getCacheDirectory()
    {
        return $this->cacheDirectory;
    }

    /**
     * @param string $cacheDirectory
     */
    public function setCacheDirectory($cacheDirectory)
    {
        $this->cacheDirectory = $cacheDirectory;
    }

}