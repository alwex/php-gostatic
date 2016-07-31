<?php

namespace GoStatic;

/**
 * Created by PhpStorm.
 * User: aguidet
 * Date: 30/07/16
 * Time: 2:24 PM
 */
class Cache
{
    const EXTENTION = '.static';

    /**
     * @var Cache
     */
    private static $instance = null;

    /**
     * the generated content
     * or the static page
     * @var string
     */
    private $config;

    /**
     * the requested url
     * @var string
     */
    private $requestedUrl = '';

    /**
     * cached filename for the
     * requested url
     * @var string
     */
    private $fileName;

    /**
     * do we cache the uri?
     * maybe not, depending on the
     * exclude configuration
     * @var boolean
     */
    private $skip = false;

    /**
     * Cache constructor.
     */
    private function __construct()
    {
        $this->config = Configuration::load();
    }

    /**
     * return the singleton
     * @return Cache
     */
    public static function get()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function start()
    {
        $this->requestedUrl = $_SERVER['REQUEST_URI'];
        if (array_key_exists('QUERY_STRING', $_SERVER)) {
            $this->requestedUrl .= '?'.$_SERVER['QUERY_STRING'];
        }

        $params = $this->config->getParams();
        $excludePatterns = $params[Configuration::KEY_CACHE][Configuration::KEY_EXCLUDE];

        foreach ($excludePatterns as $pattern) {
            if (preg_match('/'.$pattern.'/', $this->requestedUrl)) {
                $this->skip = true;
            }
        }

        if (!$this->skip) {
            $this->fileName = $this->config->getCacheDirectory().'/'.sha1($this->requestedUrl).self::EXTENTION;
            $expire = time() - $params[Configuration::KEY_CACHE][Configuration::KEY_TTL];

            if (file_exists($this->fileName)
                && filemtime($this->fileName) > $expire
            ) {
                $content = file_get_contents($this->fileName);
                echo $content;
                exit();
            }

            ob_start();
        }
    }

    public function end()
    {
        if (!$this->skip) {
            $content = ob_get_contents();
            ob_end_clean();

            file_put_contents($this->fileName, $content);

            // write the content
            // to the user browser
            echo $content;
        }
    }
}