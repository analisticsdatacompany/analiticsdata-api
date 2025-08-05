<?php
namespace App\Libs;
use DateTime;
use Exception;
 
class Clock{
    private function __construct(){}
    private function __clone(){}

    static function splitDateTime($datetime,$format='Y-m-d H:i:s') {
        $parts = date_parse_from_format($format, $datetime);
        return [
            'year' => $parts['year'],
            'month' => $parts['month'],
            'day' => $parts['day'],
            'hour' => $parts['hour'],
            'minute' => $parts['minute'],
            'second' => $parts['second']
        ];
    }

    public static function calculateDateDifference($start, $end) {
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
        $difference = $startDate->diff($endDate);
        return [
            'days' => $difference->days,  
            'hours' => $difference->h,
            'minutes' => $difference->i,
            'seconds' => $difference->s
        ];
    }

    

    public static function NowDate($format=null){
        
        try{
            if(is_null($format)){
                return date("Y-m-d H:i:s");
            }
            return date($format);
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
        return null;
    }


    public static function NowDateToTime($data=null){
        try{
            if(is_null($data)){
                return strtotime(date("Y-m-d H:i:s"));
            }
            return strtotime($data);
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
        return null;
    }

    

    public static function minuteToSeconds($minute){
        return $minute * 60 ;
    }

    public static function SecondsToMinute($seconds){
        return $seconds / 60 ;
    }


    public static function HoursToSeconds($hours){
        return $hours * 3600;
    }


   public static function addOneormoreDay($date,$day=1) {
        // Converte a data para um objeto DateTime
        $dateObj = new DateTime($date);
        // Adiciona um dia
        $dateObj->modify(sprintf("+%s day",$day));
        // Retorna a nova data no formato Y-m-d
        return $dateObj->format('Y-m-d');
    }

    public static function addOneormoremonths($date=null,$month=1,$format=null) {
        // Converte a data para um objeto DateTime
        if(is_null($date)){
            $date=date("Y-m-d");
        }
        $dateObj = new DateTime($date);
        // Adiciona um dia
        $dateObj->modify(sprintf("+%s month",$month));
        // Retorna a nova data no formato Y-m-d
        if(is_null($format)){
            return $dateObj->format('Y-m-');
        }else{
            if($format=="br"){
                return $dateObj->format('/m/Y');
            }
            return $dateObj->format('Y-m-');
            
        }

      
        
    }


    

 
}