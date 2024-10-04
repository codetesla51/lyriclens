<?php
class RememberMe extends DbCreate
{
  private $cookieName = "remember_me";
  private $cookieLifetime = 86400 * 30; // 30 days

  public function createRememberMeToken($user_id)
  {
    $token = bin2hex(random_bytes(16));
    setcookie($this->cookieName, $token, time() + $this->cookieLifetime, "/");
    $this->storeTokenInDatabase($user_id, $token);
  }

  private function storeTokenInDatabase($user_id, $token)
  {
    $hashedToken = hash("sha256", $token);
    $sql = "INSERT INTO user_tokens (user_id, token, created_at) VALUES (?, ?, NOW())";
    $stmt = $this->connect()->prepare($sql);

    if (!$stmt) {
      echo "Statement preparation failed.";
      exit();
    }

    $stmt->execute([$user_id, $hashedToken]);
  }

  public function validateRememberMeToken()
  {
    if (isset($_COOKIE[$this->cookieName])) {
      $token = $_COOKIE[$this->cookieName];
      $hashedToken = hash("sha256", $token);
      $sql = "SELECT user_id FROM user_tokens WHERE token = ?";
      $stmt = $this->connect()->prepare($sql);
      $stmt->execute([$hashedToken]);
      $result = $stmt->fetch();

      if ($result) {
        // Token is valid
        return true;
      } else {
        // Token is invalid, delete it
        $this->deleteRememberMeToken();
      }
    }
    // No token found or token invalid
    return false;
  }

  public function getUserIdFromToken()
  {
    if (isset($_COOKIE[$this->cookieName])) {
      $token = $_COOKIE[$this->cookieName];
      $hashedToken = hash("sha256", $token);
      $sql = "SELECT user_id FROM user_tokens WHERE token = ?";
      $stmt = $this->connect()->prepare($sql);
      $stmt->execute([$hashedToken]);
      $result = $stmt->fetch();

      if ($result) {
        return $result["user_id"];
      }
    }
    return false;
  }

  public function deleteRememberMeToken()
  {
    if (isset($_COOKIE[$this->cookieName])) {
      setcookie($this->cookieName, "", time() - 3600, "/");
      $this->removeTokenFromDatabase();
    }
  }

  private function removeTokenFromDatabase()
  {
    if (isset($_COOKIE[$this->cookieName])) {
      $token = $_COOKIE[$this->cookieName];
      $hashedToken = hash("sha256", $token);

      $sql = "DELETE FROM user_tokens WHERE token = ?";
      $stmt = $this->connect()->prepare($sql);
      $stmt->execute([$hashedToken]);
    }
  }
}
?>