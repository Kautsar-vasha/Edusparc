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
    // 1. LAPORAN SELURUH GURU
    public function cetakGuru($format) {
        $data = Teacher::select('nip', 'name', 'subject', 'role_tambahan')->get()->toArray();
        $headers = ['NIP/NUPTK', 'Nama Guru', 'Mata Pelajaran', 'Peran Guru'];
        return $this->generateFile('Data Seluruh Guru & Tenaga Pendidik', $headers, $data, $format, 'Data_Guru');
    }

    // 2. LAPORAN DAFTAR SISWA PER KELAS (Urut NISN)
    public function cetakKelas(Request $request) {
        $format = $request->format;
        $kelas = $request->kelas;

        $data = Student::where('class', $kelas)
                        ->select('nisn', 'name', 'total_points')
                        ->orderBy('nisn', 'asc')
                        ->get()->toArray();

        // Teks diperbarui menjadi lebih netral
        $headers = ['NISN', 'Nama Siswa', 'Total Poin Karakter'];
        return $this->generateFile("Data Daftar Siswa Kelas $kelas", $headers, $data, $format, "Data_Kelas_$kelas");
    }

    // 3. LAPORAN RIWAYAT INDIVIDU SISWA SPESIFIK
    public function cetakRiwayat(Request $request) {
        $format = $request->format;
        $student_id = $request->student_id;

        $student = Student::findOrFail($student_id);
        $violations = Violation::where('student_id', $student_id)
                               ->orderBy('created_at', 'asc')
                               ->get();

        if ($format === 'excel') {
            $data = [];
            foreach($violations as $v) {
                $data[] = [
                    $v->created_at->format('d/m/Y H:i'),
                    $v->type,
                    $v->motivation ?? '-',
                    ($v->jenis_poin == 'positif' ? '+' : '-') . $v->points
                ];
            }
            // Teks diperbarui menjadi lebih netral
            $headers = ['Tanggal Waktu', 'Aktivitas / Perilaku', 'Motivasi', 'Poin'];
            return Excel::download(new DataExport($data, $headers), "Riwayat_{$student->name}.xlsx");
        } else {
            $pdf = Pdf::loadView('laporan.pdf_riwayat', compact('student', 'violations'));
            return $pdf->download("Riwayat_Karakter_{$student->name}.pdf");
        }
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
    protected $data; protected $headers;
    public function __construct($data, $headers) { $this->data = $data; $this->headers = $headers; }
    public function collection() { return collect($this->data); }
    public function headings(): array { return $this->headers; }
}
