<?php

namespace CargoTrackingForWooCommerce\Base;

use CargoTrackingForWooCommerce\Base\Assets;
use CargoTrackingForWooCommerce\Base\MetaBox;
use CargoTrackingForWooCommerce\Base\Hooks\Activate;
use CargoTrackingForWooCommerce\Base\Hooks\Deactivate;

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
