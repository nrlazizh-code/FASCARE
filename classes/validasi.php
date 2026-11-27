<?php
class Validasi {
    public static function bersih($data) {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    public static function wajib($data) {
        return !empty(trim($data));
    }

    public static function email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function minimalKarakter($data, $min) {
        return strlen(trim($data)) >= $min;
    }
}