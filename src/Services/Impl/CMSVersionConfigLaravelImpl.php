<?php

namespace JFC\Modules\CMSVersion\Services\Impl;

use JFC\Modules\CMSVersion\Services\CMSVersionConfig;
use function config;

/**
 * Description of CMSVersionConfigLaravelImpl
 *
 * @author Ervinne Sodusta <ervinne.sodusta@nuworks.ph>
 */
class CMSVersionConfigLaravelImpl implements CMSVersionConfig
{

    public function getVersionDetector()
    {
        return config('cms-version.detect_version');
    }

    public function getModelConfigs($modelTable)
    {
        $models = config('cms-version.models');
        return $this->groupModels($models, $modelTable);
    }

    public function getModelListConfigs()
    {
        $models = config('cms-version.models');
        return $this->groupModels($models);
    }

    public function getPivotTable($modelTable)
    {
        $modelConfig = $this->getModelConfigs($modelTable);
        return "{$modelConfig['table']}_version_pivot";
    }

    /**
     * Groups model configurations with the same table
     * 
     * @param array $models     The list of model configurations to group
     * @param string $table     If specified, returns only data from the model with the same table
     * @return array            The grouped and mapped version of the model list
     */
    protected function groupModels($models, $table = null)
    {
        $groupedModels = [];

        foreach ($models as $model) {
            if (array_key_exists($model['table'], $groupedModels)) {
                $groupedModels[$model['table']]['fields'][] = $model;
            } else {
                $groupedModels[$model['table']] = [
                    'table'       => $model['table'],
                    'primary_key' => $model['primary_key'],
                    'classpath'   => $model['classpath'],
                    'fields'      => [$model]
                ];
            }
        }

        if ($table) {
            return $groupedModels[$table];
        } else {
            return $groupedModels;
        }
    }

}
