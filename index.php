<?php

  class Ospite {

    private $id;
    private $name;
    private $lastname;

    public function __construct($id, $name, $lastname) {

      $this->id = $id;
      $this->name = $name;
      $this->lastname = $lastname;

    }

    public function getName() {

      return $this->name;
    }

    public function getLastName() {

      return $this->lastname;
    }

    public static function getOspitiByPrenId($conn, $id) {

      $sql = "

        SELECT *
        FROM prenotazioni_has_ospiti
        RIGHT JOIN ospiti
        ON prenotazioni_has_ospiti.ospite_id  = ospiti.id
        WHERE prenotazione_id = $id

      ";

      $result = $conn->query($sql);


      if ($result->num_rows > 0) {

        $ospiti = [];

        while($row = $result->fetch_assoc()) {

          $ospiti[] = new Ospite($row["id"], $row["name"], $row["lastname"]);
        }


      }

      return $ospiti;
    }
  }

  // class Prenotazione_has_ospiti {
  //
  //   private $id;
  //   private $prenotazione_id;
  //   private $ospite_id;
  //
  //   public function __construct($id, $prenotazione_id, $ospite_id) {
  //
  //     $this->id = $id;
  //     $this->prenotazione_id = $prenotazione_id;
  //     $this->ospite_id = $ospite_id;
  //   }
  //
  //   function getId() {
  //
  //     return $this->id;
  //   }
  //
  //   function getPrenotazioneId() {
  //
  //     return $this->prenotazione_id;
  //   }
  //
  //   function getOspiteId() {
  //
  //     return $this->ospite_id;
  //   }
  //
  //   public static function getPreHasOspByPrenotazioneId($conn, $id) {
  //
  //     $sql = "
  //
  //       SELECT *
  //       FROM prenotazioni_has_ospiti
  //       WHERE prenotazione_id = $id
  //
  //     ";
  //
  //     $result = $conn->query($sql);
  //
  //     if ($result->num_rows > 0) {
  //
  //       $preHasOsp = [];
  //
  //       while($row = $result->fetch_assoc()) {
  //
  //         $preHasOsp[] = new Prenotazione_has_ospiti($row["id"], $row["prenotazione_id"], $row["ospite_id"]);
  //       }
  //
  //       return $preHasOsp;
  //     }
  //   }
  //
  // }

  class Pagamento {

    private $id;
    private $status;
    private $price;
    private $prenotazione_id;

    public function __construct($id, $status, $price, $prenotazione_id) {

      $this->id = $id;
      $this->status = $status;
      $this->price = $price;
      $this->prenotazione_id = $prenotazione_id;
    }

    function getId() {

      return $this->id;
    }

    function getStatus() {

      return $this->status;
    }

    function getPrice() {

      return $this->price;
    }

    public static function getPagamentoByPrenotazioneId($conn, $id) {

      $sql = "

        SELECT *
        FROM pagamenti
        WHERE prenotazione_id = $id

      ";

      $result = $conn->query($sql);

      if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();
        $pagamento = new Pagamento($row["id"], $row["status"], $row["price"], $row["prenotazione_id"]);

        return $pagamento;
      }
      else {
        return new Pagamento("cancellato", "x", "x", "x");
      }
    }
  }

  class Prenotazione {

    private $id;
    private $stanza_id;
    private $configurazione_id;
    private $create_at;

    public function __construct($id, $stanza_id, $configurazione_id, $create_at) {

      $this->id = $id;
      $this->stanza_id = $stanza_id;
      $this->configurazione_id = $configurazione_id;
      $this->create_at = $create_at;
    }

    public function getAsArray() {

      return [
        "id" => $this->id,
        "stanza_id" => $this->stanza_id,
        "configurazione_id" => $this->configurazione_id,
        "create_at" => $this->create_at
      ];
    }

    public static function getAllPrenotazioni($conn) {

      $sql = "

        SELECT *
        FROM prenotazioni

      ";

      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        $prenotazioni = [];
        while($row = $result->fetch_assoc()) {
          $prenotazioni[] =
              new Prenotazione($row["id"],
                               $row["stanza_id"],
                               $row["configurazione_id"],
                               $row["created_at"]);
        }
      }

      return $prenotazioni;
    }
  }

  class Stanza {

    private $id;
    private $room_number;
    private $floor;
    private $beds;

    function __construct($id, $room_number, $floor, $beds) {

      $this->id = $id;
      $this->room_number = $room_number;
      $this->floor = $floor;
      $this->beds = $beds;
    }

    function getId() {

      return $this->id;
    }

    function getRoomNumber() {

      return $this->room_number;
    }

    function getFloor() {

      return $this->floor;
    }

    function getBeds() {

      return $this->beds;
    }

    public static function getStanzaById($conn, $id) {

      $sql = "

        SELECT *
        FROM stanze
        WHERE id = $id

      ";

      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stanza = new Stanza(
                      $row["id"],
                      $row["room_number"],
                      $row["floor"],
                      $row["beds"]);

        return $stanza;
      }
    }
  }

  class Configurazione {

    private $id;
    private $title;
    private $description;

    function __construct($id, $title, $description) {

      $this->id = $id;
      $this->title = $title;
      $this->description = $description;
    }

    function getId() {

      return $this->id;
    }

    function getTitle() {

      return $this->title;
    }

    function getDescription() {

      return $this->description;
    }

    public static function getConfigurazioneById($conn, $id) {

      $sql = "

        SELECT *
        FROM configurazioni
        WHERE id = $id

      ";

      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $configurazione = new Configurazione($row["id"], $row["title"], $row["description"]);

        return $configurazione;
      }
    }
  }

  $servername = "localhost";
  $username = "root";
  $password = "password";
  $dbname = "prova";

  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_errno) {

    echo $conn->connect_error;
    return;
  }

  $prenotazioni = Prenotazione::getAllPrenotazioni($conn);

  foreach ($prenotazioni as $prenotazione) {

    $prenotazioneArr = $prenotazione->getAsArray();
    $stanza_id = $prenotazioneArr["stanza_id"];
    $configurazione_id = $prenotazioneArr["configurazione_id"];

    $stanza = Stanza::getStanzaById($conn, $stanza_id);
    $configurazione = Configurazione::getConfigurazioneById($conn, $configurazione_id);
    $pagamento = Pagamento::getPagamentoByPrenotazioneId($conn, $prenotazioneArr["id"]);

    // $prenotazioneOspitiArr = Prenotazione_has_ospiti::getPreHasOspByPrenotazioneId($conn, $prenotazioneArr["id"]);
    // $ospiti = [];
    // foreach ($prenotazioneOspitiArr as $prenotazioneOspite) {
    //
    //   $ospiti[] = Ospite::getOspitiById($conn, $prenotazioneOspite->getOspiteId());
    // }

    $ospiti = Ospite::getOspitiByPrenId($conn, $prenotazioneArr["id"]);

    echo "Prenotazione: " . $prenotazioneArr["id"] . "<br>" .
          "- stanza: " . $stanza->getId() . "; number: " . $stanza->getRoomNumber() . "; floor: " . $stanza->getFloor() . "; beds: " . $stanza->getBeds() . "<br>" .
          "- configurazione: " . $configurazione->getId() . "; " . $configurazione->getTitle() . "; " . $configurazione->getDescription() . "; " . "<br>" .
          "- pagamento: " . $pagamento->getId() . "; status " . $pagamento->getStatus() . "; price " . $pagamento->getPrice() . "; " . "<br>" .
          "- ospiti: <br>";

    foreach ($ospiti as $ospite) {

      echo "-- " . $ospite->getName() . " " . $ospite->getLastName() . "<br>";
    }

    echo  "<br><br>";

  }

 ?>
