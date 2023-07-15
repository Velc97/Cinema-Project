<?php 

/*Effettuo la connessione.*/
/*Creo la connessione*/
$CONNESSIONE = mysqli_connect($nome, $username, $password);
/*Effettuo un controllo sulla connessione.*/
if(!$CONNESSIONE) /*Riscontro negativo.*/
	{die("Connessione fallita" . mysqli_connect_error());} /*Termino la connessione e stampo un messaggio di errore.*/

/*Cancello il database se necessario.*/
/*$var = "DROP DATABASE cinema_progetto;";
if(mysqli_query($CONNESSIONE, $var)) {}
else
    {echo "Errore nella cancellazione del database:" . mysqli_error($CONNESSIONE);}*/

/*Creo il database*/
$var = "CREATE DATABASE IF NOT EXISTS $NomeDB"; /*Memorizzo la query per la creazione del DB.*/
if(mysqli_query($CONNESSIONE, $var)) {}/*Caso di creazione effettuata con successo*/
else /*Caso di creazione effettuata con insuccesso*/
    {echo "Errore nella creazione del database:" . mysqli_error($CONNESSIONE);}

/*Seleziono il database.*/
mysqli_select_db($CONNESSIONE,$NomeDB);




/*Creazione delle tabelle.*/
$var = "CREATE TABLE IF NOT EXISTS Artista
    (
     ID CHAR(5) PRIMARY KEY, 
     cf CHAR(16) UNIQUE, /*Codice fiscale.*/ 
     data_nascita DATE, /*Data di nascita.*/
     nome VARCHAR(20), /*Nome.*/
     cognome VARCHAR(20) /*Cognome.*/
    );";
/*Check sulla creazione della tabella.*/
if(mysqli_query($CONNESSIONE, $var)) {}/*Caso di creazione effettuata con successo*/
else /*Caso di creazione effettuata con insuccesso*/
	{echo "Errore nella creazione della tabella $NomeTabellaArtista:" . mysqli_error($CONNESSIONE);}

$var = "CREATE TABLE IF NOT EXISTS Regista 
    (
     ID CHAR(5) PRIMARY KEY, 
     n_film INT, /*Numero di film prodotti.*/
     FOREIGN KEY (ID) REFERENCES Artista(ID)
    );";
/*Check sulla creazione della tabella.*/
if(mysqli_query($CONNESSIONE, $var)) {}/*Caso di creazione effettuata con successo*/
else /*Caso di creazione effettuata con insuccesso*/
    {echo "Errore nella creazione della tabella $NomeTabellaRegista:" . mysqli_error($CONNESSIONE);}

$var = "CREATE TABLE IF NOT EXISTS Attore
    (
     ID CHAR (5) PRIMARY KEY,
     FOREIGN KEY (ID) REFERENCES Artista(ID)
    );";
/*Check sulla creazione della tabella.*/
if(mysqli_query($CONNESSIONE, $var)) {}/*Caso di creazione effettuata con successo*/
else /*Caso di creazione effettuata con insuccesso*/
    {echo "Errore nella creazione della tabella $NomeTabellaAttore:" . mysqli_error($CONNESSIONE);}
    
$var = "CREATE TABLE IF NOT EXISTS Film
    (
     ID CHAR(5) PRIMARY KEY,
     titolo VARCHAR(30), /*Titolo.*/
     data_produzione DATE, /*Data di produzione*/ 
     durata INT, /*Durata in minuti, approssimata sempre per difetto.*/ 
     genere VARCHAR(10), /*Genere*/
     regista CHAR(5), /*Il regista del film.*/
     FOREIGN KEY (regista) REFERENCES Regista(ID),
     CHECK (genere IN ('Drammatico', 'Commedia', 'Thriller', 'Comico'))
    );";
/*Check sulla creazione della tabella.*/
if(mysqli_query($CONNESSIONE, $var)) {}/*Caso di creazione effettuata con successo*/
else /*Caso di creazione effettuata con insuccesso*/
	{echo "Errore nella creazione della tabella $NomeTabellaFilm:" . mysqli_error($CONNESSIONE);}

