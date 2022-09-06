<?php

namespace App\Helper;

use SilverStripe\Core\Manifest\ModuleResourceLoader;
use SilverStripe\View\TemplateGlobalProvider;

class MixHelper implements TemplateGlobalProvider
{
    public static function startsWith($haystack, $needle) :bool
    {
        return (strpos($haystack, $needle) === 0);
    }

    /**
     * @throws \JsonException
     * @throws \Exception
     */
    public static function Mix($path, $manifestDirectory = 'app/client/dist') :string
    {
        static $manifest;
        $rootPath = BASE_PATH;
        $publicPath = $rootPath;
        if ($manifestDirectory && !self::startsWith($manifestDirectory, '/')) {
            $manifestDirectory = "/{$manifestDirectory}";
        }
        if (!$manifest) {
            if (!file_exists($manifestPath = ($rootPath . $manifestDirectory . '/mix-manifest.json'))) {
                throw new \Exception('The Mix manifest does not exist.');
            }
            $manifest = json_decode(file_get_contents($manifestPath), true, 512, JSON_THROW_ON_ERROR);
        }

        if (!self::startsWith($path, '/')) {
            $path = "/{$path}";
        }

        if (!array_key_exists($path, $manifest)) {
            throw new \Exception(
                "Unable to locate Mix file: {$path}. Please check your " .
                'webpack.mix.js output paths and try again.'
            );
        }

        $url = ModuleResourceLoader::resourceURL($manifestDirectory . $manifest[$path]);

        return file_exists($publicPath . ($manifestDirectory . '/hot'))
            ? "http://localhost:3000/_resources/" . $url
            : $url;
    }

    public static function get_template_global_variables() :array
    {
        return [
            'Mix'
        ];
    }
}
