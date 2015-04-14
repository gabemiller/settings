<?php
namespace GabeMiller\Settings\Contracts;

/**
 *
 */

interface SettingsInterface
{


    /**
     * Open the file.
     *
     * @return mixed
     */
    public function open();

    /**
     * Save the file.
     *
     * @return mixed
     */
    public function save();

    /**
     * Remove the file.
     *
     * @return mixed
     */
    public function remove();

    /**
     * Set the given keys and values in the json file.
     *
     * @param $array keys with dot notation to set the nested values
     * @return mixed
     */
    public function set($array);

    /**
     * Get the values by the given keys.
     *
     * @param $key string with dot notation to get the nested values
     * @return mixed
     */
    public function get($key);

    /**
     * Forget values by the given keys.
     *
     * @param $key string with dot notation to forget nested values
     * @return mixed
     */
    public function forget($key);

    /**
     * Forget all values.
     *
     * @return mixed
     */
    public function clear();

    /**
     * Set the new path of the file.
     *
     * @param $path
     * @return mixed
     */
    public function setPath($path = null);

    /**
     * Set the new name of the file.
     *
     * @param $fileName
     * @return mixed
     */
    public function setFileName($fileName = null);

    /**
     * Get the current filename.
     *
     * @return mixed
     */
    public function getFileName();

    /**
     * Get the current path of the file with optionally the filename.
     *
     * @param bool $withFileName
     * @return mixed
     */
    public function getPath($withFileName = true);
}