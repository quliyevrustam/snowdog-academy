<?php

namespace Snowdog\Academy\Component;

class Helper
{
    /* Parse CSV file - from file to array */
    public static function parseCsvToArray(string $filename, string $delimiter): array
    {
        $return = [];

        $header = null;
        if(($handle = fopen($filename, 'r')) !== false)
        {
            while(($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if(!$header)
                    $header = $row;
                else
                {
                    $return[] = array_combine($header, $row);
                }

            }
            fclose($handle);
        }

        return $return;
    }

    // Print array for testing
    public static function prePrint(array $array, bool $isExit = false): void
    {
        echo '<pre>';
        print_r($array);
        echo '</pre>';

        if($isExit) exit();
    }
}