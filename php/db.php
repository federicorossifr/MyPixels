<?php



class db {
  protected $connection;
  protected $result;
  protected $error;
  protected $errorMessage;
  public $rows;
  public $insertedId;
  public $affected;


  function __construct() {
    $this->connection = new mysqli('localhost','root','','mypx');
    if(!$this->connection)
      die("Impossibile contattare il database");
    else {
      $this->rows = 0;
      $this->errorMessage = "";
      $this->error = 0;
      $this->result = null;
    }
  }

  function utilityFilter(&$query) {
    $this->connection->real_escape_string($query);
    return $query;
  }

  function query($query) {

    $this->result = $this->connection->query($query);

    if($this->connection->error) {
      $this->result = null;
      $this->error = 1;
      $this->errorMessage = $this->connection->error;
      die($this->errorMessage);
    } else {

      if(isset($this->result->num_rows))
        $this->rows = $this->result->num_rows;

      $this->insertedId = $this->connection->insert_id;
      $this->affected = $this->connection->affected_rows;
    }
      return $this->result;
  }

  function arrayResult()  {
    $response = array();

    while($row = $this->result->fetch_assoc()) {
      array_push($response,$row);
    }

    return $response;
  }

  function JSONResult() {
    $response = $this->arrayResult();
    return json_encode($response);
  }

  function error() {
    $error = array();
    $error['isError'] = $this->error;
    $error['errorMessage'] = $this->errorMessage;
    return $error;
  }

  function end() {
    return $this->connection->close();

  }



}



?>
