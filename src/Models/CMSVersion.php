<?php

namespace JFC\Modules\CMSVersion\Models;

use Illuminate\Database\Eloquent\Model;

class CMSVersion extends Model
{

    protected $table    = 'cms_versions';
    protected $fillable = ['display_name', 'description', 'status', 'publish_at'];

    public function scopeActive($query)
    {
        //  TODO: Active filter here
        return $query;
    }

}
