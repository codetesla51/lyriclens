<?php

/*registtering users via ip_address
immediate click button for registration 
I know this isn't thr safest way but for this basic project we use it
*/
class CreateUser extends DbCreate
{
  private $user_id;
  private $ip_address;

  public function __construct()
  {
    $this->ip_address = $this->getUserIp();
    $this->user_id = $this->generateUserId();
  }
  #method fir getting ip_address
  public function getUserIp()
  {
    if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
      return $_SERVER["HTTP_CLIENT_IP"];
    } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
      $ipList = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"]);
      return trim($ipList[0]);
    } else {
      return $_SERVER["REMOTE_ADDR"];
    }
  }
  #method for generating user_id
  private function generateUserId($length = 10)
  {
    if ($length < 5) {
      $length = 5;
    }
    $text = "";
    for ($i = 0; $i < $length; $i++) {
      $text .= rand(0, 9);
    }
    return $text;
  }
#method for creating user
  public function insertUser()
  {
    $this->checkUser();
    $sql = "INSERT INTO users (u_id, ip) VALUES (?, ?)";
    $stmt = $this->connect()->prepare($sql);
    $result = $stmt->execute([$this->user_id, $this->ip_address]);
    if (!$result) {
      throw new Exception(
        "Statement failed to execute: " . $stmt->errorInfo()[2]
      );
    } else {
      echo "User created";
      $rememberMe = new RememberMe();
      $rememberMe->createRememberMeToken($this->user_id);
    }
  }

  public function checkUser()
  {
    $sql = "SELECT ip FROM `users` WHERE ip = ?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$this->ip_address]);

    if ($stmt->errorCode() !== "00000") {
      throw new Exception("Statement failed: " . $stmt->errorInfo()[2]);
    }

    if ($stmt->rowCount() > 0) {
      header("Location: ../views/index.php");
      exit();
    }
  }
}
