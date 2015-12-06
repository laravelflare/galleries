<?php

namespace LaravelFlare\Galleries\Policies;

class GalleryPolicy
{
    /**
     * Determine if the given Model can be viewed by the user.
     *
     * @param  $user
     * @param  $admin
     * 
     * @return bool
     */
    public function view($user, $admin)
    {
        return $user->is_admin;
    }
}
