<?php

namespace App\Exports;

use App\Models\Anggota;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithColumnWidths
};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class AnggotaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    public function collection()
    {
        return Anggota::with('akun')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'Email',
            'Username',
            'Alamat',
            'No Telepon',
            'Status',
            'Tanggal Daftar',
        ];
    }

    public function map($anggota): array
    {
        return [
            $anggota->id,
            $anggota->nama,
            optional($anggota->akun)->email,
            optional($anggota->akun)->username,
            $anggota->alamat,
            $anggota->notlp,
            ucfirst($anggota->status ?? '-'),
            $anggota->created_at ? $anggota->created_at->format('d-m-Y H:i') : '-',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,    // ID
            'B' => 25,   // Nama
            'C' => 30,   // Email
            'D' => 25,   // Username
            'E' => 40,   // Alamat
            'F' => 20,   // No Telepon
            'G' => 15,   // Status
            'H' => 20,   // Tanggal Daftar
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();

        $sheet->getStyle('A1:H1')->getFont()->setBold(true);

        $sheet->getStyle('A1:H' . $highestRow)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        return [];
    }
}
