<?php

namespace App\Enums;

enum Transmission: string
{
    case Manuelni = 'manuelni';
    case Automatski = 'automatski';
    case DsgCvt = 'dsg_cvt';
}