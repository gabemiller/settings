<?php
namespace GabeMiller\Settings;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;

/**
 *
 */
class Settings
{

    /**
     * @var
     */
    protected $file;

    /**
     * @var
     */
    protected $jsonFile;

    /**
     * @param Filesystem $file
     */
    public function __construct(Filesystem $file)
    {
        $this->file = $file;
        $this->jsonFile = Config::get('settings.path') . '/' . Config::get('settings.name');


        if (!$this->file->exists($this->jsonFile))
            touch($this->jsonFile);

    }


    /**
     * @param $key
     * @return array
     */
    public function get($key)
    {
        $settingsArray = json_decode($this->file->get($this->jsonFile), true);

        return array_only($settingsArray, $key);
    }

    /**
     * @param $array
     */
    public function set($array)
    {

        $settingsArray = json_decode($this->file->get($this->jsonFile), true);

        array_unique(array_merge($settingsArray,$array));

        return $this->file->put($this->jsonFile, json_encode($settingsArray));

    }

    /**
     * @param $key
     * @return mixed
     */
    public function forget($key)
    {
        $settingsArray = json_decode($this->file->get($this->jsonFile), true);

        return $this->file->put($this->jsonFile, json_encode(array_except($settingsArray, $key)));
    }

    /**
     * @return mixed
     */
    public function clear()
    {
        return $this->file->put($this->jsonFile, json_encode(['']));
    }

}