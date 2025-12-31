<?php
$id = $_GET['id'] ?? '';
$file = __DIR__ . '/data/prescriptions.json';
if (!file_exists($file)) die("File prescrizioni non trovato.");

$all = json_decode(file_get_contents($file), true);
$prescrizione = null;
foreach($all as $p){
if($p['id'] == $id){ $prescrizione = $p; break; }
}
if (!$prescrizione) die("Prescrizione non trovata.");
?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<title>Prescrizione - <?= htmlspecialchars($prescrizione['paziente_nome']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background:#f8f9fa; }
@media print {
.no-print { display: none !important; }
body { background: white; }
}
</style>        
</head>
<body class="p-4 bg-light">
            <header class="header">
        <div class="container">
            <h1><i class="bi bi-heart-pulse-fill"></i> Studio Medico</h1>
            <p class="lead">Sistema di Gestione Ambulatorio</p>
        </div>
    </header>
<div class="container bg-white p-4 shadow-sm">
<!-- Barra superiore visibile solo a schermo -->
<div class="no-print mb-3 d-flex justify-content-between align-items-center">
<a href="gestisci_prescrizioni.php" class="btn btn-secondary">&laquo; Torna all'elenco</a>
<button class="btn btn-outline-primary" onclick="window.print()">🖨️ Stampa / Esporta PDF</button>
</div>

<h2 class="mb-3 text-center text-primary">Prescrizione Medica</h2>
<hr>
<h2 class="mb-3">Dettaglio Prescrizione</h2>
<hr>
<p><strong>Data:</strong> <?= $prescrizione['data'] ?><br>
<strong>Paziente:</strong> <?= htmlspecialchars($prescrizione['paziente_nome']) ?><br>
<strong>Codice Fiscale:</strong> <?= htmlspecialchars($prescrizione['paziente_cf']) ?></p>

<p><strong>Tipo Prescrizione:</strong> <?= htmlspecialchars($prescrizione['tipo']) ?><br>
<strong>Esenzione:</strong> <?= htmlspecialchars($prescrizione['esenzione']) ?></p>

<p><strong>Farmaco / Prestazione:</strong> <?= htmlspecialchars($prescrizione['farmaco']) ?><br>
<strong>Quantità:</strong> <?= htmlspecialchars($prescrizione['quantita']) ?><br>
<strong>Confezioni:</strong> <?= htmlspecialchars($prescrizione['confezioni']) ?><br>
<strong>Note Farmaco:</strong> <?= htmlspecialchars($prescrizione['note_farmaco']) ?></p>

<p><strong>Diagnosi:</strong> <?= htmlspecialchars($prescrizione['diagnosi']) ?></p>

<p><strong>Medico:</strong> <?= htmlspecialchars($prescrizione['medico_nome']) ?><br>
<strong>Codice Medico:</strong> <?= htmlspecialchars($prescrizione['medico_cf']) ?><br>
<strong>Note del Medico:</strong> <?= htmlspecialchars($prescrizione['note_medico']) ?></p>


</div>
</body>
</html>