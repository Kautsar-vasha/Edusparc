<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu QR - {{ $student->name }}</title>
    <style>
        body {
            background-color: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            background: white;
            width: 320px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            overflow: hidden;
            text-align: center;
            border: 2px solid #e5e7eb;
        }
        .card-header {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            padding: 20px;
        }
        .card-header img {
            width: 60px;
            margin-bottom: 10px;
        }
        .card-header h4 { margin: 0; font-size: 16px; font-weight: 600; letter-spacing: 1px;}
        .card-header p { margin: 5px 0 0; font-size: 11px; opacity: 0.9; }
        .card-body {
            padding: 30px 20px;
        }
        .qr-box {
            background: white;
            padding: 15px;
            border-radius: 12px;
            display: inline-block;
            border: 2px dashed #ccc;
            margin-bottom: 20px;
        }
        .student-name {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
            margin: 0 0 5px;
        }
        .student-nis {
            font-size: 16px;
            color: #4b5563;
            margin: 0 0 10px;
            font-weight: 500;
        }
        .student-class {
            display: inline-block;
            background: #e0e7ff;
            color: #4338ca;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
        }
        .print-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #198754;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
            transition: 0.2s;
        }
        .print-btn:hover { background-color: #146c43; }

        /* Hilangkan tombol cetak saat diprint beneran */
        @media print {
            .print-btn { display: none; }
            body { background: white; }
            .card { box-shadow: none; border: 1px solid #000; }
        }
    </style>
</head>
<body>

    <div class="card">
        <div class="card-header">
            <img src="{{ asset('images/logo_sekolah.png') }}" alt="Logo">
            <h4>SMPN 4 JEMBER MUDADIDAYA</h4>
            <p>Sistem Edukasi Profil Sikap & Karakter</p>
        </div>
        <div class="card-body">
            <div class="qr-box">
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(180)->margin(1)->generate($student->nisn) !!}
            </div>

            <p class="student-name">{{ $student->name }}</p>
            <p class="student-nis">NIS: {{ $student->nisn }}</p>
            <span class="student-class">Kelas {{ $student->class }}</span>

            <button class="print-btn" onclick="window.print()"> Cetak Kartu</button>
        </div>
    </div>

</body>
</html>
