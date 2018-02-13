<?php

namespace JFC\Modules\CMSVersion\Repositories\Impl;

use Exception;
use Illuminate\Support\Facades\DB;
use JFC\Modules\CMSVersion\Models\CMSVersion;
use JFC\Modules\CMSVersion\Repositories\CMSVersionRepository;
use JFC\Modules\CMSVersion\Services\CMSVersionConfig;

/**
 * Description of CMSVersionRepositoryDefaultImpl
 *
 * @author Ervinne Sodusta <ervinne.sodusta@nuworks.ph>
 */
class CMSVersionRepositoryDefaultImpl implements CMSVersionRepository
{

    /** @var CMSVersionConfig */
    protected $config;

    public function __construct(CMSVersionConfig $config)
    {
        $this->config = $config;
    }

    public function save(CMSVersion $version)
    {
        return DB::transaction(function() use ($version) {
                    //  There should be only 1 version published
                    if ($version->status === 'Published') {
                        CMSVersion::whereStatus('Published')->update(['status' => 'Draft']);
                    }

                    $version->save();

                    $this->updateVersionControlledModels($version->id);

                    return $version;
                });
    }

    public function updateVersionControlledModels($versionId)
    {
        $models = $this->config->getModelListConfigs();

        foreach ($models as $modelConfig) {
            $this->resetModelsData($modelConfig);
            $this->updateModelToVersion($modelConfig, $versionId);
        }
    }

    public function updateModelToVersion($modelConfig, $versionId)
    {
        $pivotTable    = $this->config->getPivotTable($modelConfig['table']);
        $versionModels = DB::table($pivotTable)->whereVersionId($versionId)->get();

        foreach ($versionModels as $versionModel) {
            $updates = [];

            foreach ($modelConfig['fields'] as $field) {
                $updates[$field['is_published_field']] = $versionModel->{$field['is_published_field']};
            }

            //  apply version data
            DB::table($modelConfig['table'])
                    ->where($modelConfig['primary_key'], $versionModel->model_id)
                    ->update($updates);
        }
    }

    public function resetModelsData($modelConfig)
    {

        $resetUpdates = [];

        foreach ($modelConfig['fields'] as $field) {
            $resetUpdates[$field['is_published_field']] = $field['is_unpublished_value'];
        }

        //  reset other data
        DB::table($modelConfig['table'])
                ->update($resetUpdates);
    }

    public function delete($id)
    {
        //  TODO: delete the version and its data
        throw new Exception('Not yet implemented');
    }

}
