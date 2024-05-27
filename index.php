<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>The Void</title>
  <link rel="stylesheet" href="./style.css">
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include("connect.php");?>
    <section class="container">
        <div class="login-container">

            <div class="form-container">
                <img src="astronaut.png" alt="illustration" class="illustration" />
                <h1 class="opacity">&lt;&nbsp;Void&nbsp;&gt;</h1>
                <form method="post">
                    <input type="text" name="username" placeholder="USERNAME" />
                    <input type="password" name="password" placeholder="PASSWORD" />
                    <input type="text" name="email" placeholder="EMAIL" />
                    <button class="opacity" type="submit" name="btnRegister">&lt;&nbsp;START MISSION&nbsp;&gt;</button>
                </form>
            </div>

        </div>
    </section>
</body>
</html>
<?php
$error = "";
if (isset($_POST['btnRegister'])) {
    $username = $_POST['username']; 	
    $password = $_POST['password'];
    $email = $_POST['email'];

    $hashed_pword = hashCode($password);


    $userExists  = valueExists($connection, 'tbluser', 'username', $username);



    if ($userExists) {
        $error = "Astronaut already exists! ";
    }

    if ($error) {
        echo "<script language='javascript'>
            $(document).ready(function() {
                $('#user .errorMessage').prepend('$error');
                $('#user').modal('show');
            });
          </script>";
          return;
    } else {
		
        $user_id = createUser($connection, $username, $hashed_pword, $email);
        createPlayer($connection, $user_id);
        $error = "Welcome! You may now start your mission, " . $username . ".";

        echo "<script language='javascript'>
                    $(document).ready(function() {
                    $('#user .errorMessage').append('$error');
                    $('#user').modal('show');
                    });
                    </script>";
    }
}
function hashCode($str) {
    $hash = 0;
    $len = strlen($str);
    for ($i = 0; $i < $len; $i++) {
        $hash = (31 * $hash + ord($str[$i])) & 0xFFFFFFFF;
    }

    if ($hash > 0x7FFFFFFF) {
        $hash -= 0x100000000;
    }
    return $hash;
}

function valueExists($connection, $table, $column, $value)
{
  $sql = "Select * from " . $table . " where " . $column . "='" . $value . "'";
  $result = mysqli_query($connection, $sql);
  $row_count = mysqli_num_rows($result);
  return $row_count > 0;
}

function createUser($connection, $username, $hashed_pword, $email)
{
  $sql = "Insert into tbluser(username,password,email) values('" . $username . "','" . $hashed_pword . "','" . $email . "')";
  mysqli_query($connection, $sql);
  return getVal($connection, "userID", "tbluser", "username", $username);
}

function getVal($connection, $desired_val, $table, $column, $value)
{
  $sql = "Select " . $desired_val . " from " . $table . " where " . $column . " = '" . $value . "'";
  $retval = mysqli_query($connection, $sql);
  if (!$retval) {
    die('Error: ' . mysqli_error($connection));
  }
  if (mysqli_num_rows($retval) > 0) {
    $row = mysqli_fetch_assoc($retval);
    return $row[$desired_val];
  } else {
    return null;
  }
}

function createPlayer($connection, $user_id)
{
  $sql = "Insert into tblplayer(userID,positionx,positionz) values('" . $user_id . "','0','0')";

  mysqli_query($connection, $sql);

  return;
}
?>
<div class="modal fade" id="user" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Incoming Log!</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="errorMessage"> </p>
            </div>

        </div>
    </div>
</div>