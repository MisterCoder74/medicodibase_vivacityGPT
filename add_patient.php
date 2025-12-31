<?php
include 'functions.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// Raccolta e pulizia input
$cf = strtoupper(trim($_POST['codice_fiscale'] ?? ''));
$nome = trim($_POST['nome'] ?? '');
$cognome = trim($_POST['cognome'] ?? '');
$data_nascita = trim($_POST['data_nascita'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$email = trim($_POST['email'] ?? '');
$indirizzo = trim($_POST['indirizzo'] ?? '');
$note = trim($_POST['note'] ?? '');

if (!$cf || !$nome || !$cognome || !$data_nascita) {
$message = '<div class="alert alert-danger">Tutti i campi obbligatori devono essere compilati.</div>';
} else {
$patients = loadPatients();

// Verifica duplicati
$exists = array_filter($patients, fn($p) => $p['codice_fiscale'] === $cf);
if ($exists) {
$message = '<div class="alert alert-warning">Esiste già un paziente con questo codice fiscale.</div>';
} else {
// Crea cartella paziente
$folder = __DIR__ . "/patients/$cf";
if (!is_dir($folder)) mkdir($folder, 0775, true);

// Struttura completa del paziente
$patientData = [
'codice_fiscale' => $cf,
'nome' => $nome,
'cognome' => $cognome,
'data_nascita' => $data_nascita,
'telefono' => $telefono,
'email' => $email,
'indirizzo' => $indirizzo,
'note' => $note,
'misurazioni' => [],
'allergie_intolleranze' => [],
'disturbi_ricorrenti' => [],
'farmaci_assunti' => [],
'patologie_importanti' => [],
'visite' => []
];

// Salvataggio file personale
file_put_contents("$folder/data.json", json_encode($patientData, JSON_PRETTY_PRINT));

// Aggiornamento lista generale
$patients[] = [
'codice_fiscale' => $cf,
'nome' => $nome,
'cognome' => $cognome,
'data_nascita' => $data_nascita
];
file_put_contents(__DIR__ . '/data/patients.json', json_encode($patients, JSON_PRETTY_PRINT));

// Redirect
header("Location: patients.php?added=1");
exit;
}
}
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<title>Nuovo Paziente</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background-color:#f8f9fa; }
.card { max-width:700px; margin:auto; }
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
<a href="patients.php" class="btn btn-secondary mb-3">&laquo; Torna all'elenco</a>
<h1 class="mb-4">Aggiungi Nuovo Paziente</h1>
<?php echo $message; ?>
<form method="post" class="card shadow-sm p-4 bg-white">

<div class="mb-3">
<label class="form-label">Codice Fiscale *</label>
<input type="text" name="codice_fiscale" class="form-control" maxlength="16" required>
</div>

<div class="row">
<div class="col-md-6 mb-3">
<label class="form-label">Nome *</label>
<input type="text" name="nome" class="form-control" required>
</div>
<div class="col-md-6 mb-3">
<label class="form-label">Cognome *</label>
<input type="text" name="cognome" class="form-control" required>
</div>
</div>

<div class="mb-3">
<label class="form-label">Data di Nascita *</label>
<input type="date" name="data_nascita" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Telefono</label>
<input type="text" name="telefono" class="form-control" placeholder="+39...">
</div>

<div class="mb-3">
<label class="form-label">Email</label>
<input type="email" name="email" class="form-control" placeholder="esempio@dominio.it">
</div>

<div class="mb-3">
<label class="form-label">Indirizzo</label>
<input type="text" name="indirizzo" class="form-control">
</div>

<div class="mb-3">
<label class="form-label">Note cliniche</label>
<textarea name="note" class="form-control" rows="3"></textarea>
</div>

<button type="submit" class="btn btn-success">Salva Paziente</button>
<a href="patients.php" class="btn btn-secondary">Annulla</a>
</form>
</div>
</body>
</html>