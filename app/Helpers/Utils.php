<?php
namespace App\Helpers;
class Utils{
    public static function rowNumber($items,$loop){
        return ( ( $items->currentPage() - 1) * $items->perPage() ) + $loop->iteration;
    }
}
