<?php
date_default_timezone_set('Europe/Rome');
include 'functions.php';

$id = $_GET['id'] ?? '';
if (!$id) die("Paziente non specificato");

// Carica i dati
$patient = loadPatientData($id);
if (!$patient) die("File del paziente non trovato.");

// Percorso sottocartella assets
$assetsFolder = __DIR__ . "/patients/$id/assets";
if (!is_dir($assetsFolder)) mkdir($assetsFolder, 0775, true);

// ----- UPLOAD FILE -----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['asset_file'])) {
$file = $_FILES['asset_file'];
if ($file['error'] === UPLOAD_ERR_OK) {
$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$timestamp = date('Ymd_His');
$filename = $timestamp . '.' . strtolower($ext);
move_uploaded_file($file['tmp_name'], "$assetsFolder/$filename");
}
header("Location: patient_detail.php?id=$id&success=1");
exit;
}

// ----- AGGIUNTA DATI CLINICI -----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['section'])) {
$section = $_POST['section'];
$new = null;

switch ($section) {
case 'misurazioni':
$new = [
'data' => date('Y-m-d'),
'altezza_cm' => (int)$_POST['altezza_cm'],
'peso_kg' => (float)$_POST['peso_kg'],
'battito' => (int)$_POST['battito'],
'pressione_max' => (int)$_POST['pressione_max'],
'pressione_min' => (int)$_POST['pressione_min']
];
break;

case 'allergie_intolleranze':
case 'disturbi_ricorrenti':
case 'farmaci_assunti':
case 'patologie_importanti':
$new = trim($_POST['valore']);
break;

case 'visite':
$new = [
'data' => $_POST['data_visita'],
'ora' => $_POST['ora_visita'],
'diagnosi' => $_POST['diagnosi'],
'farmaci_consigliati' => $_POST['farmaci_consigliati'],
'note' => $_POST['note']
];
break;
}

if ($new) {
updatePatientSection($id, $section, $new);
header("Location: patient_detail.php?id=$id&success=1");
exit;
}
}

