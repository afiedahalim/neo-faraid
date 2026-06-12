<?php

if (!function_exists('formatDate')) {
    function formatDate($date, $format = 'd M Y') {
        if (!$date) return 'Recent';
        if (is_string($date)) {
            $timestamp = strtotime($date);
            return $timestamp ? date($format, $timestamp) : 'Recent';
        }
        if ($date instanceof \DateTime) {
            return $date->format($format);
        }
        return 'Recent';
    }
}