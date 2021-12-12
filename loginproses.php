<?php

  session_start();

  $username = @$_POST['user'];
  $password = @$_POST['password'];

  if ($username == 'admin' && $password == 'Tracker123!') {
    $_SESSION['user'] = "admin";
    header("location: index.php");
  } else {
    echo "<script>alert('User / Password salah!');window.history.back();</script>";
  }

?>
