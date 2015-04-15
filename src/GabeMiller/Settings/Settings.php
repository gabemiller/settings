<?php
namespace GabeMiller\Settings;

use GabeMiller\Settings\Contracts\SettingsInterface;
use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;

/**
 *
 */
class Settings implements SettingsInterface
{

    /**
     * @var Filesystem
     */
    protected $file;

    /**
     * @var
     */
    protected $config;

    /**
     * @var
     */
    protected $jsonArray;

    /**
     * @var
     */
    protected $fileName;

    /**
     * @var string
     */
    protected $path;

    /**
     * @param Filesystem $file
     */
    public function __construct(Filesystem $file, Repository $config)
    {
        $this->file = $file;

        $this->config = $config;

        $this->setPath();

        $this->setFileName();


        $this->open();

    }


    /**
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return array_get($this->jsonArray, $key);
    }

    /**
     * @param Contracts\keys $array
     * @return int
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function set($array)
    {
        foreach ($array as $key => $value) {
            array_set($this->jsonArray, $key, $value);
        }

        $this->save();

        return $this;

    }

    /**
     * @param string $key
     * @return $this
     */
    public function forget($key)
    {

        array_forget($this->jsonArray, $key);

        $this->save();

        return $this;
    }

    /**
     * @return mixed
     */
    public function clear()
    {
        $this->file->put($this->path . $this->fileName, '');

        $this->save();

        return $this;
    }

    /**
     * Set the new path of the file.
     *
     * @param $path
     * @return mixed
     */
    public function setPath($path = null)
    {
        if (is_null($path)) {
            $this->path = $this->config->get('settings.path');
        } else {
            $this->path = $path;
        }

        if (!ends_with($this->path, '/')) {
            $this->path = str_finish($this->path, '/');
        }


        return $this;
    }

    /**
     * Set the new name of the file.
     *
     * @param $fileName
     * @return mixed
     */
    public function setFileName($fileName = null)
    {
        if (is_null($fileName)) {
            $this->fileName = $this->config->get('settings.name');
        } else {
            $this->fileName = $fileName;
        }

        return $this;
    }

    /**
     * Get the current filename.
     *
     * @return mixed
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Get the current path of the file with optionally the filename.
     *
     * @param bool $withFileName
     * @return mixed
     */
    public function getPath($withFileName = true)
    {
        return $withFileName ? $this->path . $this->fileName : $this->path;
    }

    /**
     * Open the file.
     *
     * @return mixed
     */
    public function open()
    {
        if (!$this->file->exists($this->path . $this->fileName)) {
            touch($this->path . $this->fileName);
        }

        $this->jsonArray = json_decode($this->file->get($this->path . $this->fileName), true);

        return $this;
    }

    /**
     * Save the file.
     *
     * @return mixed
     */
    public function save()
    {
        $this->file->put($this->path . $this->fileName, json_encode($this->jsonArray));

        return $this;
    }

    /**
     * Remove the file.
     *
     * @return mixed
     */
    public function remove()
    {
        $this->file->delete($this->path . $this->fileName);

        return $this;
    }
}