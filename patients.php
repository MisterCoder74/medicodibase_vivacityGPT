<?php
include 'functions.php';
$patients = loadPatients();
?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<title>Anagrafica Pazienti</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
    <header class="header">
        <div class="container">
            <h1><i class="bi bi-heart-pulse-fill"></i> Studio Medico</h1>
            <p class="lead">Sistema di Gestione Ambulatorio</p>
        </div>
    </header>
<div class="container">
<h1 class="mb-4">Anagrafica Pazienti</h1>
<div class="mb-3">
<a href="index.php" class="btn btn-secondary">&laquo; Torna alla Dashboard</a> <a href="add_patient.php" class="btn btn-success">+ Nuovo Paziente</a>
</div>        
<table class="table table-striped">
<thead>
<tr>
<th>Codice Fiscale</th>
<th>Nome</th>
<th>Cognome</th>
<th>Data di Nascita</th>
<th>Dettaglio</th>
</tr>
</thead>
<tbody>
<?php foreach ($patients as $p): ?>
<tr>
<td><?php echo $p['codice_fiscale']; ?></td>
<td><?php echo $p['nome']; ?></td>
<td><?php echo $p['cognome']; ?></td>
<td><?php echo $p['data_nascita']; ?></td>
<td><a class="btn btn-sm btn-primary" href="patient_detail.php?id=<?php echo $p['codice_fiscale']; ?>">Apri</a></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

</body>
</html>