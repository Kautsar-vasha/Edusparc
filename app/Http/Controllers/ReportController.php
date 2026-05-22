<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Kelas;
use App\Models\Student;
use App\Models\Violation;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportController extends Controller
{
    public function cetakGuru($format) {
        $data = Teacher::select('name', 'username')->get()->toArray();
        $headers = ['Nama Guru / Tenaga Pendidik', 'Username'];
        return $this->generateFile('Data Seluruh Guru', $headers, $data, $format, 'Data_Guru');
    }

    public function cetakKelas($format) {
        $data = Kelas::select('nama_kelas')->orderBy('nama_kelas')->get()->toArray();
        $headers = ['Nama Kelas'];
        return $this->generateFile('Data Kelas EDUSPARC', $headers, $data, $format, 'Data_Kelas');
    }

    public function cetakSiswa(Request $request) {
        $format = $request->format;
        $kelas = $request->kelas;

        $data = Student::where('class', $kelas)
                        ->select('name', 'nisn', 'total_points')
                        ->orderBy('name')
                        ->get()->toArray();

        $headers = ['Nama Siswa', 'NISN', 'Total Poin Pelanggaran'];
        return $this->generateFile("Data Siswa Kelas $kelas", $headers, $data, $format, "Siswa_Kelas_$kelas");
    }

    public function cetakPelanggaran($format) {
        $violations = Violation::with('student')->latest()->get();
        $data = [];

        foreach($violations as $v) {
            $data[] = [
                $v->created_at->format('d/m/Y H:i'),
                $v->student->name ?? 'Terhapus',
                $v->student->class ?? '-',
                $v->type,
                '+' . $v->points
            ];
        }

        $headers = ['Tanggal Waktu', 'Nama Siswa', 'Kelas', 'Jenis Pelanggaran', 'Poin'];
        return $this->generateFile('Riwayat Pelanggaran Siswa', $headers, $data, $format, 'Riwayat_Pelanggaran');
    }

    private function generateFile($title, $headers, $data, $format, $filename) {
        if ($format === 'excel') {
            return Excel::download(new DataExport($data, $headers), $filename . '.xlsx');
        } else {
            $pdf = Pdf::loadView('laporan.pdf', compact('title', 'headers', 'data'));
            return $pdf->download($filename . '.pdf');
        }
    }
}

class DataExport implements FromCollection, WithHeadings {
    protected $data;
    protected $headers;

    public function __construct($data, $headers) {
        $this->data = $data;
        $this->headers = $headers;
    }

    public function collection() {
        return collect($this->data);
    }

    public function headings(): array {
        return $this->headers;
    }
}
