<?php
namespace GabeMiller\Settings;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

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
    public function __construct(File $file)
    {
        $this->file = $file;
        $this->jsonFile = Config::get('settings::settings.path') . '/' . Config::get('settings::settings.name');


        if (!$this->file->exists($this->jsonFile))
            $this->file->put($this->jsonFile, json_encode(['']));

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

        foreach ($array as $key => $value) {
            if (array_key_exists($key, $settingsArray))
                $settingsArray[$key] = $value;
        }

        return $this->file->put($this->jsonFile,json_encode($settingsArray));

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