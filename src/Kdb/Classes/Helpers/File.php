<?php

namespace Kdb\Classes\Helpers;

use Kdb\Classes\KdbException;


class File implements FileInterface {

    /**
     * File name
     * @var string
     */
    protected $name;

    /**
     * File type (data|config)
     * @var string
     */
    protected $type;

    public static function table($name)
    {
        $file       = new File;
        $file->name = $name;

        return $file;
    }

    public final function setType($type)
    {
        $this->type = $type;
    }

    public final function getPath()
    {
        if (!env('Kdb_DATA_PATH'))
        {
            throw new KdbException('Set ENV data path {Kdb_DATA_PATH }');
        }
        else if (!empty($this->type))
        {
            return env('Kdb_DATA_PATH') . $this->name . '.' . $this->type . '.json';
        }
        else
        {
            throw new KdbException('Please specify the type of file in class: ' . __CLASS__);
        }
    }

    public final function get($assoc = false)
    {
        return json_decode(file_get_contents($this->getPath()), $assoc);
    }

    public final function put($data)
    {
        return file_put_contents($this->getPath(), json_encode($data));
    }

    public final function exists()
    {
        return file_exists($this->getPath());
    }

    public final function remove()
    {
        $type = ucfirst($this->type);
        if ($this->exists())
        {
            if (unlink($this->getPath()))
                return TRUE;

            throw new KdbException($type . ': Deleting failed');
        }

        throw new KdbException($type . ': File does not exists');
    }

}
