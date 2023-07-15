<!--Pagina che mostra le proiezioni dei film, cont tanto di orario, nome del cinema e città-->
<?php include 'connessione.php' ?>
<?php 
        /*Vista per le proiezioni di film.*/
        $query ="CREATE OR REPLACE VIEW ProiezioniVista AS
                 SELECT C.nome  AS NomeCinema, P.dataora_proiezione, F.titolo AS NomeFilm, L.citta
			     FROM $NomeTabellaProiezione P, $NomeTabellaSala S, $NomeTabellaCinema C, $NomeTabellaFilm F, $NomeTabellaLuogo L
                 WHERE S.ID = P.id_Sala
                 AND C.ID = S.id_Cinema
                 AND F.ID = P.id_Film
                 AND C.id_luogo = L.ID;";
				 /*Effettuo la query sopra.*/
				 if (!$risultatoQuery = mysqli_query($CONNESSIONE, $query))
					{
					 echo "Errore nell'esecuzione della query per le proiezioni. <br />" . mysqli_error($CONNESSIONE);
					 exit(); /*Termino lo script.*/
                    }
?>

<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head> 
	<title> Cinema e dintorni - Proiezioni </title> <!--Titolo della pagina.-->
	<link rel="stylesheet" type="text/css" href="main.css" /> <!--Style principale.-->
	<link rel="icon" href="materials/img/iconaSito.png" /> <!--Icona del sito-->
