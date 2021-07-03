<?php
require_once $NarinConfig->path . '/lib/narin/narindataviewer.php';
class s_dataviewer extends NarinDataViewer {
    public function __construct() {
        parent::__construct((object)array('title' => 's', 'approval_needed' => 1, 'ref_id' => 1, 'name' => 's', 'table' => 's'), array('af' => (object)array('label' => 'af', 'type' => 'textarea')));
    }
}