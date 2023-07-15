<!--Pagina contenente le informazioni dei registi.-->
<?php include 'connessione.php' ?>

<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head> 
	<title> Cinema e dintorni - Registi </title> <!--Titolo della pagina.-->
	<link rel="stylesheet" type="text/css" href="main.css" /> <!--Style principale.-->
	<link rel="icon" href="materials/img/iconaSito.png" /> <!--Icona del sito-->
</head>
<body>
    <?php include 'toolbar.php'; ?>
        <h1>I registi.</h1>

    <table class="catalogo" style="margin: 0 auto; border: 3px solid #3D9970; border-collapse: collapse;">
        <tr>
            <td><h3>Nome </h3></td> 
            <td><h3>Cognome</h3></td> 
            <td><h3>Data di nascita</h3></td> 
            <td><h3>Numero di film prodotti</h3></td> 
        </tr>
        <tr>
        <?php 
        /*Query che  mostra tutti i registi in ordine ascendente di cognome.*/
        $query ="SELECT nome, cognome, data_nascita, n_film
			     FROM $NomeTabellaArtista A, $NomeTabellaRegista R
                 WHERE A.ID = R.ID
                 ORDER BY cognome ASC;";
				 /*Effettuo la query sopra.*/
				 if (!$risultatoQuery = mysqli_query($CONNESSIONE, $query))
					{
					 echo "Errore nell'esecuzione della query per i registi. <br />" . mysqli_error($CONNESSIONE);
					 exit(); /*Termino lo script.*/
                    }
        /*Stampo il risultato della query.*/
        $lista = "";
        while($riga = mysqli_fetch_array($risultatoQuery))
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

            $lista .= "<td>";
            $lista .= $riga['n_film'];
            $lista .= "</td>";

            $lista .= "</tr>";
            }
        echo $lista; 
        ?>
        </tr>
    </table>
    <br />
    <!--Form per la ricerca di informazioni relative -->
    <form action="<?php $_SERVER['PHP_SELF']?>" method="post">
		Cerca i film prodotti da un regista inserendone il cognome: <br />
        <span style="color: #0074D9;"> Cognome: </span> <input type="text" name="CognomeRegista" size="20"/>
        <?php
                if (isset($_POST['CognomeRegista'])) 
                {
                 /*Query che cerca informazioni relative al regista cercato.*/
                 $query ="SELECT F.titolo, F.data_produzione, F.durata, F.genere
                          FROM $NomeTabellaRegista R, $NomeTabellaFilm F, $NomeTabellaArtista A
                          WHERE R.ID = A.ID
                          AND F.regista = R.ID
                          AND A.cognome = \"{$_POST['CognomeRegista']}\";";
                          /*Effettuo la query sopra.*/
                          if (!$risultatoQuery = mysqli_query($CONNESSIONE, $query))
                            {
                             echo "Errore nell'esecuzione della query per i registi. <br />" . mysqli_error($CONNESSIONE);
                             exit(); /*Termino lo script.*/
                            } 
                
                 if (mysqli_num_rows($risultatoQuery)==0)
                    {echo "<p style=\"color:red\"> Questo regista non &egrave; presente nel nostro database.</p>";}
                 else
                    {
                     $lista = "<ul>";
                     while ($riga = mysqli_fetch_array($risultatoQuery))
                        {       
                         $lista .= "<li>";
                         $lista .= $riga['titolo'] . ", " . $riga['data_produzione']  . ", " . $riga['durata'] . " minuti, " . $riga['genere'];
                         $lista .= "</li>";
                        }
                     echo "<br />Qui di seguito i film prodotti da" . $_POST['CognomeRegista'] . ":";
                     $lista .= "</ul>";
                     echo $lista;
                    }

                }
        ?>
	</form>
</body>
</html>