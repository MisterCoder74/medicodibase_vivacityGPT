<?php
date_default_timezone_set('Europe/Rome');
include 'functions.php';

$prescriptionsFile = __DIR__ . '/data/prescriptions.json';
$setupFile = __DIR__ . '/data/setup.json';
$patients = loadPatients();

// ▪️ Carica setup medico
$setup = [];
if (file_exists($setupFile)) {
$setup = json_decode(file_get_contents($setupFile), true);
}

// ▪️ Carica prescrizioni
$prescriptions = [];
if (file_exists($prescriptionsFile)) {
$prescriptions = json_decode(file_get_contents($prescriptionsFile), true) ?: [];
}

// ▪️ Salvataggio nuova prescrizione
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'new') {
$new = [
'id' => time(),
'data' => $_POST['data_prescrizione'],
'paziente_nome' => trim($_POST['paziente_nome']),
'paziente_cf' => strtoupper(trim($_POST['paziente_cf'])),
'tipo' => $_POST['tipo_prescrizione'],
'esenzione' => $_POST['esenzione'],
'farmaco' => $_POST['farmaco'],
'quantita' => $_POST['quantita'],
'confezioni' => $_POST['confezioni'],
'note_farmaco' => $_POST['note_farmaco'],
'diagnosi' => $_POST['diagnosi'],
'medico_nome' => $setup['nome_medico'] ?? '',
'medico_cf' => $setup['codice_fiscale'] ?? '',
'note_medico' => $_POST['note_medico'] ?? ''
];

$prescriptions[] = $new;
file_put_contents($prescriptionsFile, json_encode($prescriptions, JSON_PRETTY_PRINT));

header("Location: gestisci_prescrizioni.php?success=1");
exit;
}

