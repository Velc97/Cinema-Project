<!--Pagina contenente le informazioni dei film.-->
<?php include 'connessione.php' ?>

<?php /*Query che ottiena tutti i filmfilm. */
    $query ="SELECT * 
            FROM $NomeTabellaFilm F, $NomeTabellaRegista R, $NomeTabellaArtista A
            WHERE F.regista = R.ID
            AND R.ID = A.ID;";
			/*Effettuo la query sopra.*/
			if (!$risultatoQuery = mysqli_query($CONNESSIONE, $query))
				{
				 echo "Errore nell'esecuzione della query per i film. <br />" . mysqli_error($CONNESSIONE);
				 exit(); /*Termino lo script.*/
                }
    /*Stampo il risultato della query.*/
    $lista = "";
    while($riga = mysqli_fetch_array($risultatoQuery))
        {
        $lista .= "<tr>";

        $lista .= "<td>";
        $lista .= $riga['titolo'];
        $lista .= "</td>";

        $lista .= "<td>";
        $lista .= $riga['data_produzione'];
        $lista .= "</td>";

        $lista .= "<td>";
        $lista .= $riga['durata'];
        $lista .= "</td>";       

        $lista .= "<td>";
        $lista .= $riga['genere'];
        $lista .= "</td>";

        $lista .= "<td>";
        $lista .= $riga['nome'] . " " . $riga['cognome'];
        $lista .= "</td>";

        $lista .= "</tr>";
        }
?>


<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head> 
	<title> Cinema e dintorni - Film </title> <!--Titolo della pagina.-->
	<link rel="stylesheet" type="text/css" href="main.css" /> <!--Style principale.-->
	<link rel="icon" href="materials/img/iconaSito.png" /> <!--Icona del sito-->
</head>
<body>
    <?php include 'toolbar.php'; ?>
    <h1>I Film.</h1>
    <table class="catalogo" style="margin: 0 auto; border: 3px solid #3D9970; border-collapse: collapse;">
    <tr> 
        <td><h3>Titolo</h3></td> 
        <td><h3>Produzione</h3></td> 
        <td><h3>Durata</h3></td> 
        <td><h3>Genere</h3></td>
        <td><h3>Regista</h3></td>
    </tr>
        <?php echo $lista;?>
    </table>
    
    <!--Form per la ricerca del cast degli attori.-->
    <form action="<?php $_SERVER['PHP_SELF']?>" method="post">
        Cerca il cast di un film inserendone il titolo:<br />
        <span style="color: #0074D9;"> Titolo: </span> <input type="text" name="TitoloFilm" size="20"/>
        <?php 
            if (isset($_POST['TitoloFilm'])) 
                {
                 /*Query che cerca informazioni relative al film specificato.*/
                 $query = "SELECT A.nome, A.cognome, R.personaggio, R.ruolo
                 FROM $NomeTabellaArtista A, $NomeTabellaAttore ATT, $NomeTabellaFilm F, $NomeTabellaRecita R
                 WHERE A.ID = ATT.ID
                 AND ATT.ID = R.id_attore
                 AND F.ID = R.id_film
                 AND F.titolo = \"{$_POST['TitoloFilm']}\";";
                 /*Effettuo la query sopra.*/
                 if (!$risultatoQuery = mysqli_query($CONNESSIONE, $query))
                    {
                     echo "Errore nell'esecuzione della query per i registi. <br />" . mysqli_error($CONNESSIONE);
                     exit(); /*Termino lo script.*/
                    } 
                
                 if(mysqli_num_rows($risultatoQuery)==0)
                    {echo "<p style=\"color:red\"> Questo Film non &egrave presente nel nostro database.</p>";}
                 else /*Altrimenti procedo a stampare la lista del cast.*/
                       {
                        $lista = "<ul>";
                        while ($riga = mysqli_fetch_array($risultatoQuery))
                           {
                            $lista .= "<li>";
                            $lista .= $riga['nome'] . " " . $riga['cognome'] . " nel ruolo di " . $riga['personaggio'] . 
                                    " (". $riga['ruolo'] .").";
                            $lista .= "</li>";
                           }
                        $lista .= "</ul>";
                        echo "<br />Qui di seguito il cast del film " . $_POST['TitoloFilm'] . ":";
                        echo $lista;
                       }
                }
        ?>
    </form>
</body>
</html>