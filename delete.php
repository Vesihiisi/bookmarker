<?php
include "base.php";



$id = $_POST['linkID'];
deleteEntry($id);
deleteConnections($id);