// ----- CARICAMENTO FILE E DATI -----
$assets = getPatientAssets($id);
$patient = loadPatientData($id); // aggiorna dopo eventuale salvataggio
$success = isset($_GET['success']);
?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<title>Scheda Paziente - <?= htmlspecialchars($patient['nome'].' '.$patient['cognome']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background:#f8f9fa; }
.page-container { display:flex; gap:1rem; }
.sidebar { width:280px; background:#fff; border:1px solid #ddd; padding:1rem; height:auto; }
.main { flex:1; background:#fff; border:1px solid #ddd; padding:1rem; }
.file-table td { vertical-align:middle; }
.section-block { margin-bottom:2rem; }
</style>
</head>
<body class="p-3">
    <header class="header">
        <div class="container">
            <h1><i class="bi bi-heart-pulse-fill"></i> Studio Medico</h1>
            <p class="lead">Sistema di Gestione Ambulatorio</p>
        </div>
    </header>
<div class="container-fluid page-container">

<!-- ================= COLONNA SINISTRA: ALLEGATI ================= -->
<div class="sidebar shadow-sm">
<h5 class="mb-3">📎 Allegati</h5>

<?php if ($assets): ?>
<table class="table table-sm file-table">
<thead><tr><th>File</th><th></th></tr></thead>
<tbody>
<?php foreach ($assets as $f): ?>
<?php
$ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
if (in_array($ext, ['jpg','jpeg','png','gif'])) {
$icon = '<i class="bi bi-image text-primary"></i>';
} elseif ($ext === 'pdf') {
$icon = '<i class="bi bi-file-earmark-pdf text-danger"></i>';
} else {
$icon = '<i class="bi bi-file-earmark text-secondary"></i>';
}
?>
<tr>
<td class="text-truncate" style="max-width:140px;"><?= $icon ?> <?= htmlspecialchars($f) ?></td>
<td><a class="btn btn-sm btn-outline-primary" target="_blank" href="patients/<?= $id ?>/assets/<?= rawurlencode($f) ?>">Apri</a></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php else: ?>
<div class="text-muted small mb-2">Nessun file caricato</div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" class="mt-3">
<div class="input-group input-group-sm">
<input type="file" name="asset_file" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
<button class="btn btn-success btn-sm" type="submit">Upload</button>
</div>
<div class="form-text">Formati: PDF, JPG, PNG</div>
</form>
</div>

<!-- ================= COLONNA DESTRA: DATI E SCHEDE ================= -->
<div class="main shadow-sm">

<a href="patients.php" class="btn btn-secondary btn-sm mb-3">&laquo; Torna all'elenco</a>

<?php if ($success): ?>
<div id="alert-success" class="alert alert-success alert-dismissible fade show" role="alert">
✅ Dati aggiornati correttamente.
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Chiudi"></button>
</div>
<?php endif; ?>

<h3><?= htmlspecialchars($patient['nome'].' '.$patient['cognome']) ?></h3>
<hr>

<div class="section-block mb-4">
<p>
<strong>Codice Fiscale:</strong> <?= $patient['codice_fiscale']; ?><br>
<strong>Email:</strong> <?= $patient['email'] ?? '—'; ?><br>
<strong>Telefono:</strong> <?= $patient['telefono'] ?? '—'; ?><br>
<strong>Indirizzo:</strong> <?= $patient['indirizzo'] ?? '—'; ?><br>
<strong>Data di Nascita:</strong> <?= $patient['data_nascita'] ?? '—'; ?>
</p>
</div>

<!-- Misurazioni -->
<div class="section-block">
<h4>Misurazioni</h4>
<table class="table table-sm">
<thead><tr><th>Data</th><th>Altezza</th><th>Peso</th><th>Battito</th><th>Pressione</th></tr></thead>
<tbody>
<?php foreach (($patient['misurazioni'] ?? []) as $m): ?>
<tr>
<td><?= $m['data'] ?></td>
<td><?= $m['altezza_cm'] ?> cm</td>
<td><?= $m['peso_kg'] ?> kg</td>
<td><?= $m['battito'] ?></td>
<td><?= $m['pressione_max'] ?>/<?= $m['pressione_min'] ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<form method="post" class="row g-2 mb-4">
<input type="hidden" name="section" value="misurazioni">
<div class="col"><input type="number" name="altezza_cm" class="form-control" placeholder="Altezza cm"></div>
<div class="col"><input type="number" name="peso_kg" class="form-control" placeholder="Peso kg"></div>
<div class="col"><input type="number" name="battito" class="form-control" placeholder="Battito"></div>
<div class="col"><input type="number" name="pressione_max" class="form-control" placeholder="P. max"></div>
<div class="col"><input type="number" name="pressione_min" class="form-control" placeholder="P. min"></div>
<div class="col-auto"><button class="btn btn-primary">Aggiungi</button></div>
</form>
</div>

<!-- Altre sezioni testuali -->
<?php
$arrSections = [
'allergie_intolleranze' => 'Allergie e Intolleranze',
'disturbi_ricorrenti' => 'Disturbi Ricorrenti',
'farmaci_assunti' => 'Farmaci Assunti',
'patologie_importanti' => 'Patologie Importanti'
];
foreach ($arrSections as $sec => $title):
?>
<div class="section-block">
<h4><?= $title ?></h4>
<ul class="list-group mb-2">
<?php foreach (($patient[$sec] ?? []) as $item): ?>
<li class="list-group-item"><?= htmlspecialchars($item) ?></li>
<?php endforeach; ?>
<?php if (empty($patient[$sec])): ?>
<li class="list-group-item text-muted">Nessun dato</li>
<?php endif; ?>
</ul>
<form method="post" class="d-flex mb-4">
<input type="hidden" name="section" value="<?= $sec ?>">
<input type="text" name="valore" class="form-control me-2" placeholder="Aggiungi nuovo...">
<button class="btn btn-primary">+</button>
</form>
</div>
<?php endforeach; ?>

<!-- Visite -->
<div class="section-block">
<h4>Visite</h4>
<table class="table table-sm">
<thead><tr><th>Data</th><th>Ora</th><th>Diagnosi</th><th>Farmaci/Consigli</th><th>Note</th></tr></thead>
<tbody>
<?php foreach (($patient['visite'] ?? []) as $v): ?>
<tr>
<td><?= $v['data'] ?></td>
<td><?= $v['ora'] ?></td>
<td><?= htmlspecialchars($v['diagnosi']) ?></td>
<td><?= htmlspecialchars($v['farmaci_consigliati']) ?></td>
<td><?= htmlspecialchars($v['note']) ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<form method="post" class="card p-3 mb-3 bg-light">
<input type="hidden" name="section" value="visite">

<div class="row mb-3">
<div class="col-md-6">
<label class="form-label">Data</label>
<input type="date" name="data_visita" class="form-control" required>
</div>
<div class="col-md-6">
<label class="form-label">Ora</label>
<input type="time" name="ora_visita" class="form-control" required>
</div>
</div>

<div class="mb-3">
<label class="form-label">Diagnosi</label>
<input type="text" name="diagnosi" class="form-control" placeholder="Inserisci diagnosi...">
</div>

<div class="mb-3">
<label class="form-label">Farmaci / Consigli</label>
<input type="text" name="farmaci_consigliati" class="form-control" placeholder="Farmaci prescritti o consigli...">
</div>

<div class="mb-3">
<label class="form-label">Note</label>
<textarea name="note" class="form-control" rows="2" placeholder="Note aggiuntive..."></textarea>
</div>

<div class="text-end">
<button class="btn btn-primary">Aggiungi visita</button>
</div>
</form>
</div>
</div>
</div>

<script>
setTimeout(() => {
const alertBox = document.getElementById('alert-success');
if(alertBox){
alertBox.classList.remove('show');
alertBox.classList.add('fade');
}
}, 3000);
</script>

</body>
</html>