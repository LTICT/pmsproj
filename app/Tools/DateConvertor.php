<?php

namespace App\Tools;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DateConvertor
{
    //START CALENDAR GC TO EC
    public function GCtoEC_Converter($day, $month, $year) {
        /*alert($day + "/" + $month + "/" + $year);*/
    //alert("in function" + $month);
        $day = intval($day);
        $month = intval($month);
        $year = intval($year);
        $cday; $cmonth; $cyear;
        $ecLeapEffect; 
        $gcLeapEffect;
        $convertedDate;
        $ecLeapEffect = isLeapYear($year - 9);
        $gcLeapEffect = isLeapYearGC($year);
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    if ($month == 1)//jan
    {
        $cyear = $year - 8;
        //alert($ecLeapEffect);
        if ($day <= (8 + $ecLeapEffect)) {
            $cmonth = $month + 3; //tahissas
            $cday = ($day + 22 - $ecLeapEffect);
        }
        else {
            $cmonth = $month + 4; //thir
            if ($ecLeapEffect == 1)
                $cday = $day - 9;
            else
                $cday = $day - 8;
        }
        $convertedDate = sprintf('%02d',$cday) . "/" . sprintf('%02d',$cmonth) . "/" . $cyear;
        return $convertedDate;
    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    else if ($month == 2)//feb
    {
        $cyear = $year - 8;
        if ($day <= (7 + $ecLeapEffect)) {
            $cmonth = $month + 3; //thir
            $cday = ($day + 23 - $ecLeapEffect);
        }
        else {//this else statment doesn't need to consider GC Leap $year, since it doesn't make any diffrence on conversion
            $cmonth = $month + 4; //yekatit
            if ($ecLeapEffect == 1)
                $cday = $day - 8;
            else                    //1ce in 4 $year feb leap it self and be 29 rather 28 in this else statment
            $cday = $day - 7;
        }
        $convertedDate = sprintf('%02d',$cday) . "/" . sprintf('%02d',$cmonth) . "/" . $cyear;
        return $convertedDate;
    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    if ($month == 3)//mar
    {
        //both ec and gc leapeffects returns one here so either feb 29 or 28 it ends with ec 21 and march starts from 22
        //so in this case the ec leap effect affects no more $month before this end of $year, since it is rejected by gc leap effect
        //alert("ec leap = " + $ecLeapEffect + " gc leap = " + $gcLeapEffect);
        //alert($gcLeapEffect);
        $cyear = $year - 8;
        if ($day <= 9) {
            $cmonth = $month + 3; //yekatit
            $cday = ($day + 21);
        }
        else {
            $cmonth = $month + 4; //megabit
            $cday = $day - 9;
        }
        $convertedDate = sprintf('%02d',$cday) . "/" . sprintf('%02d',$cmonth) . "/" . $cyear;
        return $convertedDate;
    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    if ($month == 4)//apr
    {
        $cyear = $year - 8;
        if ($day <= 8) {
            $cmonth = $month + 3; //megabit
            $cday = ($day + 22);
        }
        else {
            $cmonth = $month + 4; //miyaziya
            $cday = $day - 8;
        }
        $convertedDate = sprintf('%02d',$cday) . "/" . sprintf('%02d',$cmonth) . "/" . $cyear;
        return $convertedDate;
    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    if ($month == 5)//may
    {
        $cyear = $year - 8;
        if ($day <= 8) {
            $cmonth = $month + 3; //miyaziya
            $cday = ($day + 22);
        }
        else {
            $cmonth = $month + 4; //ginbot
            $cday = $day - 8;
        }
        $convertedDate = sprintf('%02d',$cday) . "/" . sprintf('%02d',$cmonth) . "/" . $cyear;
        return $convertedDate;
    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    if ($month == 6)//jun
    {
        $cyear = $year - 8;
        if ($day <= 7) {
            $cmonth = $month + 3; //ginbot
            $cday = ($day + 23);
        }
        else {
            $cmonth = $month + 4; //sene
            $cday = $day - 7;
        }
        $convertedDate = sprintf('%02d',$cday) . "/" . sprintf('%02d',$cmonth) . "/" . $cyear;
        return $convertedDate;
    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    if ($month == 7)//jul
    {
        $cyear = $year - 8;
        if ($day <= 7) {
            $cmonth = $month + 3; //sene
            $cday = ($day + 23);
        }
        else {
            $cmonth = $month + 4; //hamle
            $cday = $day - 7;
        }
        $convertedDate = sprintf('%02d',$cday) . "/" . sprintf('%02d',$cmonth) . "/" . $cyear;
        return $convertedDate;
    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    if ($month == 8)//aug
    {
        $cyear = $year - 8;
        if ($day <= 6) {
            $cmonth = $month + 3; //hamle
            $cday = ($day + 24);
        }
        else {
            $cmonth = $month + 4; //nehasse
            $cday = $day - 6;
        }
        $convertedDate = sprintf('%02d',$cday) . "/" . sprintf('%02d',$cmonth) . "/" . $cyear;
        return $convertedDate;
    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    if ($month == 9)//sep
    {
        $ecLeapEffect2 = isLeapYear($year - 8); // this is not the same leap check as the global peer, rather it checks if the current
        if ($day <= 5) {                         //$year is leap or not, the global checks if the current-1 is leap or not.
            $cyear = $year - 8;
            $cmonth = $month + 3; //nehasse
            $cday = ($day + 25);
        }
        else if ($day >= 6 && $day <= (10 + $ecLeapEffect2)) {
            $cyear = $year - 8;
            $cmonth = $month + 4; //Puagme
            $cday = $day - 5;
        }
        else {
            $cyear = $year - 7;
            $cmonth = $month - 8; //Meskerem
            if ($ecLeapEffect2 == 1)
                $cday = $day - 11;
            else
                $cday = $day - 10;
        }
        $convertedDate = sprintf('%02d',$cday) . "/" . sprintf('%02d',$cmonth) . "/" . $cyear;
        return $convertedDate;
    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    if ($month == 10)//oct
    {
        $ecLeapEffect2 = isLeapYear($year - 8); // check if last ethiopian $year is leap or not, bc it affects months after puagme 5 or 6
        $cyear = $year - 7;                       // and consider that there is no gc leap arround this $month so it will continue until it gets it.
        if ($day <= (10 + $ecLeapEffect2)) {
            $cmonth = $month - 9;  //meskerem
            if ($ecLeapEffect2 == 1)
                $cday = $day + 19;
            else
                $cday = $day + 20;
        }
        else {
            $cmonth = $month - 8;  //tikimt
            if ($ecLeapEffect2 == 1)
                $cday = $day - 11;
            else
                $cday = $day - 10;
        }
        $convertedDate = sprintf('%02d',$cday) . "/" . sprintf('%02d',$cmonth) . "/" . $cyear;
        return $convertedDate;
    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    if ($month == 11)//nov
    {
        $ecLeapEffect2 = isLeapYear($year - 8); // check if last ethiopian $year is leap or not, bc it affects months after puagme 5 or 6
        $cyear = $year - 7;                       // and consider that there is no gc leap arround this $month so it will continue until it gets it.
        if ($day <= (9 + $ecLeapEffect2)) {
            $cmonth = $month - 9;  //tikimt
            if ($ecLeapEffect2 == 1)
                $cday = $day + 20;
            else
                $cday = $day + 21;
        }
        else {
            $cmonth = $month - 8;  //hidar
            if ($ecLeapEffect2 == 1)
                $cday = $day - 10;
            else
                $cday = $day - 9;
        }
        $convertedDate = sprintf('%02d',$cday) . "/" . sprintf('%02d',$cmonth) . "/" . $cyear;
        return $convertedDate;
    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    if ($month == 12)//dec
    {
        //alert($day+" " + $month + " " + $year);
        $ecLeapEffect2 = isLeapYear($year - 8); // check if last ethiopian $year is leap or not, bc it affects months after puagme 5 or 6
        $cyear = $year - 7;                       // and consider that there is no gc leap arround this $month so it will continue until it gets it.
        if ($day <= (9 + $ecLeapEffect2)) {
            $cmonth = $month - 9;  //hidar
            if ($ecLeapEffect2 == 1)
                $cday = $day + 20;
            else
                $cday = $day + 21;
        }
        else {
            $cmonth = $month - 8;  //tahissas
            if ($ecLeapEffect2 == 1)
                $cday = $day - 10;
            else
                $cday = $day - 9;
        }
        $convertedDate = sprintf('%02d',$cday) . "/" . sprintf('%02d',$cmonth) . "/" . $cyear;
        //alert($convertedDate);
        return $convertedDate;
    }
}
//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
function isLeapYearGC($yearToBeChecked) {
    $yearToBeChecked = intval($yearToBeChecked);
    if ($yearToBeChecked % 4 == 0) {
        if ($yearToBeChecked % 100 != 0)
            return 1;
        else if ($yearToBeChecked % 400 == 0)
            return 1;
        else
            return 0;
    }
    return 0;
}
public static function isLeapYear($yearToBeChecked) {
    $initialYear = 1899;
    $yearToBeChecked = ($yearToBeChecked * 1);
    for ($year = $initialYear; $year <= $yearToBeChecked; $year += 4) {
        if ($yearToBeChecked == $year)
            return 1;
    }
    return 0;
}
//END CALENDAR GC TO EC
//START CALENDAR EC TO GC
public static function ECtoGC_Converter($day, $month, $year) {
    $day = intval($day);
    $month = intval($month);
    $year = intval($year);
    $cday;
    $cmonth;
    $cyear;
    $leapEffect = Self::isLeapYear($year - 1);
    $gcLeapEffect;
    $convertedDate;
    if ($month == 1) //if Meskerem
    {
        $cyear = $year + 7;
        if ($day <= (20 - $leapEffect)) {
            $cmonth = 9; //sep
            $cday = $day + 10 + $leapEffect;
        }
        else {
            $cmonth = 10; //oct
            if ($leapEffect == 1)
                $cday = $day - 19;
            else
                $cday = $day - 20;
        }
        //alert($cday + "/" + $cmonth + "/" + $cyear);
        $convertedDate = $cday . "/" . $cmonth . "/" . $cyear;
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
   else if ($month == 2) //if Tikimt
   {
    $cyear = $year + 7;
    if ($day <= (21 - $leapEffect)) {
            $cmonth = $month + 8; //oct
            $cday = $day + 10 + $leapEffect;
        }
        else {
            $cmonth = $month + 9; //nov
            if ($leapEffect == 1)
                $cday = $day - 20;
            else
                $cday = $day - 21;
        }
        //alert($cday + "/" + $cmonth + "/" + $cyear);
        $convertedDate = $cday . "/" . $cmonth . "/" . $cyear;
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  else  if ($month == 3) //if Hidar
  {
    $cyear = $year + 7;
    if ($day <= (21 - $leapEffect)) {
            $cmonth = $month + 8; //nov
            $cday = $day + 9 + $leapEffect;
        }
        else {
            $cmonth = $month + 9; //dec
            if ($leapEffect == 1)
                $cday = $day - 20;
            else
                $cday = $day - 21;
        }
        //alert($cday + "/" + $cmonth + "/" + $cyear);
        $convertedDate = $cday . "/" . $cmonth . "/" . $cyear;
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    else if ($month == 4) //if Tahissas
    {
        //$cyear = $year + 7;
        if ($day <= (22 - $leapEffect)) {
            $cyear = $year + 7; //$year is ready to switch
            $cmonth = $month + 8; //dec
            $cday = $day + 9 + $leapEffect;
        }
        else {
            $cyear = $year + 8; //$year is switched
            $cmonth = $month - 3;  //JAN /*HAPPY NEW $year*/
            if ($leapEffect == 1)
                $cday = $day - 21;
            else
                $cday = $day - 22;
        }
        //alert($cday + "/" + $cmonth + "/" + $cyear);
        $convertedDate = $cday . "/" . $cmonth . "/" . $cyear;
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 else if ($month == 5) //if Thir
 {
    $cyear = $year + 8;
    if ($day <= (23 - $leapEffect)) {
            $cmonth = $month - 4; //jan
            $cday = $day + 8 + $leapEffect;
        }
        else {
            $cmonth = $month - 3; //feb /*April the fool*/
            if ($leapEffect == 1)
                $cday = $day - 22;
            else
                $cday = $day - 23;
        }
        //alert($cday + "/" + $cmonth + "/" + $cyear);
        $convertedDate = $cday . "/" . $cmonth . "/" . $cyear;
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 else if ($month == 6) //if Yekatit
 {
    $cyear = $year + 8;
    $gcLeapEffect = Self::isLeapYearGC($cyear);
    if ($day <= ((21 + $gcLeapEffect) - $leapEffect)) {
            $cmonth = $month - 4; //feb
            $cday = $day + 7 + $leapEffect;
        }
        else {
            $cmonth = $month - 3; //mar
            if ($leapEffect == 1)
                $cday = $day - (20 + $gcLeapEffect);
            else
                $cday = $day - (21 + $gcLeapEffect);
        }
        //alert($cday + "/" + $cmonth + "/" + $cyear);
        $convertedDate = $cday . "/" . $cmonth . "/" . $cyear;
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
   else if ($month == 7) //if Megabit
   {
    $cyear = $year + 8;
    if ($day <= 22) {
            $cmonth = $month - 4;  //mar
            $cday = $day + 9;
        }
        else {
            $cmonth = $month - 3;  //apr
            $cday = $day - 22;
        }
        //alert($cday + "/" + $cmonth + "/" + $cyear);
        $convertedDate = $cday . "/" . $cmonth . "/" . $cyear;
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 else if ($month == 8) //if Miyazia
 {
    $cyear = $year + 8;
    if ($day <= 22) {
            $cmonth = $month - 4;  //apr
            $cday = $day + 8;
        }
        else {
            $cmonth = $month - 3;  //may
            $cday = $day - 22;
        }
        //alert($cday + "/" + $cmonth + "/" + $cyear);
        $convertedDate = $cday . "/" . $cmonth . "/" . $cyear;
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
   else if ($month == 9) //if Ginbot
   {
    $cyear = $year + 8;
    if ($day <= 23) {
            $cmonth = $month - 4;  //may
            $cday = $day + 8;
        }
        else {
            $cmonth = $month - 3;  //jun
            $cday = $day - 23;
        }
        //alert($cday + "/" + $cmonth + "/" + $cyear);
        $convertedDate = $cday . "/" . $cmonth . "/" . $cyear;
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
else if ($month == 10) //if Sene
{
    $cyear = $year + 8;
    if ($day <= 23) {
            $cmonth = $month - 4;  //jun
            $cday = $day + 7;
        }
        else {
            $cmonth = $month - 3;  //jul
            $cday = $day - 23;
        }
        //alert($cday + "/" + $cmonth + "/" + $cyear);
        $convertedDate = $cday . "/" . $cmonth . "/" . $cyear;
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
   else if ($month == 11) //if Hamle
   {
    $cyear = $year + 8;
    if ($day <= 24) {
            $cmonth = $month - 4;  //jul
            $cday = $day + 7;
        }
        else {
            $cmonth = $month - 3;  //aug
            $cday = $day - 24;
        }
        //alert($cday + "/" + $cmonth + "/" + $cyear);
        $convertedDate = $cday . "/" . $cmonth . "/" . $cyear;
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
   else if ($month == 12) //if Nehasse
   {
    $cyear = $year + 8;
    if ($day <= 25) {
            $cmonth = $month - 4;  //aug
            $cday = $day + 6;
        }
        else {
            //$cyear = $year + 7;
            $cmonth = $month - 3;  //sep
            $cday = $day - 25;
        }
        //alert($cday + "/" + $cmonth + "/" + $cyear);
        $convertedDate = $cday . "/" . $cmonth . "/" . $cyear;
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
   else if ($month == 13) //if Puagme /*Ethiopian's alone $month*/
   {
    $cyear = $year + 8;
        $cmonth = $month - 4;  //sep
        $cday = $day + 5;
        //alert($cday + "/" + $cmonth + "/" + $cyear);
        $convertedDate = $cday . "/" . $cmonth . "/" . $cyear;
    }
    $formattedDate=explode("/", $convertedDate);
    return $formattedDate[2]."-".sprintf('%02d',$formattedDate[1])."-".sprintf('%02d',$formattedDate[0]);
} //end of function
//END CALENDAR EC TO GC
}
