<?php  
  require_once "../../application.php";

  ini_set("max_execution_time", "0");

  $connection = mysql_connect($dbHostname, $dbUsername, $dbPassword);
  if (!$connection) {
    die("Database connection failed:" . mysql_error());
  }
   
  $db_select = mysql_select_db($dbName, $connection);

  $debug .= drop_tables();
  $debug .= run_sql('fresh.sql');

  // update config

  print "<div style='font-size:10px;font-family:monaco'>";
  print nl2br($debug);
  print "</div><hr>DONE";

///////////////////////////////////////////////////////////////////

  function drop_tables() {
    global $connection,$dbName; 

    $result = mysql_list_tables($dbName, $connection);

    while ($row = mysql_fetch_row($result)) {
      $query = "DROP TABLE {$row[0]}";
      $result2 = mysql_query($query, $connection);

      ($result2) ? $res = "<span style='color:#00f;'>OK</span>" : $res = "<span style='color:#f00;'>FAILED</span>";

      $debug .= htmlentities($query) . "... $res \n\n";
    }
    return $debug."<hr>";
  }

  function run_sql($file) {
    global $connection;

    $lines = @file($file);    
    unset($query);

    if (is_array($lines)) {
      foreach ($lines as $k => $v) {
        $buffer = trim($v);

        if(!empty($buffer) && substr($buffer,0,1) != "#" && substr($buffer,0,2) != "--" && substr($buffer,0,2) != "/*") {
          if(substr($buffer,-1,1)==";") {
            $query .= $v;
            $result = mysql_query($query, $connection);
            
            ($result) ? $res = "<span style='color:#00f;'>OK</span>" : $res = "<span style='color:#f00;'>FAILED</span>";
            
            $debug .= htmlentities($query) . "... $res \n\n";
            
            unset($query);
          }
          else {
            $query .= $v;
          }
        }
      }         
    }    
    return $debug."<hr>";
  }
?>