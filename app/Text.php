<?php


namespace App;


class Text {
    public static function length($str, $encoding = null) {
        return mb_strlen($str, $encoding);
    }

}