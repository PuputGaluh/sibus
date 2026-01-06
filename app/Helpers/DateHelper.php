<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Format tanggal ke format Indonesia (d-m-Y)
     */
    public static function formatDate($date)
    {
        if (!$date) return '-';
        
        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);
        return $carbon->format('d-m-Y');
    }

    /**
     * Format tanggal dan waktu ke format Indonesia (d-m-Y H:i)
     */
    public static function formatDateTime($datetime)
    {
        if (!$datetime) return '-';
        
        $carbon = $datetime instanceof Carbon ? $datetime : Carbon::parse($datetime);
        return $carbon->format('d-m-Y H:i');
    }

    /**
     * Format tanggal lengkap Indonesia (d Month Y)
     */
    public static function formatDateLong($date)
    {
        if (!$date) return '-';
        
        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);
        return $carbon->translatedFormat('d F Y');
    }

    /**
     * Format tanggal lengkap dengan waktu Indonesia
     */
    public static function formatDateTimeLong($datetime)
    {
        if (!$datetime) return '-';
        
        $carbon = $datetime instanceof Carbon ? $datetime : Carbon::parse($datetime);
        return $carbon->translatedFormat('d F Y H:i');
    }

    /**
     * Hitung selisih waktu (untuk menunjukkan "2 jam lalu", dll)
     */
    public static function timeAgo($datetime)
    {
        if (!$datetime) return '-';
        
        $carbon = $datetime instanceof Carbon ? $datetime : Carbon::parse($datetime);
        return $carbon->diffForHumans();
    }
}
