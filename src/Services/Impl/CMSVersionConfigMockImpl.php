<?php

namespace JFC\Modules\CMSVersion\Services\Impl;

use Exception;
use JFC\Modules\CMSVersion\Services\CMSVersionConfig;

/**
 * An implementation of CMSVersionConfig where the configuration can be conveniently
 * inserted. 
 *
 * @author Ervinne Sodusta <ervinne.sodusta@nuworks.ph>
 */
class CMSVersionConfigMockImpl extends CMSVersionConfigLaravelImpl implements CMSVersionConfig
{

    protected $mockConfigs = [];

    public function getVersionDetector()
    {
        throw new Exception('getVersionDetector is not implemented');
    }

    public function getModelConfigs($modelTable)
    {
        $this->groupModels($this->mockConfigs['models'], $modelTable);
    }

    public function getModelListConfigs()
    {
        $this->groupModels($this->mockConfigs['models']);
    }

    public function getPivotTable($modelTable)
    {
        throw new Exception('getPivotTable is not implemented');
    }

    function getMockConfigs()
    {
        return $this->mockConfigs;
    }

    function setMockConfigs($mockConfigs)
    {
        $this->mockConfigs = $mockConfigs;
    }

}
