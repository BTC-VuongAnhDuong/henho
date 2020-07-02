<?php

namespace App\Glossary;

class UserState{
    const UNAVAILABLE = ['display'=>'Không hoạt động', 'value'=>'0'];
    const PENDING = ['display'=>'Đang chờ', 'value'=>'1'];
    const MATCHED = ['display'=>'Đã kết nối thành công', 'value'=>'2'];
	
    public static function getAll() {
        $oClass = new \ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
    public static function getDisplay($value) {
        if (isset($value)){
            $oClass = new \ReflectionClass(__CLASS__);
            $constants = $oClass->getConstants();
            foreach ($constants as $item) {
                if ($item['value'] == $value) return $item['display'];
            }
        }
        return false;
    }


}