<?php

namespace Ervinne\CMSVersion\Services;

/**
 *
 * @author Ervinne Sodusta <ervinne.sodusta@nuworks.ph>
 */
interface CMSVersionConfig
{

    function getVersionDetector();
    
    function getModelListConfigs();

    function getModelConfigs($modelTable);
    
    function getPivotTable($modelTable);
}
