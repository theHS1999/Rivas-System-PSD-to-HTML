<?php
require_once $NarinConfig->path . '/lib/narin/narindataviewer.php';
class testForm_dataviewer extends NarinDataViewer {
    public function __construct() {
        parent::__construct((object)array('title' => 'فرم تست', 'name' => 'testForm', 'approval_needed' => 1, 'ref_id' => 1, 'table' => 'testForm'), array('title' => (object)array('label' => 'عنوان', 'type' => 'textarea')));
    }
}