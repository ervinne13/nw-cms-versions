<?php

namespace Ervinne\CMSVersion\Http\Controllers;

use Ervinne\CMSVersion\Services\CMSVersionConfig;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class VersionController extends Controller {

    protected $versionStatusList = ['Draft', 'Archived', 'Published'];

    public function getModelVersionData(CMSVersionConfig $config, $model, $versionId, $modelId) {
        $versionData = DB::table($config->getPivotTable($model))
                ->whereVersionId($versionId)
                ->whereModelId($modelId)
                ->first();

        $modelData = $config->getModelConfigs($model);
        $modelData['data'] = $versionData;

        return response()->json($modelData);
    }

}
