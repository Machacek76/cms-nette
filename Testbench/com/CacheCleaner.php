<?php

namespace test\com;



class CacheCleaner {

    private $cacheDir;

    public function __construct($cacheDir)    {
        $this->cacheDir = $cacheDir;
    }


    public static function clean(string $dir) {
        $di = new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS);
        $ri = new \RecursiveIteratorIterator($di, \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ( $ri as $file ) {
            $file->isDir() ?  rmdir($file) : unlink($file);
        }
        return true;
    }



}

