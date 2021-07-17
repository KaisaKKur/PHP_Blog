<?php
namespace KKur;

class KFileSystem {

    public static function getContentFromLocalFile(string $path, string $mode = "r") : string {
        try {
            $handle = fopen($path, $mode);
            $content = fread($handle, filesize ($path));
            fclose($handle);
            return $content;
        } catch (\Exception $e) {
            exit("Error=" + $e->getMessage());
        }
    }

}

