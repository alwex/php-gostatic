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
    /**
     * @var Yaml
     */
    private $params;

    public static function load()
    {
        return new self();
    }

    public static function create($params)
    {

        if (file_exists(self::CONFIG_DIR.'/'.self::CONFIG_FILE)
            || is_dir(self::CONFIG_DIR)
        ) {
            throw new \RuntimeException("configuration file already exists !!!");
        }
        mkdir(self::CONFIG_DIR);
        mkdir(self::CACHE_DIR);

        $yamlString = Yaml::dump($params);
        file_put_contents(self::CONFIG_DIR.'/'.self::CONFIG_FILE, $yamlString);
    }

    private function __construct()
    {
        if (file_exists(self::CONFIG_DIR.'/'.self::CONFIG_FILE)) {
            $this->params = Yaml::parse(file_get_contents(self::CONFIG_DIR.'/'.self::CONFIG_FILE));
        }
    }

    /**
     * @return Yaml
     */
    public function getParams()
    {
        return $this->params;
    }
}