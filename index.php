 <!DOCTYPE html>
<html lang="de">
<head>

  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>Bookmarks</title>

  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous" />
  <link rel="stylesheet" href="resources/css/styles.css" />

</head>

<body>

  <h1>Bookmarks - Login</h1>

  <?php

  session_start();

  if( isset($_SESSION['user_id']) ) {
    $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $query_string = parse_url($url, PHP_URL_QUERY);
    
    header('Location: bookmarks.php?' . $query_string);
    die();
  }

  include 'database.php';
  include 'tables.php';

  if( isset($_GET['login']) ) {
    $username = $_POST['user'];
    $password = $_POST['password'];

    try {
      $sql = $db->query('SELECT * FROM users WHERE username = "' . $username . '"');
      $user = $sql->fetch();

      if( $user !== false && password_verify($password, $user['password']) ) {
        $_SESSION['user_id'] = $user['id'];
        $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $query_string = parse_url($url, PHP_URL_QUERY);
        header('Location: /bookmarks.php?' . $query_string);
        die();
      }
      else {
        $errortext = "Username or password is not correct!";
      }
    }
    catch (PDOException $e){
      exit($e->getMessage());
    }
  }
  elseif ( isset($_GET['username']) ) {
    $username_value = $_GET['username'];
  }

  if( isset($errortext) ){
    echo $errortext;
  }

  ?>

  <form action="?login=1" method="post">
    Username:<br>
    <input type="text" value="<?php echo (isset($username_value))?$username_value:'';?>" size="40" maxlength="250" name="user"><br><br>
	 
    Password:<br>
    <input type="password" size="40"  maxlength="250" name="password"><br>
	 
    <input type="submit" value="Login">
  </form> 

</body>
</html>