$var = "CREATE TABLE IF NOT EXISTS Recita
    (
     id_attore CHAR(5), /*ID dell'attore che recita nel film.*/  
     id_film CHAR(5), /*Film in cui recita l'attore.*/
     ruolo VARCHAR(20), /*Ruolo dell'attore nel film.*/
     personaggio VARCHAR(20), /*Personaggio interpetato dall'attore nel film.*/
     PRIMARY KEY (id_attore, id_film),
     FOREIGN KEY (id_attore) REFERENCES Attore(ID),
     FOREIGN KEY (id_film) REFERENCES Film(ID)
    );";
/*Check sulla creazione della tabella.*/
if(mysqli_query($CONNESSIONE, $var)) {}/*Caso di creazione effettuata con successo*/
else /*Caso di creazione effettuata con insuccesso*/
	{echo "Errore nella creazione della tabella $NomeTabellaRecita:" . mysqli_error($CONNESSIONE);}

$var = "CREATE TABLE IF NOT EXISTS Luogo
    (
     ID CHAR(5) PRIMARY KEY,
     citta VARCHAR(20) NOT NULL, /*Città.*/
     provincia VARCHAR(20) NOT NULL, /*Provincia.*/
     via VARCHAR(30), /*Via.*/
     civico INT /*Civico.*/
    );";
/*Check sulla creazione della tabella.*/
if(mysqli_query($CONNESSIONE, $var)) {}/*Caso di creazione effettuata con successo*/
else /*Caso di creazione effettuata con insuccesso*/
	{echo "Errore nella creazione della tabella $NomeTabellaLuogo:" . mysqli_error($CONNESSIONE);}

$var = "CREATE TABLE IF NOT EXISTS Cinema
    (
     ID CHAR(5) PRIMARY KEY, 
     Piva CHAR(11) UNIQUE, /*Partita iva.*/
     nome VARCHAR(30), /*Nome.*/
     telefono CHAR(10), /*Recapito telefonico.*/
     id_luogo CHAR(5), /*ID del luogo in cui si trova il cinema.*/
     FOREIGN KEY (id_luogo) REFERENCES Luogo(ID)
    );";
/*Check sulla creazione della tabella.*/
if(mysqli_query($CONNESSIONE, $var)) {}/*Caso di creazione effettuata con successo*/
else /*Caso di creazione effettuata con insuccesso*/
	{echo "Errore nella creazione della tabella $NomeTabellaCinema:" . mysqli_error($CONNESSIONE);}

$var = "CREATE TABLE IF NOT EXISTS Sala
    (
     ID CHAR(5) PRIMARY KEY, 
     nome VARCHAR(20), /*Nome.*/
     numero VARCHAR(10), /*Numero.*/
     numero_posti INT NOT NULL, /*Numero di posti,*/
     id_Cinema CHAR(5), /*Cinema in cui si trova la sala.*/
     FOREIGN KEY (id_Cinema) REFERENCES Cinema(ID)
    );";
/*Check sulla creazione della tabella.*/
if(mysqli_query($CONNESSIONE, $var)) {}/*Caso di creazione effettuata con successo*/
else /*Caso di creazione effettuata con insuccesso*/
	{echo "Errore nella creazione della tabella $NomeTabellaSala:" . mysqli_error($CONNESSIONE);}

$var = "CREATE TABLE IF NOT EXISTS Proiezione
    (
    ID CHAR(5) PRIMARY KEY, 
    dataora_proiezione DATETIME NOT NULL, /*Data e ora della proiezione.*/
    id_Film CHAR(5), /*Film che viene proiettato.*/
    id_Sala CHAR(5), /*Sala in cui avviene la proiezione.*/
    FOREIGN KEY (id_Film) REFERENCES Film(ID),
    FOREIGN KEY (id_Sala) REFERENCES Sala(ID)
    );";
/*Check sulla creazione della tabella.*/
if(mysqli_query($CONNESSIONE, $var)) {}/*Caso di creazione effettuata con successo*/
else /*Caso di creazione effettuata con insuccesso*/
    {echo "Errore nella creazione della tabella $NomeTabellaProiezione:" . mysqli_error($CONNESSIONE);}




