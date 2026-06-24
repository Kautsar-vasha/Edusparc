<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Karakter - {{ $student->name }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12px; color: #333; }
        .kop-surat { width: 100%; border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 20px; border-collapse: collapse; }
        .kop-surat td { border: none; padding: 0; }
        .logo { width: 80px; text-align: center; }
        .teks-kop { text-align: center; }
        .teks-kop h2 { margin: 0; font-size: 20px; }
        .teks-kop h4 { margin: 5px 0; font-size: 14px; font-weight: normal; }
        .teks-kop p { margin: 0; font-size: 11px; font-style: italic; color: #555; }

        .info-siswa { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
        .info-siswa td { padding: 4px 0; font-size: 13px; font-weight: bold; border: none; }

        table.data { width: 100%; border-collapse: collapse; }
        table.data th, table.data td { border: 1px solid #999; padding: 8px; text-align: left; vertical-align: middle; }
        table.data th { background-color: #f2f2f2; text-align: center; }
        .bg-hijau { background-color: #d1e7dd; color: #0f5132; text-align: center; font-weight: bold; }
        .bg-kuning { background-color: #fff3cd; color: #856404; text-align: center; font-weight: bold; }
    </style>
</head>
<body>
    <table class="kop-surat">
        <tr>
            <td class="logo"><img src="{{ public_path('images/logo_sekolah.png') }}" style="width: 75px; height: auto;"></td>
            <td class="teks-kop">
                <h2>SMP NEGERI 4 JEMBER MUDADIDAYA</h2>
                <h4>Sistem Edukasi Profil Sikap & Karakter (EDUSPARC)</h4>
                <p>Jl. Nusa Indah No.14, Kelurahan Jember Lor, Kecamatan Patrang, Kabupaten Jember, Jawa Timur 68118</p>
            </td>
            <td style="width: 80px;"></td>
        </tr>
    </table>

    <h3 style="text-align: center; margin-bottom: 20px; text-transform: uppercase;">Riwayat Poin & Karakter Siswa</h3>

    <table class="info-siswa">
        <tr>
            <td style="width: 15%;">Nama Siswa</td><td style="width: 2%;">:</td><td>{{ $student->name }}</td>
        </tr>
        <tr>
            <td>Kelas</td><td>:</td><td>{{ $student->class }}</td>
        </tr>
        <tr>
            <td>NISN</td><td>:</td><td>{{ $student->nisn }}</td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Tanggal & Waktu</th>
                <th style="width: 35%;">Aktivitas / Perilaku</th>
                <th style="width: 30%;">Motivasi / Keterangan</th>
                <th style="width: 10%;">Poin</th>
            </tr>
        </thead>
        <tbody>
            @forelse($violations as $index => $v)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td style="text-align: center;">{{ $v->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $v->type }}</td>
                <td>{{ $v->motivation ?: '-' }}</td>

                @if($v->jenis_poin == 'positif')
                    <td class="bg-hijau">+{{ $v->points }}</td>
                @else
                    <td class="bg-kuning">-{{ $v->points }}</td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; font-style: italic;">Belum ada riwayat tercatat.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
