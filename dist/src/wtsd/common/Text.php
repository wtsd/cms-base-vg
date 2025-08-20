<?php
namespace wtsd\common;
/**
* Helper class for working with text. Basically, cyrillic. Methods are appended
* when it`s needed. This is necessary to make all the checking mechanisms and
* to format the text nitty as it`s needed.
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Text
{
    public static function prepare($text)
    {
        $ret_text = $text;
        $preps = array('с', 'в', 'по', 'а', 'и', 'на', 'им.', 'из', 'от', 'к', 'г.');
        foreach ($preps as $prep) {
            $ret_text = self::escape(str_replace(' ' . $prep . ' ', ' ' . $prep . '&nbsp;', $ret_text));
        }
        $ret_text = trim($ret_text);
        $replacements = array(
            ' - ' => '&nbsp;&mdash;&nbsp;',
            ' - ' => '&nbsp;&mdash;&nbsp;',
            ' – ' => '&nbsp;&mdash;&nbsp;',
        );
        foreach ($replacements as $key => $value) {
            $ret_text = str_replace($key, $value, $ret_text);
        }

        //$ret_text = preg_replace('!(((f|ht)tp://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1">$1</a>', $ret_text);
        
        return $ret_text;
    }

    public static function escape($str)
    {
        if (get_magic_quotes_gpc()) {
            $str = stripslashes($str);
        }
        return $str;
    }
    
    /* $num, "человек", "человека", "человек" | " фотография"," фотографий"," фотографий" */
    public static function numRussification($num, $im_p, $v_p, $r_p)
    {
        if (($num >= 11) && ($num <= 19)) {
            return $r_p;
        } elseif ($num % 10 == 1) {
            return $im_p;
        } elseif (($num % 10 >= 2) && ($num % 10 <= 4)) {
            return $v_p;
        } else {
            return $r_p;
        }
            
    }

    public static function transliterate($str)
    {
        $tr = array(
            "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","И"=>"I","Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M",
            "Н"=>"N","О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T","У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH","Ш"=>"SH","Щ"=>"SCH",
            "Ъ"=>"","Ы"=>"YI","Ь"=>"","Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
            "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l","м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r","с"=>"s","т"=>"t","у"=>"u",
            "ф"=>"f","х"=>"h","ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y","ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya"
        );
        return strtr($str, $tr);
    }

    public static function rusDate($mysqlDateString, $withTime = false)
    {
        //2013-03-10 21:35:35
        $mysqlDateArray = explode(" ", trim($mysqlDateString));
        $mysqlDate = $mysqlDateArray[0];
        $mysqlTime = $mysqlDateArray[1];

        $tmpDateArray = explode("-", $mysqlDate);
        $year = intval($tmpDateArray[0]);
        $month = intval($tmpDateArray[1]);
        $day = intval($tmpDateArray[2]);
        $rusMonths = array('', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября','октября', 'ноября', 'декабря');

        $resultString = $day . ' ' . $rusMonths[$month] . ' ' . $year;
        if ($withTime) {
            $resultString .= ' ' . substr($mysqlTime, 0, -3);
        }
        return $resultString;
    }

    static public function rusCurrency($price)
    {
        return number_format($price, 0, ',', ' ') . self::numRussification($price, " рубль", " рублей", " рублей");
    }
}