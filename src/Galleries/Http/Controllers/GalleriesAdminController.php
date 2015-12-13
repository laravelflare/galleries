<?php

namespace LaravelFlare\Galleries\Http\Controllers;

use LaravelFlare\Cms\Slugs\Slug;
use LaravelFlare\Galleries\Gallery;
use LaravelFlare\Flare\Admin\AdminManager;
use LaravelFlare\Flare\Admin\Modules\ModuleAdminController;
use LaravelFlare\Galleries\Http\Requests\GalleryEditRequest;
use LaravelFlare\Galleries\Http\Requests\GalleryCreateRequest;

class GalleriesAdminController extends ModuleAdminController
{
    /**
     * Admin Instance.
     * 
     * @var 
     */
    protected $admin;

    /**
     * __construct.
     * 
     * @param AdminManager $adminManager
     */
    public function __construct(AdminManager $adminManager)
    {
        // Must call parent __construct otherwise 
        // we need to redeclare checkpermissions
        // middleware for authentication check
        parent::__construct($adminManager);

        $this->admin = $this->adminManager->getAdminInstance();
    }

    /**
     * Index Gallery for Module.
     *
     * Lists the current galleries in the system.
     * 
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        return view('flare::admin.galleries.index', [
                                                    'galleries' => Gallery::paginate(),
                                                    'totals' => [
                                                        'all' => Gallery::get()->count(),
                                                        'with_trashed' => Gallery::withTrashed()->get()->count(),
                                                        'only_trashed' => Gallery::onlyTrashed()->get()->count(),
                                                    ],
                                                ]
                                            );
    }

    /**
     * Lists Trashed Gallerys.
     * 
     * @return \Illuminate\Http\Response
     */
    public function getTrashed()
    {
        return view('flare::admin.galleries.trashed', [
                                                    'galleries' => Gallery::onlyTrashed()->paginate(),
                                                    'totals' => [
                                                        'all' => Gallery::get()->count(),
                                                        'with_trashed' => Gallery::withTrashed()->get()->count(),
                                                        'only_trashed' => Gallery::onlyTrashed()->get()->count(),
                                                    ],
                                                ]
                                            );
    }

    /**
     * List All Gallerys inc Trashed.
     * 
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        return view('flare::admin.galleries.all', ['galleries' => Gallery::withTrashed()->paginate(),
                                                    'totals' => [
                                                        'all' => Gallery::get()->count(),
                                                        'with_trashed' => Gallery::withTrashed()->get()->count(),
                                                        'only_trashed' => Gallery::onlyTrashed()->get()->count(),
                                                    ],
                                                ]
                                            );
    }

    /**
     * Create a new Gallery.
     * 
     * @return \Illuminate\Http\Response
     */
    public function getCreate()
    {
        return view('flare::admin.galleries.create', ['gallery' => new Gallery()]);
    }

    /**
     * Processes a new Gallery Request.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreate(GalleryCreateRequest $request)
    {
        $gallery = Gallery::create($request->only(['name', 'content', 'template']));
        $gallery->saveSlug($request->input('slug'));

        return redirect($this->admin->currentUrl('edit/'.$gallery->id))->with('notifications_below_header', [['type' => 'success', 'icon' => 'check-circle', 'title' => 'Success!', 'message' => 'Your gallery was successfully created.', 'dismissable' => false]]);
    }

    /**
     * Edit a Gallery.
     * 
     * @return \Illuminate\Http\Response
     */
    public function getEdit($galleryId)
    {
        return view('flare::admin.galleries.edit', ['gallery' => Gallery::withTrashed()->findOrFail($galleryId)]);
    }

    /**
     * Processes a new Gallery Request.
     *
     * Be proud of yourself, while the `set as homegallery`
     * logic shouldn't be here, at least you have got
     * all Otwell and finally hit the 3 characters
     * shorter per line comments nearly spot on
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEdit(GalleryEditRequest $request, $galleryId)
    {
        $gallery = Gallery::withTrashed()->findOrFail($galleryId)->fill($request->only(['name', 'template']));
        $gallery->save();
        $gallery->saveSlug($request->input('slug'));

        return redirect($this->admin->currentUrl('edit/'.$galleryId))->with('notifications_below_header', [['type' => 'success', 'icon' => 'check-circle', 'title' => 'Success!', 'message' => 'Your gallery was successfully updated.', 'dismissable' => false]]);
    }

    /**
     * Delete a Gallery.
     *
     * @param int $galleryId
     * 
     * @return \Illuminate\Http\Response
     */
    public function getDelete($galleryId)
    {
        return view('flare::admin.galleries.delete', ['gallery' => Gallery::withTrashed()->findOrFail($galleryId)]);
    }

    /**
     * Process Delete Gallery Request.
     *
     * @param int $galleryId
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDelete($galleryId)
    {
        $gallery = Gallery::withTrashed()->findOrFail($galleryId);

        if ($gallery->trashed()) {
            $gallery->slug()->delete();
            $gallery->forceDelete();
            $action = 'deleted';
        } else {
            $gallery->delete();
            $action = 'trashed';
        }

        return redirect($this->admin->currentUrl())->with('notifications_below_header', [['type' => 'success', 'icon' => 'check-circle', 'title' => 'Success!', 'message' => 'The gallery was successfully '.$action.'.', 'dismissable' => false]]);
    }

    /**
     * Restore a Gallery.
     *
     * @param int $galleryId
     * 
     * @return \Illuminate\Http\Response
     */
    public function getRestore($galleryId)
    {
        return view('flare::admin.galleries.restore', ['gallery' => Gallery::onlyTrashed()->findOrFail($galleryId)]);
    }

    /**
     * Process Restore Gallery Request.
     *
     * @param int $galleryId
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postRestore($galleryId)
    {
        $gallery = Gallery::onlyTrashed()->findOrFail($galleryId)->restore();

        return redirect($this->admin->currentUrl())->with('notifications_below_header', [['type' => 'success', 'icon' => 'check-circle', 'title' => 'Success!', 'message' => 'The gallery was successfully restored.', 'dismissable' => false]]);
    }

    /**
     * Clone a Gallery.
     *
     * @param int $galleryId
     * 
     * @return \Illuminate\Http\Response
     */
    public function getClone($galleryId)
    {
        Gallery::findOrFail($galleryId)->replicate()->save();

        return redirect($this->admin->currentUrl())->with('notifications_below_header', [['type' => 'success', 'icon' => 'check-circle', 'title' => 'Success!', 'message' => 'The gallery was successfully cloned.', 'dismissable' => false]]);
    }

    /**
     * Method is called when the appropriate controller
     * method is unable to be found or called.
     * 
     * @param array $parameters
     * 
     * @return
     */
    public function missingMethod($parameters = array())
    {
        parent::missingMethod();
    }
}
