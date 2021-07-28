<?php 

Route::middleware(['web', 'auth', 'auth:sanctum', 'lang', 'verified'])->namespace('Phobrv\BrvRecruitment\Controllers')->group(function () {
	Route::middleware(['can:post_manage'])->prefix('admin')->group(function () {
			Route::resource('recruitment', 'RecruitmentController');
	});
});