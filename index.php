<?php
include 'functions.php';
$patients = loadPatients();
$total = count($patients);
?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<title>Dashboard - Studio Medico</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
body { background-color:#f8f9fa; }
.card-dashboard {
min-width: 250px;
margin: 10px;
text-align: center;
padding: 20px;
}
.cards-container {
display: flex;
flex-wrap: wrap;
justify-content: center;
gap: 1rem;
}
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
<h2 class="mb-4">Dashboard</h2>

<div class="cards-container">
<!-- Card: Pazienti registrati -->
<div class="card card-dashboard shadow-sm">
<h5>Pazienti registrati</h5>
<p class="display-6 mt-3"><?php echo $total; ?></p>
<a href="patients.php" class="btn btn-primary">Gestisci Anagrafica</a>
</div>

<!-- Card: Gestisci Prescrizioni -->
<div class="card card-dashboard shadow-sm">
<h5>Gestisci Prescrizioni</h5>
<div class="mt-3">
<i class="bi bi-file-earmark-medical-fill" style="font-size:2rem; color: #0d6efd;"></i>
</div>
<a href="gestisci_prescrizioni.php" class="btn btn-success mt-3">Apri</a>
</div>

<!-- Card: Pazienti sensibili -->
<div class="card card-dashboard shadow-sm">
<h5>Gestisci Pazienti Sensibili</h5>
<div class="mt-3">
<i class="bi bi-shield-lock-fill" style="font-size:2rem; color: #dc3545;"></i>
</div>
<a href="pazienti_sensibili.php" class="btn btn-danger mt-3">Apri</a>
</div>
</div>

</div>

</body>
</html>