<?php

  require __DIR__ . "/userController.php";

  function caller($function) {
    $arguments = func_get_args();
    array_shift($arguments);
    $callerArguments = array();
    foreach ($arguments as $key => $value) {
      $callerArguments[$key] = $value;
    }
    return call_user_func_array($function,$callerArguments);
  }
