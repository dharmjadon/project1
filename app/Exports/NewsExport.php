<?php

namespace App\Exports;

use App\Models\News;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
// use Maatwebsite\Excel\Concerns\FromCollection;

class NewsExport implements  FromQuery,WithHeadings,ShouldAutoSize
{

    /**
    * @return \Illuminate\Support\Collection
    */


        public function headings(): array
        {
    return [
        'S No',
        'Title',
        'Publisher',
        'Publish Date',
    ];
}
public function query()
{
    // return News::query();
     return News::query()->select( DB::raw('ROW_NUMBER() OVER(ORDER BY ID) AS Row'),'title', 'publisher',DB::raw('DATE_FORMAT(publish_date, "%d-%b-%Y") as publish_date'));
}
public function map($news): array
{
    return [
        $news->id,
        $news->title,
        $news->publisher,
        Date::dateTimeToExcel($news->publish_date)
    ];
}


public function columnFormats(): array
{
    return [
        'D' => 'dd-mm-yyyy'
    ];
}
}
