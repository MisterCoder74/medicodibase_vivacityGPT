### MEDICO DI BASE
Versione creata con VivacityGPT Complete
da Alessandro Demontis per Vivacity Design Web Agency
29/30-12-2025

🤖 Analisi del Progetto AI
### Analisi del Repository GitHub: MisterCoder74/medicodibase_vivacityGPT
Analisi condotta tramite AI Repository Analyzer: https://www.vivacitydesign.net/vdgptscomplete/repo_analyzer.html

#### Scopo del Progetto
Il progetto "MEDICO DI BASE" è un’applicazione web progettata per gestire informazioni sui pazienti e prescrizioni mediche. È sviluppato con l’assistenza di VivacityGPT Complete, la piattaforma IA di Vivacity Design Web Agency.

#### Tipo di Applicazione
Questa applicazione è un sistema di gestione medica, focalizzato sull’amministrazione e sul monitoraggio dei dati dei pazienti, inclusi i dettagli personali e le prescrizioni.

#### Utenti Target
Gli utenti principali dell’applicazione sono professionisti sanitari, come medici e personale amministrativo delle strutture mediche, che necessitano di gestire in modo efficiente le cartelle cliniche dei pazienti.

#### Funzionalità Principali
1. Gestione Pazienti: consente di aggiungere, visualizzare e modificare le informazioni dei pazienti.
2. Gestione Prescrizioni: permette di creare e gestire prescrizioni, visualizzando i dettagli di quelle esistenti.
3. Gestione Pazienti Sensibili: funzionalità dedicate ai pazienti vulnerabili o ad alto rischio.
4. Archiviazione Dati: i dati di pazienti e prescrizioni sono salvati in formato JSON, una struttura leggera e facilmente gestibile dall’applicazione.

#### Stack Tecnologico
- Backend: l’applicazione utilizza PHP come linguaggio lato server (presenza di file *.php*).
- Archiviazione Dati: i dati di pazienti e prescrizioni vengono memorizzati in file JSON, prediligendo un approccio semplice alla gestione delle informazioni DB-agnostic.
- Frontend: i file PHP generano pagine HTML dinamiche per la visualizzazione dei dati tramite JS/Ajax. Bootstrap è scelto come template di base.

#### Possibili Casi d’Uso
1. Registrazione Pazienti: il personale sanitario può registrare nuovi pazienti inserendo i dati nel sistema.
2. Creazione Prescrizioni: i medici possono creare e gestire prescrizioni, mantenendo aggiornate le cartelle cliniche.
3. Visualizzazione Dettagli Paziente: gli utenti possono consultare informazioni dettagliate sul paziente, comprese prescrizioni e cronologia clinica.
4. Gestione Casi Sensibili: l’applicazione consente di trattare i casi che richiedono particolare riservatezza, in conformità con le normative sulla privacy sanitaria.

### Conclusione
L’applicazione "MEDICO DI BASE" è uno strumento utile per i professionisti sanitari che devono gestire in modo efficiente i dati dei pazienti e le prescrizioni. Grazie all’utilizzo di PHP e JSON, offre una soluzione pratica e leggera per gli ambienti medici implementabile in qulsiasi servizio hosting privo di DB.

🛠️ Tecnologie Utilizzate
- PHP, JS, Ajax, JSON, Bootstrap

📁 Struttura Principale dei File
📄 README.md
📄 add_patient.php
📁 data
 📄 patients.json
 📄 prescriptions.json
 📄 setup.json
📄 functions.php
📄 gestisci_prescrizioni.php
📄 index.php
📄 patient_detail.php
📄 patients.php
📄 pazienti_sensibili.php
📄 prescrizione_detail.php
