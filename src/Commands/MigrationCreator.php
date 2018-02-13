<?php

namespace Ervinne\CMSVersion\Commands;

use Exception;
use Illuminate\Database\Migrations\MigrationCreator as BaseMigrationCreator;
use Illuminate\Filesystem\Filesystem;
use Ervinne\CMSVersion\Services\CMSVersionConfig;

/**
 * Description of MigrationCreator
 *
 * @author Ervinne Sodusta <ervinne.sodusta@nuworks.ph>
 */
class MigrationCreator extends BaseMigrationCreator
{

    /** @var CMSVersionConfig */
    protected $config;

    public function __construct(Filesystem $files, CMSVersionConfig $config)
    {
        parent::__construct($files);

        $this->config = $config;
    }

    /**
     * Get the path to the stubs.
     *
     * @return string
     */
    public function stubPath()
    {
        return __DIR__ . '/stubs';
    }

    protected function getStub($table, $create)
    {
        return $this->files->get($this->stubPath() . '/cmsvmigration.stub');
    }

    protected function getTableStub()
    {
        return $this->files->get($this->stubPath() . '/cmsvmigration-table.stub');
    }

    protected function getColumnStub()
    {
        return $this->files->get($this->stubPath() . '/cmsvmigration-column.stub');
    }

    protected function getDropTableStub()
    {
        return $this->files->get($this->stubPath() . '/cmsvmigration-drop-table.stub');
    }

    /**
     * Populate the place-holders in the migration stub.
     *
     * @param  string  $name
     * @param  string  $stub
     * @param  string  $table
     * @return string
     */
    protected function populateStub($name, $stub, $table)
    {
        $stub = str_replace('DummyClass', $this->getClassName($name), $stub);

        $stub = str_replace('CreateTablesPlaceholder', $this->generateCreateTableScripts(), $stub);

        return str_replace('DropTablesPlaceholder', $this->generateDropTableScripts(), $stub);
    }

    protected function generateCreateTableScripts()
    {
        $models = $this->config->getModelListConfigs();

        $tableStub = $this->getTableStub();

        $createTableScripts = '';

        foreach ($models as $model) {
            $createTableScript = str_replace('DummyTable', "{$model['table']}_version_pivot", $tableStub);
            $createTableScript = str_replace('DummyModelPK', "{$model['primary_key']}", $createTableScript);
            $createTableScript = str_replace('DummyModelTable', $model['table'], $createTableScript);

            $fieldsScript = '';
            foreach ($model['fields'] as $field) {
                $fieldsScript .= $this->generateCreateColumnScript($field);
            }

            $createTableScript = str_replace('TableColumnsPlaceholder', $fieldsScript, $createTableScript);

            $createTableScripts .= $createTableScript;
        }

        return $createTableScripts;
    }

    protected function generateDropTableScripts()
    {
        $models          = $this->config->getModelListConfigs();
        $dropTableStub   = $this->getDropTableStub();
        $dropTableScript = '';

        foreach ($models as $model) {
            $dropTableScript .= str_replace('DummyTable', "{$model['table']}_version_pivot", $dropTableStub);
        }

        return $dropTableScript;
    }

    protected function generateCreateColumnScript($field)
    {
        $columnStub = str_replace('DummyColumn', $field['is_published_field'], $this->getColumnStub());

        $type            = gettype($field['is_published_value']);
        $supportedFields = ['boolean', 'integer', 'string'];

        if (!in_array($type, $supportedFields)) {
            throw new Exception('Unsupported published value type: ' . gettype($field['is_published_value']));
        }

        return str_replace('DummyType', $type, $columnStub);
    }

}
