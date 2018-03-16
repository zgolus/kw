<?php
/**
 * Created by PhpStorm.
 * User: jzgolinski
 * Date: 15.03.18
 * Time: 20:30
 */

namespace App\Utils;


class ReasonValidator
{
    public function isValid($reason)
    {
        $isValid = true;
        $validCodes = ['1','2','3','4','5','6','7','8','9','10','x1','x2','x3','x4'];
        $reasonCodes = explode(',', $reason);
        foreach($reasonCodes as $code) {
            if (!in_array($code, $validCodes)) {
                $isValid = false;
            }
        }

        return $isValid;
    }
}