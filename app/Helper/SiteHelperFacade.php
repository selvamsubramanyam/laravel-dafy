<?php

namespace App\Helper;
use Illuminate\Support\Facades\Facade;

class SiteHelperFacade extends Facade
{
    protected static function getFacadeAccessor()
    { 
      return 'helperclass';
    }
}
