<?php
//Route::get('center/selectinsured','CenterController@selectinsured');
Route::controller('center','CenterController');
Route::controller('expert','ExpertController');
Route::controller('admin','AdminController');
Route::controller('/','HomeController');






