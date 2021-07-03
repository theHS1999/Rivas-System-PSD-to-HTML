<?php

/* Copyright (C) Rivas System, Inc. All rights reserved. */

require_once dirname(dirname(__FILE__)) . '/narinconfig.php';
require_once $NarinConfig->path . '/lib/narin/narintools.php';

if (isset($_GET['form']) && isset($_GET['id'])) {
	try {
		$result = NarinTools::search($_GET['form'], $_GET['id']);
	}
	catch (Exception $e) {
		$error = $e->getMessage();
	}
}

?>
<!DOCTYPE html>
<html dir="rtl">
<head>
	<meta charset="UTF-8" />
	<title>جست‌وجو</title>
</head>
<body>
	<form method="get">
		<p>
			<label>نام فرم:</label>
			<input type="text" name="form" value="<?= htmlspecialchars($_GET['form']) ?>" dir="ltr">
		</p>
		<p>
			<label>شماره پیگیری:</label>
			<input type="text" name="id" value="<?= htmlspecialchars($_GET['id']) ?>" dir="ltr">
		</p>
		<p>
			<input type="submit" value="جست‌وجو">
		</p>
	</form>
	<?php if (isset($result)): ?>
		<?php if ($result === false): ?>
			<p>
				هیچ نتیجه‌ای یافت نشد.
			</p>
		<?php else: ?>
			<ul>
				<?php foreach ($result as $label => $value): ?>
					<li>
						<b><?= htmlspecialchars($label) ?></b>:
						<?= htmlspecialchars($value) ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	<?php elseif (isset($error)): ?>
		<p dir="ltr">
			ERROR: <?= htmlspecialchars($error) ?>
		</p>
	<?php endif; ?>
</body>
</html>