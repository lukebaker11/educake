<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use ReflectionException;

class Helper
{
    /**
     * @param string $formData
     * @return array
     */
    public static function unserializeForm(string $formData):array
    {
        $array = [];
        parse_str($formData, $array);
        return $array;
    }

    /**
     * @param string $dateTimeString
     * @param string $format
     * @return Carbon
     */
    public static function formDatePickerToDateTime(string $dateTimeString, $format = 'jS F Y, g:ia'):Carbon
    {
        return Carbon::createFromFormat($format, $dateTimeString);
    }
}
