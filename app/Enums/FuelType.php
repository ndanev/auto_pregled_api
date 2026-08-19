<?php

namespace App\Enums;

enum FuelType: string
{
    case Benzin = 'benzin';
    case Dizel = 'dizel';
    case Hibrid = 'hibrid';
    case PlugInHibrid = 'plug_in_hibrid';
    case Elektro = 'elektro';
}
