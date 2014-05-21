<?php namespace Esensi\Core\Interfaces;

/**
 * Packaged interface
 *
 * @author daniel <daniel@bexarcreative.com>
 */
interface PackagedInterface{

    /**
     * Setup the layout used by the controller.
     * This is part of Laravel's internal controller
     * layout handlers. It should stay here.
     *
     * @return void
     */
    function setupLayout();

    /**
     * Generate a subview for the layout
     *
     * @param string $key to view config
     * @param array $data (optional) to be passed to view
     * @param string $name (optional) of content
     * @return \Illuminate\View\View
     */
    function content(string $key, array $data = [], string $name = null);

    /**
     * Generate a modal view
     *
     * @param string $key to view config
     * @param array $data to be passed to view
     * @param string $name (optional) of content
     * @return \Illuminate\View\View
     */
    function modal(string $key, array $data = [], string $name = null);

    /**
     * Get a configuration line
     *
     * @param string $key to config line
     * @return mixed
     */
    function config(string $key);

    /**
     * Get the package namespace
     *
     * @return string
     */
    function namespacing();

    /**
     * Get a language line
     *
     * @param string $key to language config line
     * @param array $replacements (optional) in language line
     * @return string
     */
    function language(string $key, array $replacements = []);

    /**
     * Get an error language line
     *
     * @param string $key to language config line
     * @param array $replacements (optional) in language line
     * @return string
     */
    function error(string $key, array $replacements = []);

    /**
     * Get a message language line
     *
     * @param string $key to language config line
     * @param array $replacements (optional) in language line
     * @return string
     */
    function message(string $key, array $replacements = []);
    /**
     * Generate a redirect
     *
     * @param string $key to route config
     * @param array $params (optional) to construct route
     * @return \Illuminate\Routing\Redirector
     */
    function redirect(string $key, array $params = []);

    /**
     * Generate a redirect back
     *
     * @param string $key to route config
     * @param array $params (optional) to construct route
     * @return \Illuminate\Routing\Redirector
     */
    function back(string $key, array $params = []);

}