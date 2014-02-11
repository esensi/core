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
        $versioned = Config::get('alba::build.versioned', false);

        // Use config assets if no assets were passed
        if( empty($collections) )
        {
            $collections = Config::get('alba::build.' . $key, []);
        }
        
        // Calculate the signatures of all assets
        $cache_key = 'build.' . md5(implode('_', $collections));
        $signatures = Cache::remember($cache_key, $minutes, function() use ($collections, $extension)
            {
                $signatures = [];
                foreach($collections as $collection):
                    $file_path = public_path('builds/' . $collection . '.' . $extension);
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
        if(in_array(App::environment(), $environments))
        {
            // Get signature of combined file
            $signature = substr(md5(implode('_', array_values($signatures))), 0, 8);
            $file_path = public_path('builds/collection-' . $signature . '.' . $extension);

            // Build combined collection file
            if(!file_exists($file_path))
            {
                $data = '';
                foreach($signatures as $collection => $sig):
                    $data .= file_get_contents(public_path('builds/' . $collection . '-' . $sig . '.' . $extension)) . PHP_EOL;
                endforeach;
                file_put_contents($file_path, $data);
            }

            switch($key)
            {
                case 'scripts':
                    return '<script type="text/javascript" src="' . asset('builds/'. $collection . '-' . $signature . '.' . $extension) . '"></script>';
                    break;

                case 'styles':
                    return '<link rel="stylesheet" href="' . asset('builds/'. $collection . '-' . $signature . '.' . $extension) . '">';
                    break;
            }
        }
        
        // Single assets on non-production environments
        foreach($signatures as $collection => $sig):
            switch($key)
            {
                case 'scripts':
                    $responses[] = '<script type="text/javascript" src="' . asset('builds/'. $collection . ($versioned ? '-' . $sig : null) . '.' . $extension) . '"></script>';
                    break;

                case 'styles':
                    $responses[] = '<link rel="stylesheet" href="' . asset('builds/'. $collection . ($versioned ? '-' . $sig : null) . '.' . $extension) . '">';
                    break;
            }
        endforeach;

        return implode(PHP_EOL, $responses);
    }
}