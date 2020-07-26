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
                {
                    // Delete BOM symbol
                    foreach ($row as $key => &$value)
                    {
                        $value = str_replace('﻿', '', $value);
                    }
                    $header = $row;
                }
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

    /* Send Curl Request */
    public static function sendCurlRequest(string $url): array
    {
        $headers = array(
            "Content-Type: application/json",
        );

        $rest = curl_init();
        curl_setopt($rest,CURLOPT_URL,$url);
        curl_setopt($rest,CURLOPT_HTTPHEADER,$headers);
        curl_setopt($rest,CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($rest);

        if (curl_error ($rest))
        {
            return ['error' => curl_error ($rest)];
        }

        curl_close($rest);

        $result = json_decode ($response, true);
        if (json_last_error() != JSON_ERROR_NONE) return ['error' => 'Unable to fetch JSON'];

        return $result;
    }
}