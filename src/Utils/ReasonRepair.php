<?php
/**
 * Created by PhpStorm.
 * User: jzgolinski
 * Date: 15.03.18
 * Time: 20:25
 */

namespace App\Utils;

class ReasonRepair
{
    private $validator;

    public function __construct(ReasonValidator $reasonValidator)
    {
        $this->validator = $reasonValidator;
    }

    public function repair($reason)
    {
        $reason = $this->basicRepair($reason);
        if ($this->validator->isValid($reason)) {
            return $reason;
        }

        $validCodes = ['x1', 'x2', 'x3', 'x4', '10', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $validatedCodes = [];
        $originalReason = $reason;
        foreach ($validCodes as $code) {
            $codePosition = strpos($reason, $code);
            $originalCodePosition = strpos($originalReason, $code);
            if ($codePosition !== false) {
                $codeLength = strlen($code);
                $validCode = substr($reason, $codePosition, $codeLength);
                $validatedCodes[$originalCodePosition] = $validCode;
                $reason = substr_replace($reason, '', $codePosition, $codeLength);
            }
        }
        
        return $this->implodeCodes($validatedCodes);
    }

    private function basicRepair($reason)
    {
        return str_replace(['.', '/', ' ', '"', '#'], [',', ',', ',', '', ''], $reason);
    }

    private function implodeCodes(array $codes)
    {
        ksort($codes);

        return implode(',', $codes);
    }

}