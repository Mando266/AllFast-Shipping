<?php

namespace App\Helpers;

use EDI\Parser;
use Illuminate\Support\Facades\Log;

class EdiParser
{
    public static function parse($filePath)
    {
        $parser = new Parser();
        $parser->load($filePath);
        $errors = $parser->errors();

        if (!empty($errors)) {
            throw new \Exception("Errors found while parsing EDI file: " . implode(", ", $errors));
        }

        $parsedData = $parser->get();
        $records = [];
        $record = [];

        foreach ($parsedData as $segment) {
            if (!is_array($segment) || count($segment) < 1) {
                continue;
            }

            switch ($segment[0]) {
                case 'EQD':
                    $record['container_no'] = $segment[2] ?? null;
                    $record['iso_number'] = $segment[3][0] ?? null;
                    break;
                case 'TDT':
                    if ($segment[1] != '20') {
                        continue 2;
                    }
                    Log::debug('Processing TDT segment', ['segment' => $segment]);


                    $record['voyage_number'] = $segment[2] ?? null;
                    if (isset($segment[8]) && is_array($segment[8])) {
                        Log::debug('TDT Segment IMO Info', ['imo_info' => $segment[8]]);
                        $record['imo_number'] = $segment[8][0] ?? null;
                        $record['ship_name'] = $segment[8][3] ?? 'Unknown';
                        $record['country_code'] = $segment[8][4] ?? null;
                    }
                    break;
                case 'BGM':
                    $record['movement_type'] = isset($segment[1]) && $segment[1] == '36' ? 'SNTC' : 'RCVS';
                    break;
                case 'RFF':
                    if (!isset($segment[1][1])) {
                        throw new \Exception("Missing booking_number in segment RFF");
                    }
                    $record['booking_number'] = $segment[1][1];
                    break;
                case 'FTX':
                    $record['goods_description'] = $segment[4] ?? null;
                    break;
                case 'DTM':
                    if (isset($segment[1][0])) {
                        switch ($segment[1][0]) {
                            case '178':
                                $record['arrival_date'] = self::formatDate($segment[1][1]);
                                break;
                            case '133':
                                $record['departure_date'] = self::formatDate($segment[1][1]);
                                break;
                            case '7':
                                $record['planned_date'] = self::formatDate($segment[1][1]);
                                break;
                            case 'ACT':
                                $record['actual_date'] = self::formatDate($segment[1][1]);
                                break;
                            default:
                                // Handle any other unexpected DTM qualifiers if needed
                                break;
                        }
                    }
                    break;
                case 'LOC':
                    if (isset($segment[1]) && $segment[1] == '11') {
                        $record['activity_location'] = $segment[2][0] ?? null;
                    } elseif (isset($segment[1]) && $segment[1] == '9') {
                        $record['pol'] = $segment[2][0] ?? null;
                    } elseif (isset($segment[1]) && $segment[1] == '8') {
                        $record['pod'] = $segment[2][0] ?? null;
                    }
                    break;
                case 'MEA':
                    if (isset($segment[1]) && $segment[1] == 'AAE' && isset($segment[2]) && $segment[2] == 'G') {
                        $record['gross_weight'] = $segment[3][1] ?? null;
                    }
                    break;
                case 'UNT':
                    if (!empty($record)) {
                        Log::debug('Completed record:', ['record' => $record]);
                        $records[] = $record;
                        $record = [];
                    }
                    break;
            }
        }

        return $records;
    }

    private static function formatDate($dateString)
    {
        if (strlen($dateString) === 12) {
            return \DateTime::createFromFormat('YmdHi', $dateString)->format('Y-m-d H:i:s');
        }

        return null;
    }
}
