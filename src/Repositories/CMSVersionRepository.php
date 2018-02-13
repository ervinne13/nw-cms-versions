<?php

namespace JFC\Modules\CMSVersion\Repositories;

use JFC\Modules\CMSVersion\Models\CMSVersion;

/**
 *
 * @author Ervinne Sodusta <ervinne.sodusta@nuworks.ph>
 */
interface CMSVersionRepository
{

    function save(CMSVersion $version);
    
    function delete($id);
}
