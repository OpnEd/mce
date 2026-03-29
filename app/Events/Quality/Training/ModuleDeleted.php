<?php

namespace App\Events\Quality\Training;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModuleDeleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public array $moduleData,
        public int $moduleId,
    ) {}
}
