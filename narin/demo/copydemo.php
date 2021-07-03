<?php

/* Copyright (C) Rivas System, Inc. All rights reserved. */

require_once dirname(dirname(__FILE__)) . '/narinconfig.php';
require_once $NarinConfig->path . '/lib/narin/narintools.php';

if (isset($_POST['from']) && isset($_POST['to'])) {
	try {
		$result = NarinTools::copy_data($_POST['from'], $_POST['to']);
	}
	catch (Exception $e) {
		$error = $e->getMessage();
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<title>copy data</title>
</head>
<body>
	<form method="post">
		<p>
			<label>from:</label>
			<input type="text" name="from" placeholder="form name">
		</p>
		<p>
			<label>to:</label>
			<input type="text" name="to" placeholder="form name">
		</p>
		<p>
			<input type="submit" value="copy">
		</p>
	</form>
	<?php if (isset($result)): ?>
		<p>
			copy from "<?=$_POST['from']?>" to "<?=$_POST['to']?>" done; <?=$result[0]?> succeeded, <?=$result[1]?> failed.
		</p>
	<?php elseif (isset($error)): ?>
		<p>
			copy from "<?=$_POST['from']?>" to "<?=$_POST['to']?>" NOT done; ERROR: <?=htmlspecialchars($error)?>
		</p>
	<?php endif; ?>
</body>
</html>