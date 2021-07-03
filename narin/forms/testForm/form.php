<?php
require_once $NarinConfig->path . '/lib/narin/narinform.php';
class testForm_form extends NarinForm {
    public function __construct() {
        parent::__construct((object)array('title' => 'فرم تست', 'name' => 'testForm', 'approval_needed' => 1, 'captcha' => 1, 'ref_id' => 1, 'on_success' => 'msg', 'table' => 'testForm'), array('title' => (object)array('validation' => 'farsi-text', 'max_len' => 255, 'type' => 'textarea')));
    }
    protected function form_view() {
        return '<form id="narin_form_testForm" method="post" data-cols="2">'.'<input type="hidden" name="_form_" value="testForm" />'.'<input type="hidden" name="_token_" value="'.(parent::generate_token()).'" />'.'<h1>فرم تست</h1>'.'<p'.(parent::attr('title')).'><label><span>عنوان</span><textarea name="title" data-maxlength="255">'.(parent::value('title')).'</textarea></label> <em>فقط حروف و علائم فارسی</em><span class="textarea-maxlen">حداکثر 255 کارکتر</span></p>'.'<p'.(parent::attr('_captcha_')).'><span class="captcha"><img title="کد امنیتی" src="'.(parent::captcha_url()).'" /><a href="#new">تصویر جدید</a></span><label><span>کد امنیتی:</span><input type="text" name="_captcha_" autocomplete="off" dir="ltr" /> <strong><abbr title="الزامی">*</abbr></strong></label></p>'.'<p class="buttons"><input type="submit" value="ارسال" /><input type="reset" value="لغو" /></p>'.'</form>'.'<script type="text/javascript"> NarinForm.add(\'narin_form_testForm\',{"title":{"validation":"farsi-text","max_len":255,"type":"textarea"}}); </script>';
    }
}