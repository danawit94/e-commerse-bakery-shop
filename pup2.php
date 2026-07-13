<?php
// Resume the session
session_start();
?>

<!DOCTYPE html>
<html>
<body>

<?php
// Output session variables that were set on previous page
if(isset($_SESSION["favcolor"])) {
  echo "Favorite color is " . $_SESSION["favcolor"] . ".<br>";
  echo "Favorite animal is " . $_SESSION["favanimal"] . ".";
} else {
  echo "No session data found.";
}
?>




<table>
  <tr>
    <th>Filter Name</th>
    <th>Filter ID</th>
  </tr>
  <?php
  foreach (filter_list() as $id =>$filter) {
    echo '<tr><td>' . $filter . '</td><td>' . filter_id($filter) . '</td></tr>';
  }
  ?>
</table>

<style>
    table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
th, td {
  padding: 5px;
}
</style>

</body>
</html>