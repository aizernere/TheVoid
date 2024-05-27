<?php
$connection = new mysqli('localhost', 'root', '', 'dbthevoid');

if (!$connection) {
	die(mysqli_error($mysqli));
}
