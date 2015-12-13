<?php

namespace LaravelFlare\Galleries;

use LaravelFlare\Cms\Views\Viewable;
use LaravelFlare\Cms\Slugs\Sluggable;
use Illuminate\Database\Eloquent\Model;
use LaravelFlare\Cms\Views\ViewableModel;
use LaravelFlare\Cms\Slugs\SluggableModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model implements Sluggable, Viewable
{
    use SluggableModel, ViewableModel, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'flare_cms_galleries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'template'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Default Gallery View.
     *
     * @return string
     */
    protected $view = 'flare::galleries.index';

    /**
     * Gallery has many Media.
     * 
     * @return
     */
    public function images()
    {
        return $this->belongsToMany(\LaravelFlare\Media\Media::class, 'flare_cms_gallery_images', 'gallery_id', 'media_id');
    }

    /**
     * Gallery View.
     * 
     * @return string
     */
    public function view()
    {   
        if (!$this->template || !view()->exists($this->template)) {
            return $this->view;
        }

        return $this->template;
    }
}
