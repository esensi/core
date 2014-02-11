<?php

if ( ! function_exists('build_styles'))
{
    /**
     * Ouput the stylesheets for several collections.
     * 
     * @return string
     */
    function build_styles()
    {
        return build_assets(func_get_args(), 'styles', 'css');
    }
}

if ( ! function_exists('build_scripts'))
{
    /**
     * Ouput the scripts for several collections.
     * 
     * @return string
     */
    function build_scripts()
    {
        return build_assets(func_get_args(), 'scripts', 'js');
    }
}

if ( ! function_exists('build_assets'))
{
    /**
     * Ouput the assets for several collections.
     * 
     * @param array $collections
     * @param string $key
     * @param string $extension
     * @return string
     */
    function build_assets($collections = [], $key, $extension)
    {
        $responses = [];
        $minutes = Config::get('alba::build.ttl.' . $key, 0);
        $environments = Config::get('alba::build.environments', ['production']);
        $versions = Config::get('alba::build.versions', false);
        $builds_dir = public_path(Config::get('alba::build.directories.base', 'builds')) . '/' . Config::get('alba::build.directories.' . $key, $key);
        $builds_url = asset(Config::get('alba::build.directories.base', 'builds')) . '/' . Config::get('alba::build.directories.' . $key, $key);

        // Use config assets if no assets were passed
        if( empty($collections) )
        {
            $collections = Config::get('alba::build.' . $key, []);
        }
        
        // Calculate the signatures of all assets
        $cache_key = 'build.' . md5(implode('_', $collections));
        $signatures = Cache::remember($cache_key, $minutes, function() use ($collections, $extension, $builds_dir)
            {
                $signatures = [];
                foreach($collections as $collection):
                    $file_path = $builds_dir . '/' . $collection . '.' . $extension;
                    if(file_exists($file_path))
                    {
                        $signatures[] = substr(md5_file($file_path), 0, 8);
                    }
                    else
                    {
                        $i = array_search($collection, $collections);
                        unset($collections[$i]);
                    }
                endforeach;
                return array_combine($collections, $signatures);
            });

        // Group assets on production environment
        if(in_array(App::environment(), $environments)):
        
            // Get signature of combined file
            $signature = substr(md5(implode('_', array_values($signatures))), 0, 8);
            $file_path = $builds_dir . '/collection-' . $signature . '.' . $extension;

            // Build combined collection file
            if(!file_exists($file_path))
            {
                $data = '';
                foreach($signatures as $collection => $sig):
                    $data .= file_get_contents($builds_dir . '/' . $collection . '-' . $sig . '.' . $extension) . PHP_EOL;
                endforeach;
                file_put_contents($file_path, $data);
            }

            switch($key)
            {
                case 'scripts':
                    $responses[] = '<script type="text/javascript" src="' . $builds_url . '/collection-' . $signature . '.' . $extension . '"></script>';
                    break;

                case 'styles':
                    $responses[] = '<link rel="stylesheet" href="' . $builds_url . '/collection-' . $signature . '.' . $extension . '">';
                    break;
            }
        
        // Single assets on non-production environments
        else:
        
            // Single assets on non-production environments
            foreach($signatures as $collection => $sig):
                
                switch($key)
                {
                    case 'scripts':
                        $responses[] = '<script type="text/javascript" src="' . $builds_url . '/' . $collection . ($versions ? '-' . $sig : null) . '.' . $extension . '"></script>';
                        break;

                    case 'styles':
                        $responses[] = '<link rel="stylesheet" href="' . $builds_url . '/' . $collection . ($versions ? '-' . $sig : null) . '.' . $extension . '">';
                        break;
                }

            endforeach;

        endif;

        // Print out each asset on it's own line
        return implode(PHP_EOL, $responses) . PHP_EOL;
    }
}