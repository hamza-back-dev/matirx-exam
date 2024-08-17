<?php

namespace App\Listeners;

use App\Events\UserSaved;
use App\Service\UserService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SaveUserBackgroundInformation
{

    public $userService;
    /**
     * Create the event listener.
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Handle the event.
     */
    public function handle(UserSaved $event): void
    {
        $this->userService->saveBackgroundInformation($event->user);
    }
}
