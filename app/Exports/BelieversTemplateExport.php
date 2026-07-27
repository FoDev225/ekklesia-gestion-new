<?php

namespace App\Exports;

use App\Exports\Sheets\FideleTemplateSheet;
use App\Exports\Sheets\InstructionsTemplateSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BelieversTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new FideleTemplateSheet(),
            new InstructionsTemplateSheet(),
        ];
    }
}