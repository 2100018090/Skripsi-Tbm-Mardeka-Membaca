<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PeminjamanExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles
{
    protected $tanggal_mulai, $tanggal_sampai;

    public function __construct($tanggal_mulai, $tanggal_sampai)
    {
        $this->tanggal_mulai = $tanggal_mulai;
        $this->tanggal_sampai = $tanggal_sampai;
    }

    /**
     * Ambil data dengan relasi anggota, buku, detail buku, dan voluntter
     */
    public function collection()
    {
        $query = Peminjaman::with([
            'anggota',
            'buku',
            'detailBuku',
            'voluntterPinjam',
            'voluntterKembali'
        ])
        ->whereIn('status_pengembalian', ['Dipinjam', 'Kembalikan', 'Terlambat']);

        if ($this->tanggal_mulai) {
            $tanggalAwal = \Carbon\Carbon::parse($this->tanggal_mulai . '-01');
            $query->whereDate('tanggal_pinjam', '>=', $tanggalAwal);
        }

        if ($this->tanggal_sampai) {
            $tanggalAkhir = \Carbon\Carbon::parse($this->tanggal_sampai . '-01')->endOfMonth();
            $query->whereDate('tanggal_pinjam', '<=', $tanggalAkhir);
        }

        return $query->get();
    }

    /**
     * Judul kolom Excel
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nama Anggota',
            'Judul Buku',
            'Kode Buku',
            'ISBN',
            'Penerbit',
            'Tahun Terbit',
            'Tanggal Pinjam',
            'Tanggal Ambil',
            'Tanggal Pengembalian',
            'Status Pengembalian',
            'Denda (Rp)',
            'Pemberi Buku',
            'Penerima Buku',
        ];
    }

    /**
     * Data setiap baris
     */
    public function map($row): array
    {
        return [
            $row->id,
            optional($row->anggota)->nama,
            optional($row->buku)->judul,
            optional($row->detailBuku)->kode,
            optional($row->buku)->isbn,
            optional($row->buku)->penerbit,
            optional($row->buku)->tahun_terbit,
            optional($row->tanggal_pinjam) ? date('d-m-Y', strtotime($row->tanggal_pinjam)) : '-',
            optional($row->tanggal_ambil) ? date('d-m-Y', strtotime($row->tanggal_ambil)) : '-',
            optional($row->tanggal_pengembalian) ? date('d-m-Y', strtotime($row->tanggal_pengembalian)) : '-',
            ucfirst($row->status_pengembalian ?? '-'),
            number_format($row->denda ?? 0, 0, ',', '.'),
            optional($row->voluntterPinjam)->nama ?? '-',
            optional($row->voluntterKembali)->nama ?? '-',
        ];
    }

    /**
     * Lebar kolom Excel
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,   // ID
            'B' => 25,  // Nama Anggota
            'C' => 35,  // Judul Buku
            'D' => 20,  // Kode Buku
            'E' => 20,  // ISBN
            'F' => 25,  // Penerbit
            'G' => 15,  // Tahun Terbit
            'H' => 18,  // Tanggal Pinjam
            'I' => 18,  // Tanggal Ambil
            'J' => 20,  // Tanggal Pengembalian
            'K' => 22,  // Status Pengembalian
            'L' => 15,  // Denda
            'M' => 25,  // Petugas Penyerahan
            'N' => 25,  // Petugas Pengembalian
        ];
    }

    /**
     * Gaya tampilan Excel
     */
    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();

        // Bold header
        $sheet->getStyle('A1:N1')->getFont()->setBold(true);

        // Center all cells
        $sheet->getStyle('A1:N' . $highestRow)->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        return [];
    }
}
