<?php
$db = new PDO(
    "mysql:host=localhost;dbname=penzion;charset=utf8", //adresa a jmeno databaze
    "root", //username
    "", //heslo
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)  //options pro vypis chyb
);

class Stranka {
    //vlastnosti tridy
    protected $id;
    protected $titulek;
    protected $menu;
    protected $oldId;

    //konstruktor
    function __construct($id, $titulek, $menu)
    {
        $this->id = $id;
        $this->titulek = $titulek;
        $this->menu = $menu;
    }

    function getId() {
        return $this->id; 
    }

    function getTitulek() {
        return $this->titulek;
    }

    function getMenu() {
        return $this->menu;
    }


    function setId($noveId) {
        $this->oldId = $this->id;
        $this->id = $noveId;
        return; 
    }

    function setTitulek($novyTitulek) {
        $this->titulek = $novyTitulek;
        return;
    }

    function setMenu($noveMenu) {
        $this->menu = $noveMenu;
        return;
    }

    function ulozDoDatabaze () {
        if($this->oldId) {
            $query = $GLOBALS["db"]->prepare("UPDATE stranka SET id=?, titulek=?, menu=? WHERE id=?");
            $query->execute([$this->id, $this->titulek, $this->menu, $this->oldId]);
            //zadny fetch nepotrebujeme
        }else{
            $query = $GLOBALS["db"]->prepare("INSERT stranka SET id=?, titulek=?, menu=?");
            $query->execute([$this->id, $this->titulek, $this->menu]);
        }   
    }

    function smazSe() {
        $query = $GLOBALS["db"]->prepare("DELETE FROM stranka where id=?");
        $query->execute([$this->id]);
        //nepotrebujeme fetchovat
    }

    //kazda instance muze vratit svuj obsah
    function getObsah() {
        //$GLOBALS znamena ze chceme pouzit promenou ktera je mimo classu
        $query = $GLOBALS["db"]->prepare("SELECT * FROM stranka WHERE id=?");
        $query->execute([$this->id]);
        //fetch narozdil od fetchAll vrati je jeden pravni radek
        $row = $query->fetch();

        //podminka ktera zjisti jestli se neco naslo v databazi
        if ($row) {
            //ano naslo vrat sloupecek obsah
            return $row["obsah"];
        }else {
            //ne nenaslo vrat prazdny string
            return "";
        }
        
        /*
        $obsahStranky = file_get_contents($this->id.".html");
        return $obsahStranky;
        */
    }

    //instance zaktualizuje obsah v souboru
    function setObsah($novyObsah) {
        $query = $GLOBALS["db"]->prepare("UPDATE stranka SET obsah=? WHERE id=?");
        $query->execute([$novyObsah, $this->id]);
        //vkladame do databaze nemusime delat fetch

        
        /*
        $vysledek = true;
        
        //muze se stat ze tato operace zkrachuje
        try {
            file_put_contents($this->id.".html", $obsah);
        } catch (Exception $e) {
            //pokud nastala chyba provede se kod v tomto bloku
            $vysledek = false;
        }
        
        return $vysledek;
        */
    }

}

//novejsi seznam stranek
$seznamStranek = array(); //zatim prazdne
//pripojit se do DB a nechat si vypsat vsechny stranky
$query = $db->prepare("SELECT * FROM stranka"); //pripravime SQL prikaz
$query->execute();  //spustime prikaz
$rows = $query->fetchAll(); //ziskat vsechny radky ve vysledku

//proiterujeme pole $rows
foreach ($rows as $row) {
    //vlozime pro kazdy $row do pole novou instanci stranky
    $seznamStranek[$row["id"]] = new Stranka($row["id"], $row["titulek"], $row["menu"]);
}

/*novy seznam stranek, pole instanci
$seznamStranek = array(
    'domu' => new Stranka("domu", "PrimaPenzion", "Domů"),
    'kontakt' => new Stranka("kontakt", "Jak nás kontaktujete", "Kontakt"),
    'galerie' => new Stranka("galerie", "Fotky pokojů", "Galerie"),
    'rezervace' => new Stranka("rezervace", "Objednávka pokoje", "Rezervace"),
    'reklamace' => new Stranka("reklamace", "Reklamace služeb", "Reklamace"),
    '404' => new Stranka("404", "Stránka neexistuje", ""),
);
*/

/* stary seznam stranek tvoreny z poli
$seznamStranek = array(
	'domu' => array(
		'titulek' => 'PrimaPenzion',
		'menu' => 'Domů',
	),
	'kontakt' => array(
		'titulek' => 'Jak nás kontaktujete',
		'menu' => 'Kontakt',
	),
	'galerie' => array(
		'titulek' => 'Fotky pokojů',
		'menu' => 'Galerie',
	),
	'rezervace' => array(
		'titulek' => 'Objednávka pokoje',
		'menu' => 'Rezervace',
	),
	'reklamace' => array(
		'titulek' => 'Reklamace služeb',
		'menu' => 'Reklamace',
	),
	'404' => array(
		'titulek' => 'Stránka neexistuje',
		'menu' => '',
	),
);
*/