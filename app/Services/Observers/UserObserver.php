<?php

namespace App\Services\Observers;

use App\User;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;

class UserObserver {

    protected $events;

    /**
     * Handle the post "created" event.
     *
     * @param  \App\Post  $post
     * @return void
     */
    public function created(User $user) {
        Mail::to($user->email)->send(new SendMail($user, "verify-email"));
    }

    public function saving(User $user){
    }
    public function saved(User $user){
    }
    /**
     * Handle the post "updated" event.
     *
     * @param  \App\Post  $post
     * @return void
     */
    public function updated(User $user) {
    }

       public function updating(User $user) {
    }
    /**
     * Handle the post "deleted" event.
     *
     * @param  \App\Post  $post
     * @return void
     */
    public function deleted(User $user) {
        
    }

    /**
     * Handle the post "restored" event.
     *
     * @param  \App\Post  $post
     * @return void
     */
    public function restored(User $user) {
        dd('restored');
    }

    /**
     * Handle the post "force deleted" event.
     *
     * @param  \App\Post  $post
     * @return void
     */
    public function forceDeleted(User $user) {
        dd('forceDeleted');
    }

}
