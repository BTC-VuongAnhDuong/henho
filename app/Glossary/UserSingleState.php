<?php

namespace App\Glossary;

class UserSingleState{
    const SINGLE = ['display'=>'Độc thân', 'value'=>'1'];
    const DIVORCEDNOCHILD = ['display'=>'Đã ly dị chưa có con', 'value'=>'2'];
    const DIVORCEDWCHILD = ['display'=>'Đã ly dị và có con', 'value'=>'3'];
	
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