$success = isset($_GET['success']);
?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<title>Gestisci Prescrizioni</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<style>body{background:#f8f9fa;}</style>
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

<div class="d-flex justify-content-between align-items-center mb-3">
<h1>Gestisci Prescrizioni</h1>
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuovaPrescrizione">
<i class="bi bi-plus-circle"></i> Nuova Prescrizione
</button>
</div>

<?php if ($success): ?>
<div id="alert-success" class="alert alert-success alert-dismissible fade show" role="alert">
✅ Prescrizione salvata correttamente.
<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="card shadow-sm">
<div class="card-header bg-primary text-white">Elenco Prescrizioni Rilasciate</div>
<div class="card-body">
<table class="table table-striped align-middle">
<thead>
<tr>
<th>Data</th><th>Paziente</th><th>Codice Fiscale</th>
<th>Tipo</th><th>Farmaco</th><th>Quantità</th><th>Note</th><th>Azioni</th>
</tr>
</thead>
<tbody>
<?php if (empty($prescriptions)): ?>
<tr><td colspan="8" class="text-center text-muted">Nessuna prescrizione presente.</td></tr>
<?php else: foreach (array_reverse($prescriptions) as $p): ?>
<tr>
<td><?= htmlspecialchars($p['data']) ?></td>
<td><?= htmlspecialchars($p['paziente_nome']) ?></td>
<td><?= htmlspecialchars($p['paziente_cf']) ?></td>
<td><?= htmlspecialchars($p['tipo']) ?></td>
<td><?= htmlspecialchars($p['farmaco']) ?></td>
<td><?= htmlspecialchars($p['quantita']) ?></td>
<td><?= htmlspecialchars($p['note_farmaco']) ?></td>
<td>
<a href="prescrizione_detail.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary">
<i class="bi bi-eye"></i>
</a>
</td>
</tr>
<?php endforeach; endif; ?>
</tbody>
</table>
</div>
</div>
</div>

<!-- 🔹 MODALE NUOVA PRESCRIZIONE -->
<div class="modal fade" id="modalNuovaPrescrizione" tabindex="-1">
<div class="modal-dialog modal-lg">
<form method="post" class="modal-content">
<input type="hidden" name="action" value="new">
<div class="modal-header bg-primary text-white">
<h5 class="modal-title">Nuova Prescrizione Medica</h5>
<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
<!-- Sezione Paziente -->
<h6 class="border-bottom pb-2 mb-3 text-primary">
<i class="bi bi-person-fill"></i> Paziente
</h6>
<div class="row mb-3">
<div class="col-md-8">
<label class="form-label">Seleziona Paziente</label>
<select id="selectPaziente" class="form-select">
<option value="">-- Seleziona --</option>
<?php foreach($patients as $pat): ?>
<option value="<?= htmlspecialchars($pat['codice_fiscale']) ?>" data-nome="<?= htmlspecialchars($pat['nome'].' '.$pat['cognome']) ?>">
<?= htmlspecialchars($pat['nome'].' '.$pat['cognome'].' ('.$pat['codice_fiscale'].')') ?>
</option>
<?php endforeach; ?>
</select>
</div>
</div>
<div class="row mb-3">
<div class="col-md-6">
<label class="form-label">Nome e Cognome</label>
<input type="text" name="paziente_nome" id="paziente_nome" class="form-control" readonly required>
</div>
<div class="col-md-6">
<label class="form-label">Codice Fiscale</label>
<input type="text" name="paziente_cf" id="paziente_cf" class="form-control" readonly required>
</div>
</div>

<h6 class="border-bottom pb-2 mb-3 text-primary"><i class="bi bi-file-earmark-medical"></i> Prescrizione</h6>
<div class="mb-3">
<label class="form-label">Data</label>
<input type="date" name="data_prescrizione" class="form-control" value="<?= date('Y-m-d') ?>" required>
</div>
<div class="row mb-3">
<div class="col-md-6">
<label class="form-label">Tipo Prescrizione</label>
<select name="tipo_prescrizione" class="form-select">
<option>Farmaceutica</option><option>Diagnostica</option><option>Specialistica</option><option>Altro</option>
</select>
</div>
<div class="col-md-6">
<label class="form-label">Esenzione</label>
<select name="esenzione" class="form-select">
<option>Non esente</option><option>Reddito</option><option>Patologia</option><option>Altro</option>
</select>
</div>
</div>
<div class="mb-3">
<label class="form-label">Farmaco / Prestazione</label>
<input type="text" name="farmaco" class="form-control" required>
</div>
<div class="row mb-3">
<div class="col-md-3">
<label class="form-label">Quantità</label>
<input type="number" name="quantita" class="form-control" value="1" min="1">
</div>
<div class="col-md-3">
<label class="form-label">Confezioni</label>
<input type="number" name="confezioni" class="form-control" value="1" min="1">
</div>
<div class="col-md-6">
<label class="form-label">Note</label>
<input type="text" name="note_farmaco" class="form-control">
</div>
</div>
<div class="mb-3">
<label class="form-label">Diagnosi</label>
<textarea name="diagnosi" class="form-control" rows="2"></textarea>
</div>

<h6 class="border-bottom pb-2 mb-3 text-primary"><i class="bi bi-person-vcard"></i> Medico</h6>
<div class="row mb-3">
<div class="col-md-6">
<label class="form-label">Nome e Cognome</label>
<input type="text" name="medico_nome" class="form-control"
value="<?= htmlspecialchars($setup['nome_medico'] ?? '') ?>" readonly>
</div>
<div class="col-md-6">
<label class="form-label">Codice Fiscale</label>
<input type="text" name="medico_cf" class="form-control"
value="<?= htmlspecialchars($setup['codice_fiscale'] ?? '') ?>" readonly>
</div>
</div>
<div class="mb-3">
<label class="form-label">Note del Medico</label>
<textarea name="note_medico" class="form-control" rows="2"></textarea>
</div>
</div>

<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
<button type="submit" class="btn btn-success">Salva Prescrizione</button>
</div>
</form>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('selectPaziente').addEventListener('change', function(){
const opt = this.options[this.selectedIndex];
document.getElementById('paziente_nome').value = opt.dataset.nome || '';
document.getElementById('paziente_cf').value = opt.value || '';
});
setTimeout(()=>{const a=document.getElementById('alert-success');if(a){a.classList.remove('show');a.classList.add('fade');}},3000);
</script>
</body>
</html>