<?php

namespace LaravelFlare\Galleries\Http\Controllers;

use LaravelFlare\Galleries\Gallery;
use LaravelFlare\Cms\Slugs\Slug;
use LaravelFlare\Flare\Admin\AdminManager;
use LaravelFlare\Gallerys\Http\Requests\GalleryEditRequest;
use LaravelFlare\Gallerys\Http\Requests\GalleryCreateRequest;
use LaravelFlare\Flare\Admin\Modules\ModuleAdminController;

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
        $page = Gallery::create($request->only(['name', 'content', 'template']));
        $page->saveSlug($request->input('slug'));
        $page->author()->associate(\Auth::user())->save();

        return redirect($this->admin->currentUrl('edit/'.$page->id))->with('notifications_below_header', [['type' => 'success', 'icon' => 'check-circle', 'title' => 'Success!', 'message' => 'Your page was successfully created.', 'dismissable' => false]]);
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
     * Be proud of yourself, while the `set as homepage`
     * logic shouldn't be here, at least you have got
     * all Otwell and finally hit the 3 characters
     * shorter per line comments nearly spot on
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEdit(GalleryEditRequest $request, $galleryId)
    {
        $page = Gallery::withTrashed()->findOrFail($galleryId)->fill($request->only(['name', 'content', 'template']));
        $page->author()->associate(\Auth::user());
        $page->save();
        $page->saveSlug($request->input('slug'));

        return redirect($this->admin->currentUrl('edit/'.$galleryId))->with('notifications_below_header', [['type' => 'success', 'icon' => 'check-circle', 'title' => 'Success!', 'message' => 'Your page was successfully updated.', 'dismissable' => false]]);
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
        return view('flare::admin.galleries.delete', ['page' => Gallery::withTrashed()->findOrFail($galleryId)]);
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
        $page = Gallery::withTrashed()->findOrFail($galleryId);

        if ($page->trashed()) {
            $page->slug()->delete();
            $page->forceDelete();
            $action = 'deleted';
        } else {
            $page->delete();
            $action = 'trashed';
        }

        return redirect($this->admin->currentUrl())->with('notifications_below_header', [['type' => 'success', 'icon' => 'check-circle', 'title' => 'Success!', 'message' => 'The page was successfully '.$action.'.', 'dismissable' => false]]);
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
        return view('flare::admin.galleries.restore', ['page' => Gallery::onlyTrashed()->findOrFail($galleryId)]);
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
        $page = Gallery::onlyTrashed()->findOrFail($galleryId)->restore();

        return redirect($this->admin->currentUrl())->with('notifications_below_header', [['type' => 'success', 'icon' => 'check-circle', 'title' => 'Success!', 'message' => 'The page was successfully restored.', 'dismissable' => false]]);
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

        return redirect($this->admin->currentUrl())->with('notifications_below_header', [['type' => 'success', 'icon' => 'check-circle', 'title' => 'Success!', 'message' => 'The page was successfully cloned.', 'dismissable' => false]]);
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
