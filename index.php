<html>
  <head>
    <title>Dynamic Website</title>
  </head>
  <body>
    <h1>Welcomem to My Website</h1>
    <?php
      $ep = "create-rds-stack-dbinstance-fyayy878qe4a.cneakagya2rr.us-east-1.rds.amazonaws.com";
      $db = "DynamicWebDB";
      $un = "main";
      $pw = "mainpassword";
      /* Connect to MySQL and select the database. */
      $connection = mysqli_connect($ep, $un, $pw);
      if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();
      $database = mysqli_select_db($connection, $db);
      /* Ensure that the LOCATIONS table exists. */
      VerifyLocationsTable($connection, $db);
      /* If input fields are populated, add a row to the LOCATIONS table. */
      $employee_name = htmlentities($_POST['NAME']);
      $employee_address = htmlentities($_POST['ADDRESS']);
      if (strlen($employee_name) || strlen($employee_address)) {
      AddLocation($connection, $employee_name, $employee_address);
      }
    ?>
    <!-- Input form -->
    <form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
    <table border="0">
    <tr><td>NAME</td><td>ADDRESS</td></tr>
    <tr>
    <td><input type="text" name="NAME" maxlength="45" size="30" /></td>
    <td><input type="text" name="ADDRESS" maxlength="90" size="60" /></td>
    <td><input type="submit" value="Add Data" /></td>
    </tr>
    </table>
    </form>
    <!-- Display table data. -->
    <table border="1" cellpadding="2" cellspacing="2">
    <tr><td>ID</td><td>NAME</td><td>ADDRESS</td></tr>
    <?php
      $result = mysqli_query($connection, "SELECT * FROM LOCATIONS");
      while($query_data = mysqli_fetch_row($result)) {
      echo "<tr>";
      echo "<td>",$query_data[0], "</td>",
      "<td>",$query_data[1], "</td>",
      "<td>",$query_data[2], "</td>";
      echo "</tr>";
    }
    ?>
    </table>
    <!-- Clean up. -->
      <?php
      mysqli_free_result($result);
      mysqli_close($connection);
    ?>
  </body>
</html>

<?php
  /* Add an employee to the table. */
  function AddLocation($connection, $name, $address) {
    $n = mysqli_real_escape_string($connection, $name);
    $a = mysqli_real_escape_string($connection, $address);
    $query = "INSERT INTO LOCATIONS (NAME, ADDRESS) VALUES ('$n', '$a');";
    if(!mysqli_query($connection, $query)) echo("<p>Error adding employee data.</p>");
  }

  /* Check whether the table exists and, if not, create it. */
  function VerifyLocationsTable($connection, $dbName) {
    if(!TableExists("LOCATIONS", $connection, $dbName)) {
      $query = "CREATE TABLE LOCATIONS (
      ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      NAME VARCHAR(45),
      ADDRESS VARCHAR(90)
      )";
      if(!mysqli_query($connection, $query)) echo("<p>Error creating table.</p>");
      $query = "INSERT INTO LOCATIONS (NAME, ADDRESS) VALUES ('RMIT', '702 Nguyen Van Linh, District 7');";
      if(!mysqli_query($connection, $query)) echo("<p>Error initializing sample data.</p>");

    }
  }
  /* Check for the existence of a table. */
  function TableExists($tableName, $connection, $dbName) {
    $t = mysqli_real_escape_string($connection, $tableName);
    $d = mysqli_real_escape_string($connection, $dbName);
    $checktable = mysqli_query($connection,
    "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME =
    '$t' AND TABLE_SCHEMA = '$d'");
    if(mysqli_num_rows($checktable) > 0) return true;
    return false;
  }
?>
