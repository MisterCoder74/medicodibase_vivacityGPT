<?php
date_default_timezone_set('Europe/Rome');

function loadPatients() {
$file = __DIR__ . '/data/patients.json';
if (!file_exists($file)) return [];
$json = file_get_contents($file);
return json_decode($json, true) ?: [];
}

function loadPatientData($codiceFiscale) {
$path = __DIR__ . "/patients/$codiceFiscale/data.json";
if (!file_exists($path)) return null;
$json = file_get_contents($path);
return json_decode($json, true);
}

function savePatientData($codiceFiscale, $data) {
$folder = __DIR__ . "/patients/$codiceFiscale";
if (!is_dir($folder)) mkdir($folder, 0775, true);
file_put_contents("$folder/data.json", json_encode($data, JSON_PRETTY_PRINT));
}

function updatePatientSection($codiceFiscale, $section, $newEntry) {
$patientData = loadPatientData($codiceFiscale);
if (!$patientData) return false;

if (!isset($patientData[$section]) || !is_array($patientData[$section])) {
$patientData[$section] = [];
}
$patientData[$section][] = $newEntry;

savePatientData($codiceFiscale, $patientData);
return true;
}

function getPatientAssets($codiceFiscale) {
$path = __DIR__ . "/patients/$codiceFiscale/assets";
if (!is_dir($path)) return [];
$files = array_diff(scandir($path), ['.', '..']);
return array_values($files);
}
?>