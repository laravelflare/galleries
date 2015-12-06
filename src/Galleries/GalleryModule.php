<?php

namespace LaravelFlare\Galleries;

use LaravelFlare\Flare\Admin\Modules\ModuleAdmin;

class GalleryModule extends ModuleAdmin
{
    /**
     * Admin Section Icon.
     *
     * Font Awesome Defined Icon, eg 'user' = 'fa-user'
     *
     * @var string
     */
    protected static $icon = 'photo';

    /**
     * Title of Admin Section.
     *
     * @var string
     */
    protected static $title = 'Gallery';

    /**
     * Plural Title of Admin Section.
     *
     * @var string
     */
    protected static $pluralTitle = 'Galleries';

    /**
     * The Controller to be used by the Pages Module.
     * 
     * @var string
     */
    protected static $controller = '\LaravelFlare\Galleries\Http\Controllers\GalleriesAdminController';
}
