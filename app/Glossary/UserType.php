<?php

namespace App\Glossary;

class UserType{
    const CLIENT = ['display'=>'Khách hàng', 'value'=>'1'];
    const SUPER_ADMIN = ['display'=>'Quản trị hệ thống', 'value'=>'2'];
    const ADMIN = ['display'=>'Quản trị viên', 'value'=>'3'];
	
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