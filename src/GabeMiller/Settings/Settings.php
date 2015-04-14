<?php
namespace GabeMiller\Settings;

use GabeMiller\Settings\Contracts\SettingsInterface;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;

/**
 *
 */
class Settings implements SettingsInterface
{

    /**
     * @var
     */
    protected $file;

    /**
     * @var
     */
    protected $jsonArray;

    /**
     * @var
     */
    protected $fileName;

    /**
     * @var
     */
    protected $path;

    /**
     * @param Filesystem $file
     */
    public function __construct(Filesystem $file)
    {
        $this->file = $file;

        $this->path = Config::get('settings.path');

        if (!ends_with($this->path, '/')) {
            $this->path = str_finish($this->path, '/');
        }

        $this->fileName = Config::get('settings.name');

        $this->open();

    }


    /**
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        $this->open();

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
        $this->file->put($this->jsonArray, '');

        $this->save();

        return $this;
    }

    /**
     * Set the new path of the file.
     *
     * @param $path
     * @return mixed
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Set the new name of the file.
     *
     * @param $fileName
     * @return mixed
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

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
}