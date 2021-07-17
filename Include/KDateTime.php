<?php
namespace KKur;

class KDateTime {

    public static function isDateValid(string $date, array $formats = array('Y-m-d', 'm-d-Y', 'm/d/Y')) : bool {
        $unixTime = strtotime($date);
        
        if(!$unixTime) {
            return false;
        }

        foreach ($formats as $format) {
            if(date($format, $unixTime) == $date) {
                return true;
            }
        }
    
        return false;
    }

    public static function properNounToDigit(string $properNoun) : int|false {
        switch ($properNoun) {
            // Month
            case 'Jan.': return 1;
            case 'January': return 1;
            case 'Feb.': return 2;
            case 'February': return 2;
            case 'Mar.': return 3;
            case 'March': return 3;
            case 'Apr.': return 4;
            case 'April': return 4;
            case 'May.': return 5;
            case 'May': return 5;
            case 'Jun.': return 6;
            case 'June': return 6;
            case 'Jul.': return 7;
            case 'July': return 7;
            case 'Aug.': return 8;
            case 'August': return 8;
            case 'Sept.': return 9;
            case 'September': return 9;
            case 'Oct.': return 10;
            case 'October': return 10;
            case 'Nov.': return 11;
            case 'November': return 11;
            case 'Dec.': return 12;
            case 'December': return 12;

            // Week
            case 'Sun.': return 7;
            case 'Sunday': return 7;
            case 'Mon.': return 1;
            case 'Monday': return 1;
            case 'Tues.': return 2;
            case 'Tuesday': return 2;
            case 'Wed.': return 3;
            case 'Wednesday': return 3;
            case 'Thur.': return 4;
            case 'Thurs.': return 4;
            case 'Thursday': return 4;
            case 'Fri.': return 5;
            case 'Friday': return 5;
            case 'Sat.': return 6;
            case 'Saturday': return 6;

            default:
                return false;
        }
    }
    
}

