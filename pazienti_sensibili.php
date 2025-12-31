<?php
// Configurazione
date_default_timezone_set('Europe/Rome');
include 'functions.php';

// 🔹 Elenco termini “sensibili” da cercare (minuscolo)
$patologieSensibili = [
'tumore',
'linfoma',
'diabete',
'sla',
'ipertensione',        
'fibromialgia',
'poliomielite',
'ipoacusia',
'non vedente',
];

// 🔹 Cartella pazienti
$patientsDir = __DIR__ . '/patients/';

// Recupera tutti i pazienti presenti
$dirs = array_filter(glob($patientsDir . '*'), 'is_dir');

$pazientiSensibili = [];

// 🔹 Scorre tutti i file data.json per ogni paziente
foreach ($dirs as $dir) {
$codiceFiscale = basename($dir);
$fileData = $dir . '/data.json';

if (!file_exists($fileData)) continue;

$json = json_decode(file_get_contents($fileData), true);
if (!$json) continue;

// Combina tutti i testi rilevanti (campi note e array patologie, disturbi, diagnosi visite, ecc.)
$aggregatoTesto = '';
$campiDaVerificare = ['note', 'patologie_importanti', 'disturbi_ricorrenti', 'visite'];

foreach ($campiDaVerificare as $campo) {
if (!isset($json[$campo])) continue;

if (is_array($json[$campo])) {
$aggregatoTesto .= ' ' . json_encode($json[$campo], JSON_UNESCAPED_UNICODE);
} else {
$aggregatoTesto .= ' ' . $json[$campo];
}
}

// Testo in minuscolo
$aggregatoTesto = mb_strtolower($aggregatoTesto);

// 🔹 Verifica se contiene almeno una delle patologie sensibili
foreach ($patologieSensibili as $termine) {
if (str_contains($aggregatoTesto, mb_strtolower($termine))) {
$pazientiSensibili[] = [
'codice_fiscale' => $json['codice_fiscale'] ?? $codiceFiscale,
'nome' => $json['nome'] ?? '',
'cognome' => $json['cognome'] ?? '',
'patologia_rilevata' => $termine
];
break; // Evita duplicati dello stesso paziente
}
}
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<title>Pazienti Sensibili</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background-color:#f8f9fa; }
</style>
</head>
<body class="p-4">
    <header class="header">
        <div class="container">
            <h1><i class="bi bi-heart-pulse-fill"></i> Studio Medico</h1>
            <p class="lead">Sistema di Gestione Ambulatorio</p>
        </div>
    </header>
<div class="container">
<a href="index.php" class="btn btn-secondary mb-3">&laquo; Torna alla Dashboard</a>

<h1 class="mb-4"><i class="bi bi-shield-lock"></i> Pazienti Sensibili</h1>
<p class="text-muted">
In questa sezione vengono elencati i pazienti la cui scheda contiene almeno un termine relativo a patologie sensibili definite
nell'elenco configurato internamente alla pagina.
</p>

<div class="card shadow-sm">
<div class="card-header bg-danger text-white">
Elenco pazienti potenzialmente sensibili
</div>
<div class="card-body">
<?php if (empty($pazientiSensibili)): ?>
<div class="alert alert-success mb-0">✅ Nessun paziente sensibile rilevato nei dati attuali.</div>
<?php else: ?>
<table class="table table-striped align-middle">
<thead>
<tr>
<th>Nome</th>
<th>Cognome</th>
<th>Codice Fiscale</th>
<th>Patologia rilevata</th>
<th>Dettaglio</th>
</tr>
</thead>
<tbody>
<?php foreach ($pazientiSensibili as $p): ?>
<tr>
<td><?= htmlspecialchars($p['nome']) ?></td>
<td><?= htmlspecialchars($p['cognome']) ?></td>
<td><?= htmlspecialchars($p['codice_fiscale']) ?></td>
<td><span class="badge bg-danger"><?= htmlspecialchars($p['patologia_rilevata']) ?></span></td>
<td><a href="patient_detail.php?id=<?= htmlspecialchars($p['codice_fiscale']) ?>" class="btn btn-sm btn-outline-primary">
<i class="bi bi-eye"></i> Apri
</a></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php endif; ?>
</div>
</div>
</div>

</body>
</html>