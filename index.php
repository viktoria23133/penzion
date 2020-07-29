<?php
require_once 'data.php';

// pokud uzivatel zvolil nejakou stranku tak mu ji
// zobrazime. Pokud prisel aniz by neco zvolil tak mu
// zobrazime stranku "domu"
if (array_key_exists("stranka", $_GET))
{
	$stranka = $_GET["stranka"];
	//echo $stranka;

	// potrebujeme zkontrolovat zdali vybrana stranka
	// existuje. A pokud neexistuje, tak misto toho
	// zobrazime alternativni stranku a vratime http status
	// kod 404
	if (!array_key_exists($stranka, $seznamStranek))
	{
		$stranka = "404";
		// nastavime http status kod pro vyhledavace, aby
		// take vedeli, ze stranka neexistuje
		http_response_code(404);
	}
}
else
{
    //pokud v url neni presne specifikovano jakou stranku mame nacist vezmeme prvni
	$stranka = array_keys($seznamStranek)[0];
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $seznamStranek[$stranka]->getTitulek(); ?></title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/all.min.css">
        <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400i,700&display=swap&subset=latin-ext" rel="stylesheet">
</head>
<body>

    <header><!--hlavička-->
        <div class="headerContainer">

            <div class="headerInfo">
                <a href="tel:+420 606 123 456">+420 606 123 456</a>
                <div>
                   <a href="https://www.facebook.com/PrimaKurzy/" target="_blank"><i class="fab fa-facebook-square"></i></a> 
                   <a href="https://www.instagram.com/primakurzy_cz/" target="_blank"> <i class="fab fa-instagram"></i></a>
                   <a href="https://www.youtube.com/channel/UCt5cViTLkp_NaqGVpNL8F0g" target="_blank"> <i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <a class="logo" href="?"> <p>Prima<br />Penzion</p></a>

        

            <div class="menu">
                <ul>
					<!--
                   <li><a href="?stranka=domu">domů</a></li>
                   <li><a href="?stranka=kontakt">kontakt</a></li>
                   <li><a href="?stranka=galerie">galerie</a></li>
                   <li><a href="?stranka=rezervace">rezervace</a></li>
				   -->

				   <?php
				   foreach ($seznamStranek as $polozkaMenu => $vlastnosti)
				   {
					   echo "<li><a href='?stranka=$polozkaMenu'>{$vlastnosti->getMenu()}</a></li>";
				   }
				   ?>
                </ul>

            </div>

        </div>
        
        <div class="headerImg headerImg-<?php echo $stranka ?>">

        </div>
        
    </header>

    <section>
		<?php

		// nacteme takovy soubor, ktery odpovida nasi
		// promenny $stranka s priponou .html
        //echo file_get_contents("$stranka.html");
        echo $seznamStranek[$stranka]->getObsah();

		?>
    </section>

    <footer><!--patička-->

        <div class="pata">
            <div class="menu">
                <ul>
					<?php
				   foreach ($seznamStranek as $polozkaMenu => $vlastnosti)
				   {
					   echo "<li><a href='?stranka=$polozkaMenu'>{$vlastnosti->getMenu()}</a></li>";
				   }
				   ?>
                </ul>
            
            </div>

            <a class="logo" href="./"> <p>Prima<br />Penzion</p></a>

            <div class="footerInfo">
             <p>
                <i class="fas fa-map-marked-alt"></i>
                <a href="https://goo.gl/maps/dHWDZsevrZ8VHhKr7" target="_blank">PrimaPenzion, Jablonského 2, Praha 7</a>
             </p>
             <p>
                <i class="fas fa-phone"></i>
                <a href="tel:+420 606 123 456">+420 606 123 456</a>
             </p>
            
            <p>
                
                <i class="far fa-envelope" ></i>
                <span>info@primapenzion.cz</span>
            </p>
               <div class="footerInfoI">
                <a href="https://www.facebook.com/PrimaKurzy/" target="_blank"><i class="fab fa-facebook-square"></i></a> 
                    <a href="https://www.instagram.com/primakurzy_cz/" target="_blank"> <i class="fab fa-instagram"></i></a>
                    <a href="https://www.youtube.com/channel/UCt5cViTLkp_NaqGVpNL8F0g" target="_blank"> <i class="fab fa-youtube"></i></a>
                </div>
             </div>

            
                    
             


                

        </div>

        

        

    </footer>
         
</body>
</html>


