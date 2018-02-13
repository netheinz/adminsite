<?php
/**
 * Created by PhpStorm.
 * User: heka
 * Date: 02/02/2018
 * Time: 05.35
 */

class htmltool
{
    /**
     * @param $text
     * @param $link
     * @return string
     */
    static function button($text, $type = "submit", $class = "btn btn-primary") {
        return "<button type='".$type."' class='".$class."'>" . $text . "</button>\n";
    }

    /**
     * @param $text
     * @param $link
     * @return string
     */
    static function linkbutton($text, $link) {
        return "<a class='btn btn-primary' href='$link'>" . $text . "</a>\n";
    }

    /**
     * @param $link
     * @param $icon
     * @param array $attr
     * @return string
     */
    static function linkicon($link, $icon, $attr = array()) {
        $class = isset($attr["class"]) ? $attr["class"] : "icon";
        return "<a class='$class' href='$link'><i class='fas fa-".$icon."'></i></a>\n";
    }

}