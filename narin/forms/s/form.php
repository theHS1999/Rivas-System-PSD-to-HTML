<?php
require_once $NarinConfig->path . '/lib/narin/narinform.php';
class s_form extends NarinForm {
    public function __construct() {
        parent::__construct((object)array('title' => 's', 'approval_needed' => 1, 'captcha' => 1, 'ref_id' => 1, 'on_success' => 'msg-new', 'name' => 's', 'table' => 's'), array('af' => (object)array('type' => 'textarea')));
    }
    protected function form_view() {
        return '<form id="narin_form_s" method="post">'.'<input type="hidden" name="_form_" value="s" />'.'<input type="hidden" name="_token_" value="'.(parent::generate_token()).'" />'.'<h1>s</h1>'.'<p'.(parent::attr('af')).'><label><span>af</span><textarea name="af">'.(parent::value('af')).'</textarea></label></p>'.'<p'.(parent::attr('_captcha_')).'><span class="captcha"><img title="کد امنیتی" src="'.(parent::captcha_url()).'" /><a href="#new">تصویر جدید</a></span><label><span>کد امنیتی:</span><input type="text" name="_captcha_" autocomplete="off" dir="ltr" /> <strong><abbr title="الزامی">*</abbr></strong></label></p>'.'<p class="buttons"><input type="submit" value="ارسال" /></p>'.'</form>';
    }
}