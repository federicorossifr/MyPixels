<?php
require_once __DIR__ . "/config.php";
class db {
  protected $connection;
  protected $result;
  protected $error;
  protected $errorMessage;
  public $rows; //numero di righe ritornate
  public $insertedId; // ultimo id inserito
  public $affected; // numero di righe cancellate o aggiornate


  function __construct() {
    $this->connection = new mysqli(Config::read("dbHost"),Config::read("dbUser"),Config::read("dbPass"),Config::read("dbCollection"));
    if(!$this->connection)
      die("Impossibile contattare il database");
    else {
      $this->rows = 0;
      $this->errorMessage = "";
      $this->error = 0;
      $this->result = null;
    }
  }

  function utilityFilter(&$query) { //previene SQL injection
    $query = $this->connection->real_escape_string($query);
    return $query;
  }

  function query($query,$next = 1) {

    //esecuzione della query passata come stringa in $query
    $this->result = $this->connection->query($query);

    //se c'è errore abortisco la connessione ritornando l'errore
    if($this->connection->error) {
      $this->result = null;
      $this->error = 1;
      $this->errorMessage = $this->connection->error;
      die($this->errorMessage);
    } else {

      //controllo se esiste num_rows (num_rows è disponibile solo dopo una SELECT)
      if(isset($this->result->num_rows))
        $this->rows = $this->result->num_rows;

      //memorizzo l'id inserito (insert_id è disponibile solo dopo una INSERT)
      $this->insertedId = $this->connection->insert_id;
      //memorizzo il numero di righe cancellate o aggiornate (DELETE o UPDATE)
      $this->affected = $this->connection->affected_rows;
    }

      return $this->result;
  }

  function arrayResult($next = 0)  {
    $response = array();

    //per ogni riga del risultato della query, la metto nell'array response
    //sotto forma di array associativo (indicizzato tramite nome del campo)
    while($row = $this->result->fetch_assoc()) {
      array_push($response,$row);
    }

    $this->result->close();
    if(!$next) {
      $this->end();
    }
    return $response;
  }

  function JSONResult() {
    $response = $this->arrayResult(1);
    $this->end();

    //trasformo array in codifica JSON per ritornarlo via AJAX
    return json_encode($response);
  }

  function ExtendedJSONResult() {
    $response = array();
    $response['data'] = $this->arrayResult(1);
    $response['length'] = $this->rows;
    $response['error'] = $this->error()['errorMessage'];
    
    //trasformo array in codifica JSON per ritornarlo via AJAX
    return json_encode($response);
  }

  function error() {
    $error = array();
    $error['isError'] = $this->error;
    $error['errorMessage'] = $this->errorMessage;
    $this->end();
    return $error;
  }

  function end() {
    return $this->connection->close();
  }



}



?>
