<?php

Route::group(['prefix' => 'ervinne/nw-cms-version', 'namespace' => 'Ervinne\CMSVersion\Http\Controllers'], function() {
    Route::get('/model/{model}/version/{versionId}/data/{modelId}', 'VersionController@getModelVersionData');
});
