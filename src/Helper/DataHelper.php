<?php

namespace App\Helper;

class DataHelper
{
    /**
     * @param array $data
     * @return array
     */
    public function toArray(array $data): array
    {
        $newData = [];
        foreach ($data as $key => $value) {
            $newData[$key]['id'] = (array)$value;
        }
        return $newData;
    }
}