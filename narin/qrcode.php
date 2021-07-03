<?php

require_once dirname(__FILE__) . '/narinconfig.php';

require_once $NarinConfig->path . '/lib/phpqrcode/qrlib.php';

QRcode::png($_GET['code'], false, QR_ECLEVEL_H, 5, 2);