</head>
<body>
<?php include 'toolbar.php'; ?>
    <h1>Le prossime proiezioni.</h1>
    
    <table class="catalogo" style="margin: 0 auto; border: 3px solid #3D9970; border-collapse: collapse;">
    <tr>
        <td><h3>Nome del cinema</h3></td> 
        <td><h3>Date e ora</h3></td> 
        <td><h3>Film</h3></td> 
        <td><h3>Citt&agrave;</h3></td> 
    </tr>

        <?php 
        /*Query che  mostra le proiezioni future di film, a partire da quella temporalmente più vicina.*/
        $query = "SELECT * 
                  FROM ProiezioniVista  
                  WHERE dataora_proiezione > CURRENT_TIMESTAMP
                  ORDER BY dataora_proiezione ASC;";
				 if (!$risultatoQuery = mysqli_query($CONNESSIONE, $query))
                 {
                  echo "Errore nell'esecuzione della query per le proiezioni. <br />" . mysqli_error($CONNESSIONE);
                  exit(); /*Termino lo script.*/
                 }
        /*Stampo il risultato della query.*/
        $lista = "";
        while($riga = mysqli_fetch_array($risultatoQuery))
            {
             $lista .= "<tr>";

             $lista .= "<td>";
             $lista .= $riga['NomeCinema'];
             $lista .= "</td>";

             $lista .= "<td>";
             $lista .= $riga['dataora_proiezione'];
             $lista .= "</td>";

             $lista .= "<td>";
             $lista .= $riga['NomeFilm'];
             $lista .= "</td>";       

             $lista .= "<td>";
             $lista .= $riga['citta'];
             $lista .= "</td>";

             $lista .= "</tr>";
            }
        echo $lista; 
        ?>
    </table>
    <?php echo "<br /> Ricerca effettuata il " . date("Y/m/d") . " alle ore " . date("h:i:sa");?>
    <br />

    <!--Bottone per le proiezioni passate.-->
    <form action="<?php $_SERVER['PHP_SELF']?>" method="post">
		<input type="submit" class="bottone" name="Proiezioni_Passate" value="Proiezioni Passate"/>
	</form> 
    <?php 
    /*Script per le proieizioni passate.*/
    if (isset($_POST['Proiezioni_Passate'])) 
        {
         /*Query che  mostra le proiezioni passate di film, a partire da quella temporalmente più vicina.*/
         $query ="SELECT * 
                  FROM ProiezioniVista  
                  WHERE dataora_proiezione <= CURRENT_TIMESTAMP
                  ORDER BY dataora_proiezione ASC;";
                  /*Effettuo la query sopra.*/
                  if (!$risultatoQuery = mysqli_query($CONNESSIONE, $query))
                    {
                     echo "Errore nell'esecuzione della query per le proiezioni. <br />" . mysqli_error($CONNESSIONE);
                     exit(); /*Termino lo script.*/
                    }
         /*Stampo il risultato della query.*/
         $lista = "";
         $lista .= "
         <table class=\"catalogo\" style=\"margin: 0 auto; border: 3px solid #3D9970; border-collapse: collapse;\">
         <tr>
             <td><h3>Nome del cinema</h3></td> 
             <td><h3>Date e ora</h3></td> 
             <td><h3>Film</h3></td> 
             <td><h3>Citt&agrave;</h3></td> 
         </tr>";
         while($riga = mysqli_fetch_array($risultatoQuery))
            {
             $lista .= "<tr>";

             $lista .= "<td>";
             $lista .= $riga['NomeCinema'];
             $lista .= "</td>";

             $lista .= "<td>";
             $lista .= $riga['dataora_proiezione'];
             $lista .= "</td>";

             $lista .= "<td>";
             $lista .= $riga['NomeFilm'];
             $lista .= "</td>";       

             $lista .= "<td>";
             $lista .= $riga['citta'];
             $lista .= "</td>";

             $lista .= "</tr>";
            }
         $lista .= "</table>";
         echo $lista;          
        } /*Fine script per le proieizioni passate.*/
    ?>
    
    <!--Form per cercare le proiezioni in una certa città e di un certo film.-->
    <form action="<?php $_SERVER['PHP_SELF']?>" method="post">
        Cerca la proiezione del film nella tua citt&agrave; nei diversi cinema:<br />
        <span style="color: #0074D9;"> Citt&agrave;: </span> <input type="text" name="varCitta" size="20"/>
        <span style="color: #0074D9;"> Titolo film: </span> <input type="text" name="varFilm" size="20"/>
        <input type="submit" name="confermaRicerca" value="Conferma"/>
    </form>
    <?php 
        if(isset($_POST['confermaRicerca'])) /*Check sul submit.*/
            {
             if(empty($_POST['varCitta']) || empty($_POST['varFilm'])) /*Check sulla copilazione di tutti i campi. */
                {echo "<p style=\"color:red\">Perfavore, compilare tutti i campi </p>";}
             else
                {
                 /*Query che restituisce le proiezioni in una certa città di un certo film.*/
                 $query = 
                 "SELECT *
                 FROM ProiezioniVista
                 WHERE dataora_proiezione > CURRENT_TIMESTAMP
                 AND NomeFilm = \"{$_POST['varFilm']}\"
                 AND citta = \"{$_POST['varCitta']}\" 
                 ORDER BY dataora_proiezione ASC;";
                 if (!$risultatoQuery = mysqli_query($CONNESSIONE, $query))
                    {
                    echo "Errore nell'esecuzione della query per le proiezioni. <br />" . mysqli_error($CONNESSIONE);
                    exit(); /*Termino lo script.*/
                    }
                 else
                    {
                     if (mysqli_num_rows($risultatoQuery)==0)
                        {echo "Non sono presenti film \"" . $_POST['varFilm']  . "\" nella citt&agrave; " . $_POST['varCitta'];}
                     else
                        {
                         echo "Qui di seguito il film\"" . $_POST['varFilm']  . "\" nella citt&agrave; " . $_POST['varCitta'] . ":";
                         $lista = "<ol>";
                         while ($riga = mysqli_fetch_array($risultatoQuery))
                            { 
                             $lista .= "<li>";
                             $lista .= "Cinema" . $riga['NomeCinema'] . ", " . $riga['dataora_proiezione'];
                             $lista .= "</li>";
                            }
                         $lista .= "</ol>";
                         echo $lista;
                        }
                    }
                }
            }
 
    ?>
</body>
</html>