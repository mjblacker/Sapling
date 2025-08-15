<?php

namespace Sapling;

class FileUtils
{
    /**
     * @param array<string> $file_list
     */
    public static function firstOfFiles(array $file_list): string|null
    {
        return array_reduce(
            $file_list,
            function ($c, $v) {
                if(is_null($c) && file_exists($v)) {
                    $c = $v;
                }
                return $c;
            },
            null
        );

    }

}
