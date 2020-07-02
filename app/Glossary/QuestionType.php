<?php

namespace App\Glossary;

class QuestionType{
    // const GENDER = ['display'=>'Giới tính', 'value'=>'1'];
    const COMPARE = ['display'=>'Điểm chung', 'value'=>'2'];
    const POINT = ['display'=>'Tính điểm', 'value'=>'3'];
	
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