/*Inserimento dei dati sulle tabelle.*/
$var = "INSERT IGNORE INTO Artista (ID, cf, data_nascita, nome, cognome) VALUES
('00000', '0000000000000000', '1942-11-17', 'Martin', 'Scorsese'),
('00001', '0000000000000001', '1940-04-25', 'Al', 'Pacino'),
('00002', '0000000000000002', '1943-08-17', 'Robert', 'De Niro'),
('00003', '0000000000000003', '1943-02-09', 'Joe', 'Pesci'),
('00004', '0000000000000004', '1974-11-11', 'Leonardo', 'DiCaprio'),
('00005', '0000000000000005', '1990-07-02', 'Margot', 'Robbie'),
('00006', '0000000000000006', '1983-12-20', 'Jonah', 'Hill'),
('00007', '0000000000000007', '1967-07-22', 'Mark', 'Ruffalo'),
('00008', '0000000000000008', '1943-12-31', 'Ben', 'Kingsley'),
('00009', '0000000000000009', '1970-08-08', 'Matt', 'Damon'),
('00010', '0000000000000010', '1937-08-22', 'Jack', 'Nicholson'),
('00011', '0000000000000011', '1967-02-10', 'Vince', 'Gilligan'),
('00012', '0000000000000012', '1979-09-27', 'Aaron', 'Paul'),
('00013', '0000000000000013', '1981-12-16', 'Krysten','Ritter'),
('00014', '0000000000000014', '1963-04-27', 'Quentin', 'Tarantino'),
('00015', '0000000000000015', '1954-02-18', 'John', 'Travolta');";
if(!mysqli_query($CONNESSIONE, $var)) /*Check sul popolamento.*/
    {echo "Errore nell'inserimento dati di ". $NomeTabellaArtista . mysqli_error($CONNESSIONE);}		

$var = "INSERT IGNORE INTO Regista (ID, n_film) VALUES
('00000', '4'), ('00011', '1'), ('00014', '1');";
if(!mysqli_query($CONNESSIONE, $var)) /*Check sul popolamento.*/
    {echo "Errore nell'inserimento dati di ". $NomeTabellaRegista . mysqli_error($CONNESSIONE);}	

$var = "INSERT IGNORE INTO Attore (ID) VALUES
('00001'), ('00002'), ('00003'), ('00004'), ('00005'), ('00006'), ('00007'), ('00008'), ('00010'),
('00009'), ('00012'), ('00013'), ('00014'), ('00015');";
if(!mysqli_query($CONNESSIONE, $var)) /*Check sul popolamento.*/
    {echo "Errore nell'inserimento dati di ". $NomeTabellaAttore . mysqli_error($CONNESSIONE);}	

$var = "INSERT IGNORE INTO Film (ID, titolo, data_produzione, durata, genere, regista) VALUES
('10000', 'The Irish Man', '2019-11-04', '210', 'Drammatico', '00000'),
('10001', 'The Wolf of Wall Street', '2014-01-23', '200', 'Commedia' , '00000'),
('10002', 'Shutter Island', '2010-05-02', '148', 'Thriller', '00000'),
('10003', 'The Departed', '2006-01-10', '152', 'Drammatico', '00000'),
('10004', 'El Camino', '2019-11-20', '122', 'Drammatico', '00011'),
('10005', 'Pulp Fiction', '1994-12-16', '153', 'Thriller', '00014');";
if(!mysqli_query($CONNESSIONE, $var)) /*Check sul popolamento.*/
    {echo "Errore nell'inserimento dati di ". $NomeTabellaFilm . mysqli_error($CONNESSIONE);}	

