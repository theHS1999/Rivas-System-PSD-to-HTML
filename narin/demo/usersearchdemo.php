<?php

/* Copyright (C) Rivas System, Inc. All rights reserved. */

require_once dirname(dirname(__FILE__)) . '/narinconfig.php';
require_once $NarinConfig->path . '/lib/narin/narintools.php';

$status_names = array('S' => 'ثبت شده', 'P' => 'بررسی نشده', 'A' => 'تأیید شده', 'D' => 'رد شده');

if (isset($_GET['id'])) {
	try {
		$results = NarinTools::user_search($_GET['id']);
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
	<title>پیگیری</title>
</head>
<body>
	<form method="get">
		<p>
			<label>شماره پیگیری:</label>
			<input type="text" name="id" value="<?= htmlspecialchars($_GET['id']) ?>" dir="ltr">
		</p>
		<p>
			<input type="submit" value="جست‌وجو">
		</p>
	</form>
	<?php if (isset($results)): ?>
		<?php if ($results === false): ?>
			<p>
				شما وارد سیستم نشده‌اید.
			</p>
		<?php elseif (!count($results)): ?>
			<p>
				هیچ نتیجه‌ای یافت نشد.
			</p>
		<?php else: ?>
			<ul>
				<?php foreach ($results as $result): ?>
					<li>
						در <b><?= htmlspecialchars($result['form_name']) ?></b>:
						<?= $status_names[$result['status']] ?>.
						<?php if (strlen($result['msg'])): ?>
							پیام: <?= $result['msg'] ?>
						<?php endif; ?>
						<a href="<?= htmlspecialchars(NarinTools::get_form_url($result['form_name'])) ?>">[این فرم]</a>
						<?php if (!is_null($result['prereq'])): ?>
							<a href="<?= htmlspecialchars(NarinTools::get_form_url($result['prereq'])) ?>">[پیشنیاز]</a>
						<?php endif; ?>
						<?php if (!is_null($result['next']) && ($result['status'] === 'S' || $result['status'] === 'A')): ?>
							<a href="<?= htmlspecialchars(NarinTools::get_form_url($result['next'])) ?>">[مرحله‌ی بعد]</a>
						<?php endif; ?>
					</li>
					<ul>
						<?php foreach ($result['data'] as $label => $value): ?>
							<li>
								<b><?= htmlspecialchars($label) ?></b>:
								<?= htmlspecialchars($value) ?>
							</li>
						<?php endforeach; ?>
					</ul>
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