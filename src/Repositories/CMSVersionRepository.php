<?php

namespace Ervinne\CMSVersion\Repositories;

use Ervinne\CMSVersion\Models\CMSVersion;

/**
 *
 * @author Ervinne Sodusta <ervinne.sodusta@nuworks.ph>
 */
interface CMSVersionRepository
{

    function save(CMSVersion $version);
    
    function delete($id);
}
