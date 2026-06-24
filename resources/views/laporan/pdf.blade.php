<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12px; color: #333; }
        .kop-surat { width: 100%; border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 20px; border-collapse: collapse; }
        .kop-surat td { border: none; padding: 0; }
        .logo { width: 80px; text-align: center; }
        .teks-kop { text-align: center; }
        .teks-kop h2 { margin: 0; font-size: 20px; letter-spacing: 1px; }
        .teks-kop h4 { margin: 5px 0; font-size: 14px; font-weight: normal; }
        .teks-kop p { margin: 0; font-size: 11px; font-style: italic; color: #555; }

        table.data { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table.data th, table.data td { border: 1px solid #999; padding: 8px; text-align: left; }
        table.data th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
        h3.judul { text-align: center; margin-bottom: 15px; text-transform: uppercase; }
    </style>
</head>
<body>
    <table class="kop-surat">
        <tr>
            <td class="logo">
                <img src="{{ public_path('images/logo_sekolah.png') }}" style="width: 75px; height: auto;">
            </td>
            <td class="teks-kop">
                <h2>SMP NEGERI 4 JEMBER MUDADIDAYA</h2>
                <h4>Sistem Edukasi Profil Sikap & Karakter (EDUSPARC)</h4>
                <p>Jl. Nusa Indah No.14, Kelurahan Jember Lor, Kecamatan Patrang, Kabupaten Jember, Jawa Timur 68118</p>
            </td>
            <td style="width: 80px;"></td> </tr>
    </table>

    <h3 class="judul">{{ $title }}</h3>

    <table class="data">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                @foreach($headers as $h)
                    <th>{{ $h }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                @foreach($row as $cell)
                    <td>{{ $cell }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
