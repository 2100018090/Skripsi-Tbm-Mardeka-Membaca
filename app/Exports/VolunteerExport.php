<?php

namespace App\Exports;

use App\Models\Voluntter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class VolunteerExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles
{
    public function collection()
    {
        return Voluntter::with('akun')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'Email',
            'Username',
            'Jabatan',
            'Status',
            'Tanggal Daftar',
        ];
    }

    public function map($voluntter): array
    {
        return [
            $voluntter->id,
            $voluntter->nama,
            optional($voluntter->akun)->email,
            optional($voluntter->akun)->username,
            $voluntter->jabatan,
            ucfirst($voluntter->status ?? '-'),
            $voluntter->created_at ? $voluntter->created_at->format('d-m-Y H:i') : '-',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,    // ID
            'B' => 25,   // Nama
            'C' => 30,   // Email
            'D' => 25,   // Username
            'E' => 20,   // Jabatan
            'F' => 15,   // Status
            'G' => 20,   // Tanggal Daftar
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();

        $sheet->getStyle('A1:G1')->getFont()->setBold(true);

        $sheet->getStyle("A1:G{$highestRow}")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);

        return [];
    }
}

