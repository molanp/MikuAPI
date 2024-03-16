<?php
session_start();
$timestamp = time();

$_SESSION['cc_times'] = ($_SESSION['cc_lasttime'] ?? $timestamp) === $timestamp ? ($_SESSION['cc_times'] ?? 0) + 1 : 1;
$_SESSION['cc_lasttime'] = $timestamp;

if (isset($_SESSION['cc_locktime']) && $_SESSION['cc_locktime'] >= $timestamp) {
	die(header('HTTP/1.0 444'));
}

if (($timestamp - $_SESSION['cc_lasttime']) < 30 && $_SESSION['cc_times'] >= 10) {
	$_SESSION['cc_locktime'] = $timestamp + 60 * 60;
	die(header('HTTP/1.0 444'));
}
