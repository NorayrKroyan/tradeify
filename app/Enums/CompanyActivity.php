<?php

namespace App\Enums;

enum CompanyActivity: string
{
    case Importer = 'importer';
    case Exporter = 'exporter';
    case Both = 'both';
}
