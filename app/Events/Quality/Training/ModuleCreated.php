<?php

namespace App\Events\Quality\Training;

use App\Models\Quality\Training\Module;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModuleCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Module $module,
    ) {}
}
