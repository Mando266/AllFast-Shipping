<?php

namespace App\Services;

class MailControllerService
{

    function emailInputToArray(array $emails)
    {
        $emails = implode("\r\n", $emails);
        return $this->emailStringToArray($emails);
    }
    
    function emailStringToArray(string $emails = "")
    {
        return array_filter(explode("\r\n", $emails));
    }
    
    function seperateInputByIndex($data): \Illuminate\Support\Collection
    {
        $details = collect();

        for ($i = 0; $i < count($data['container_no']); $i++) {
            $item = collect($data)->map(fn($values) => $values[$i]);
            $details->push(collect($item));
        }
        return $details;
    }
}