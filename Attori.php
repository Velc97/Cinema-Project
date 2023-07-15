<!--Pagina che mostra gli attori nel database.-->
<?php include 'connessione.php' ?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head> 
	<title> Cinema e dintorni - Attori </title> <!--Titolo della pagina.-->
	<link rel="stylesheet" type="text/css" href="main.css" /> <!--Style principale.-->
	<link rel="icon" href="materials/img/iconaSito.png" /> <!--Icona del sito-->
</head>
<body>
    <?php include 'toolbar.php'?>
    <h1>Gli attori.</h1>
    <table class="catalogo" style="margin: 0 auto; border: 3px solid #3D9970; border-collapse: collapse;">
    <tr>
        <td><h3>Nome</h3></td> 
        <td><h3>Cognome</h3></td> 
        <td><h3>Data di nascita.</h3></td> 
    </tr>
    <?php
        /*Query sql che stampa tutti gli attori sul database.*/
        $query = "SELECT *
        FROM $NomeTabellaAttore ATT, $NomeTabellaArtista A
        WHERE ATT.ID = A.ID;";
        if (!$risultatoQuery = mysqli_query($CONNESSIONE, $query))
            {
             echo "Errore nell'esecuzione della query per gli attori. <br />" . mysqli_error($CONNESSIONE);
             exit(); /*Termino lo script.*/
            }
        /*Stampo il risultato della query.*/
        $lista = "";
        
        while ($riga = mysqli_fetch_array($risultatoQuery))
            {
             $lista .= "<tr>";
             $lista .= "<td>";
             $lista .= $riga['nome'];
             $lista .= "</td>";

             $lista .= "<td>";   
             $lista .= $riga['cognome'];
             $lista .= "</td>";

             $lista .= "<td>";   
             $lista .= $riga['data_nascita'];
             $lista .= "</td>";
             $lista .= "</tr>";
            }
        echo $lista;
    ?>
    </table>
    <form action="<?php $_SERVER['PHP_SELF']?>" method="post">
	Cerca i film in cui recita un attore inserendone il cognome: <br />
    <span style="color: #0074D9;"> Cognome: </span> <input type="text" name="CognomeAttore" size="20"/>
    </form>
    <?php if (isset($_POST['CognomeAttore'])) 
                {
                 /*Query che cerca informazioni relative al regista cercato.*/
                 $query ="CREATE OR REPLACE VIEW ListaFilmAttore AS
                          SELECT F.titolo, R.personaggio
                          FROM $NomeTabellaAttore ATT, $NomeTabellaRecita R, $NomeTabellaFilm F, $NomeTabellaArtista A
                          WHERE ATT.ID = R.id_attore
                          AND R.id_film = F.ID
                          AND A.ID = ATT.ID
                          AND A.cognome = \"{$_POST['CognomeAttore']}\";";
                          /*Effettuo la query sopra.*/
                          if (!$risultatoQuery = mysqli_query($CONNESSIONE, $query))
                            {
                             echo "Errore nell'esecuzione della query per i registi. <br />" . mysqli_error($CONNESSIONE);
                             exit(); /*Termino lo script.*/
                            } 
                $query = "SELECT * FROM ListaFilmAttore;";
                /*Effettuo la query sopra.*/
                if (!$risultatoQuery = mysqli_query($CONNESSIONE, $query))
                    {
                     echo "Errore nell'esecuzione della query per i registi. <br />" . mysqli_error($CONNESSIONE);
                     exit(); /*Termino lo script.*/
                    } 
                
                if (mysqli_num_rows($risultatoQuery)==0)
                    {echo "<p style=\"color:red\"> Questo attore non &egrave; presente nel nostro database.</p>"; exit();}
                else
                    {
                      $lista = "I film in cui recita " . $_POST['CognomeAttore'] . " sono:";
                      $lista .= "<ul>";
                      while($riga = mysqli_fetch_array($risultatoQuery))
                            {
                             $lista .= "<li>";
                             $lista .= $riga['titolo'] . " nel ruolo di " . $riga['personaggio']; 
                             $lista .= "</li>";
                            }
                      $lista .= "</ul>"; 
                      $query = "SELECT COUNT(*) AS n_film FROM ListaFilmAttore";
                      /*Effettuo la query sopra.*/
                      if (!$risultatoQuery = mysqli_query($CONNESSIONE, $query))
                        {
                         echo "Errore nell'esecuzione della query per i registi. <br />" . mysqli_error($CONNESSIONE);
                         exit(); /*Termino lo script.*/
                        } 
                      $riga = mysqli_fetch_array($risultatoQuery);
                      $lista .= "Per un totale di " . $riga['n_film'] . " film.";
                      echo $lista;  
                    }
                }
    ?>
</body>
</html>