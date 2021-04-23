<?php
print_r(filter_list());

$html = "<a href='fletch.html'> Stew's favorite movie.</a>\n";
print htmlspecialchars($html);
print htmlspecialchars($html, ENT_QUOTES);
print htmlspecialchars($html, ENT_NOQUOTES);

print htmlentities($html);

?>

<form action="c9_forms.php" method="POST">
    <input type="text" name="location.x">
    <input type="submit">
</form>

<?php
echo $_POST['location_x'];
echo $_POST['location.x'];
 ?>}
