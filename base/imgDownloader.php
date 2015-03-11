<?php

// require "imgDownloaderException.php";
use imgDownloaderException as iException;

class imgDownloader
{
    /**
    * @prefix - folder to saving files
    */
    private $prefix = "downloads/";

    /**
    * @allowedMimeTypes - array of string
    * content-type (MIME) type of allowed files
    * http://www.iana.org/assignments/media-types/media-types.xhtml#image
    */
    private $allowedMimeTypes = ["image/jpeg", "image/png", "image/gif"];

    /**
    * constructor
    * @fIn - string, path to file with list of files
    */
	public function __construct($fIn = null)
	{
        echo "-- imgDownloader started. --\n";

        if($fIn !== null) {
            $files = $this->parseList($fIn);
            foreach ($files as $file) {
                $this->saveFile($file);
            }
        } else {
            echo "\nList of files doesn't provided!\n";
            exit(1);
        }
	}

    /**
    * destructor
    */
    public function __destruct()
    {
        echo "\n\n-- Bye. --\n";
    }

    /**
    * requesting @src file and saving to filesystem
    * @src - string URI
    * return true on success or false if file 
    * not accessible from src
    */
    public function saveFile($src)
    {
        if($this->uriFileExist($src)) {
            $fSourceFile = fopen("$src", "rb");
            $sourceMeta = stream_get_meta_data($fSourceFile);
            if($this->isImage($sourceMeta)) {
                $sourceFname = basename($sourceMeta["uri"]);
                if(!is_dir($this->prefix)) {
                    try {
                        mkdir($this->prefix);
                    } catch(iException $e) {
                        throw new iException("\nCannot create dir downloads!\n", 1);
                    }
                }
                if(is_writable($this->prefix) && $fDestFile = fopen($this->prefix . $sourceFname, "w+b")) {
                    stream_copy_to_stream($fSourceFile, $fDestFile);
                    echo "\n# file " . $src . " saved to " . $this->prefix;
                    return true;
                } else {
                    echo "\nCannot write to disk!";
                    exit(1);
                }
            } else {
                echo "\n# file " . $src . " is not image, steping over";
                return false;
            }
        } else {
            echo "\n# file " . $src . " unaccessible on remote server, HTTP 40X ERR ";
            return false;
        }
    }

    /**
    * @list - text file with filenames 
    * separated by new line
    * return array of filenames or pathes
    */
    public function parseList($list)
    {
        if(file_exists($list) && is_readable($list)) {
            try {
                $fContent = file_get_contents($list);
                $contentArray = preg_split('/$\R?^/m', $fContent);
                $res = [];
                foreach ($contentArray as $line) {
                    if(strlen($line) !== 0 && strpos($line, "\n") === false) {
                        $res[] = trim($line);
                    }
                }
                return $res;
            } catch(iException $e) {
                throw new iException("\nError while reading file " . $list . " , " . $e, 1);
            }
        } else {
            echo "\n" . $list . " doesn't exists!";
            exit(1);
        }
    }

    /**
    * @src - MIME object from stream_get_meta_data()
    * check MIME of @src, return true for jpg\png\gif
    */
    public function isImage($src)
    {
        try {
            $mime = $src["wrapper_data"];
            $types = $this->allowedMimeTypes;
            foreach ($mime as $curMime) {
                foreach ($types as $type) {
                    if(strpos($curMime, $type) !== false) {
                        return true;
                    }
                }
            }
            return false;
        } catch(iException $e) {
            throw new iException($e, 1);
        }
    }

    /**
    * @uri - string URI
    * check if file accessible by URI
    * return true if file exists
    */
    public function uriFileExist($uri)
    {
        if(filter_var($uri,FILTER_VALIDATE_URL)) {
            try {
                $fHead = get_headers($uri);
                if(strpos($fHead[0], "40") === false) {
                    return true;
                } else {
                    return false;
                }
            } catch(iException $e) {
                throw new iException($e, 1);
            }
        } else {
            return false;
        }
    }
}
