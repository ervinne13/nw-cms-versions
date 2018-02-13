<?php

namespace JFC\Modules\CMSVersion;

use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use JFC\Modules\CMSVersion\Services\CMSVersionConfig;

/**
 *
 * @author Ervinne Sodusta <ervinne.sodusta@nuworks.ph>
 */
trait VersionControlled
{

    public function scopePublishedVersion($query, $isPublishedField)
    {
        $config      = App::make(CMSVersionConfig::class);
        $modelConfig = $config->getModelConfigs($this->table);
        $pivotTable  = $config->getPivotTable($this->table);

        foreach ($modelConfig['fields'] as $field) {
            if ($field['is_published_field'] == $isPublishedField) {
                $fieldConfig = $field;
                break;
            }
        }

        if (!$fieldConfig) {
            throw new Exception("{$isPublishedField} does not exist in {$pivotTable}");
        }

        return $query
                        ->select("{$this->table}.*")
                        ->where('cms_versions.status', 'Published')
                        ->join($pivotTable, "{$this->table}.{$modelConfig['primary_key']}", '=', 'model_id')
                        ->join('cms_versions', "{$pivotTable}.version_id", '=', 'cms_versions.id');
    }

    /**
     * Save the model and the version pivot to the database.
     *
     * @param  array  $options
     * @return bool
     */
    public function saveWithVersion(array $options = [])
    {
        $config      = App::make(CMSVersionConfig::class);
        $modelConfig = $config->getModelConfigs($this->table);
        $pivotTable  = $config->getPivotTable($this->table);

        $version = Models\CMSVersion::find($this->version_id);

        $this->validate($modelConfig);

        unset($this->version_id);

        $pivotData = [];

        foreach ($modelConfig['fields'] as $field) {
            $pivotData[$field['is_published_field']] = $this->{$field['is_published_field']};

            if (!$field['has_local_field'] || $version->status !== 'Published') {
                unset($this->{$field['is_published_field']});
            }
        }

        //  save the current model after stripping non model fields
        parent::save($options);

        //  prepare & save pivot data        
        $pivotData['version_id'] = $version->id;
        $pivotData['model_id']   = $this->{$modelConfig['primary_key']};

        $this->unpublishRelatedRecords($modelConfig['fields'], $pivotTable, $pivotData);
        $this->savePivotData($pivotTable, $pivotData);
    }

    protected function unpublishRelatedRecords($fields, $pivotTable, $pivotData)
    {
        foreach ($fields as $field) {
            if ($pivotData[$field['is_published_field']] == $field['is_published_value']) {
                DB::table($pivotTable)
                        ->whereModelId($pivotData['model_id'])
                        ->where($field['is_published_field'], $field['is_published_value'])
                        ->update([$field['is_published_field'] => $field['is_unpublished_value']]);
            }
        }
    }

    protected function savePivotData($pivotTable, $pivotData)
    {
        $existingPivotQuery = DB::table($pivotTable)
                ->whereVersionId($pivotData['version_id'])
                ->whereModelId($pivotData['model_id']);

        $existingPivot = $existingPivotQuery->first();

        if ($existingPivot) {
            $existingPivotQuery->update($pivotData);
        } else {
            DB::table($pivotTable)->insert($pivotData);
        }
    }

    protected function validate($modelConfig)
    {
        if (count($modelConfig['fields']) < 1) {
            throw new Exception('Unable to save version controlled model. No fields configured.');
        }

        if (!$this->version_id) {
            throw new Exception('Unable to save version controlled model. version_id is not defined.');
        }
    }

}
