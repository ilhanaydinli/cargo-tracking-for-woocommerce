<?php

namespace App\Base;

use App\Base\Assets;
use App\Base\MetaBox;
use App\Base\Hooks\Activate;
use App\Base\Hooks\Deactivate;

class RegisterServices
{
    public function __construct()
    {
        new Assets();
        new MetaBox();
        new Activate();
        new Deactivate();
    }
}