$var = "INSERT IGNORE INTO Recita (id_attore, id_film, ruolo, personaggio) VALUES
('00001', '10000', 'Non Protagonista', 'Jimmy Hoffa'),
('00002', '10000', 'Protagonista', 'Frank Sheeran'),
('00003', '10000', 'Non Protagonista', 'Russell Bufalino'),
('00004', '10001', 'Protagonista', 'Jordan Belfort'),
('00005', '10001', 'Non Protagonista', 'Naomi Lapaglia'),
('00006', '10001', 'Non Protagonista', 'Donnie Azoff'),
('00004', '10002', 'Protagonista', 'Teddy Daniels'),
('00007', '10002', 'Non protagonista', 'Chuck Aule'), 
('00008', '10002', 'Comparsa', 'John Cawley'),
('00004', '10003', 'Non Protagonista', 'Billy Costigan'),
('00009', '10003', 'Protagonista', 'Colin Sullivan'),
('00010', '10003', 'Comparsa', 'Frank Costello'),
('00012', '10004', 'Protagonista', 'Jesse Pinkman'),
('00013', '10004', 'Non Protagonista','Jane Margolis'),
('00014', '10005', 'Comparsa', 'Jimmy'),
('00015', '10005', 'Protagonista', 'Vincent Vega');";
if(!mysqli_query($CONNESSIONE, $var)) /*Check sul popolamento.*/
    {echo "Errore nell'inserimento dati di ". $NomeTabellaRecita . mysqli_error($CONNESSIONE);}	

$var = "INSERT IGNORE INTO Luogo (ID, citta, provincia, via, civico) VALUES
('20000', 'Formia', 'Latina', 'Olivastro Spaventola', '30'),
('20001', 'Melzo', 'Milano', 'De Gasperi', '5'),
('20002', 'Colonnella', 'Teramo', 'Mazzini', '20'),
('20003', 'Lecce', 'Lecce', 'Garibaldi', '35');";
if(!mysqli_query($CONNESSIONE, $var)) /*Check sul popolamento.*/
    {echo "Errore nell'inserimento dati di ". $NomeTabellaLuogo . mysqli_error($CONNESSIONE);}	

$var = "INSERT IGNORE INTO Cinema (ID, Piva, nome, telefono, id_Luogo) VALUES
('30000', '00000000000', 'Multisala del Mare', '3334567890', '20000'),
('30001', '00000000001', 'Arcadia', '4334567890', '20001'),
('30002', '00000000002', 'Cineplex Arcobaleno', '4734567890','20002'),
('30003', '00000000003', 'Massimo', '1334567850' ,'20003');";
if(!mysqli_query($CONNESSIONE, $var)) /*Check sul popolamento.*/
    {echo "Errore nell'inserimento dati di ". $NomeTabellaCinema . mysqli_error($CONNESSIONE);}	

$var = "INSERT IGNORE INTO Sala (ID, nome, numero, numero_posti, id_Cinema) VALUES
('40000', 'Fellini', '1', '30', '30000'),
('40001', 'Leone', '2', '40', '30000'),
('40002', 'Pasolini', '3', '40', '30000'),
('40003', 'Bertolucci', '4', '40', '30000'),
('40004', 'Leone', '1', '10', '30001'),
('40005', 'Pasolini', '2', '20', '30001'),
('40006', 'Bertolucci', '1', '10', '30002'),
('40007', 'Fellini', '1', '100', '30003'),
('40008', 'Battisti', '2', '100', '30003');";
if(!mysqli_query($CONNESSIONE, $var)) /*Check sul popolamento.*/
    {echo "Errore nell'inserimento dati di ". $NomeTabellaSala . mysqli_error($CONNESSIONE);}	

$var = "INSERT IGNORE INTO Proiezione (ID, dataora_proiezione, id_Film, id_Sala) VALUES
('50000', '2020-05-01 17:00:00', '10000', '40000'),
('50001', '2020-05-01 20:00:00', '10000', '40000'),
('50002', '2020-05-01 23:00:00', '10000', '40000'),
('50003', '2020-05-01 17:00:00', '10001', '40001'),
('50004', '2020-05-01 20:00:00', '10002', '40001'),
('50005', '2020-05-02 17:00:00', '10002', '40008'),
('50006', '2020-05-02 17:00:00', '10002', '40006'),
('50007', '2018-03-03 17:00:00', '10002', '40006'),
('50008', '2017-02-10 20:00:00', '10005', '40003');";
if(!mysqli_query($CONNESSIONE, $var)) /*Check sul popolamento.*/
    {echo "Errore nell'inserimento dati di ". $NomeTabellaProeizione . mysqli_error($CONNESSIONE);}	

?>