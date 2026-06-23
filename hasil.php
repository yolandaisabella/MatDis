<?php
session_start();

function aman($text) {
    return htmlspecialchars((string) $text, ENT_QUOTES, "UTF-8");
}

function normal($text) {
    return strtolower(trim((string) $text));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jurusanSMA = $_POST["jurusan_sma"];

    $data = [
        "nama" => $_POST["nama"],
        "jurusan_sma" => $jurusanSMA,
        "minat_pcr" => $_POST["minat_pcr"],
        "preferensi_prodi" => $_POST["preferensi_prodi"]
    ];

    if ($jurusanSMA == "IPA") {
        $data["nilai_mtk"] = (int) $_POST["nilai_mtk"];
        $data["nilai_fisika"] = (int) $_POST["nilai_fisika"];
        $data["nilai_inggris"] = (int) $_POST["nilai_inggris"];
        $data["nilai_ekonomi"] = 0;
    } else {
        $data["nilai_ekonomi"] = (int) $_POST["nilai_ekonomi"];
        $data["nilai_inggris"] = (int) $_POST["nilai_inggris_ips"];
        $data["nilai_mtk"] = (int) $_POST["nilai_mtk_ips"];
        $data["nilai_fisika"] = 0;
    }

    $_SESSION["siswa"] = $data;
}

$siswa = $_SESSION["siswa"] ?? null;

if ($siswa == null) {
    header("Location: index.php");
    exit;
}

function semuaProdiPCR() {
    return [
        "Teknik Informatika",
        "Sistem Informasi",
        "Teknologi Rekayasa Komputer",
        "Animasi",
        "Teknik Elektronika",
        "Teknik Listrik",
        "Teknik Mesin",
        "Teknologi Rekayasa Jaringan Telekomunikasi",
        "Teknologi Rekayasa Sistem Elektronika",
        "Teknologi Rekayasa Mekatronika",
        "Kecerdasan Buatan dan Robotika",
        "Akuntansi Perpajakan",
        "Hubungan Masyarakat dan Komunikasi Digital",
        "Bisnis Digital"
    ];
}

function kategoriProdi($prodi) {
    $informasi = [
        "Teknik Informatika",
        "Sistem Informasi",
        "Teknologi Rekayasa Komputer",
        "Animasi"
    ];

    $industri = [
        "Teknik Elektronika",
        "Teknik Listrik",
        "Teknik Mesin",
        "Teknologi Rekayasa Jaringan Telekomunikasi",
        "Teknologi Rekayasa Sistem Elektronika",
        "Teknologi Rekayasa Mekatronika",
        "Kecerdasan Buatan dan Robotika"
    ];

    $bisnisKomunikasi = [
        "Akuntansi Perpajakan",
        "Hubungan Masyarakat dan Komunikasi Digital",
        "Bisnis Digital"
    ];

    if (in_array($prodi, $informasi)) {
        return "Informasi";
    }

    if (in_array($prodi, $industri)) {
        return "Industri";
    }

    if (in_array($prodi, $bisnisKomunikasi)) {
        return "Bisnis dan Komunikasi";
    }

    return "";
}

function prioritasProdi($prodi) {
    $prioritas = [
        "Teknik Informatika" => 1,
        "Sistem Informasi" => 2,
        "Teknologi Rekayasa Komputer" => 3,
        "Animasi" => 4,
        "Teknik Mesin" => 5,
        "Teknik Elektronika" => 6,
        "Teknik Listrik" => 7,
        "Teknologi Rekayasa Jaringan Telekomunikasi" => 8,
        "Teknologi Rekayasa Sistem Elektronika" => 9,
        "Teknologi Rekayasa Mekatronika" => 10,
        "Kecerdasan Buatan dan Robotika" => 11,
        "Akuntansi Perpajakan" => 12,
        "Bisnis Digital" => 13,
        "Hubungan Masyarakat dan Komunikasi Digital" => 14
    ];

    return $prioritas[$prodi] ?? 99;
}

function skorMinat($siswa, $prodi) {
    if (normal($siswa["minat_pcr"]) == normal(kategoriProdi($prodi))) {
        return 100;
    }

    return 0;
}

function skorPreferensi($siswa, $prodi) {
    if ($siswa["preferensi_prodi"] == $prodi) {
        return 100;
    }

    return 0;
}

function standarMinimalProdi($prodi) {
    return 80;
}

/*
    Kombinatorika:
    Karena sistem menampilkan 3 rekomendasi prodi dalam bentuk urutan ranking,
    maka digunakan rumus permutasi P(n,r) = n! / (n-r)!.
*/
function faktorial($n) {
    $hasil = 1;

    for ($i = 1; $i <= $n; $i++) {
        $hasil *= $i;
    }

    return $hasil;
}

function permutasi($n, $r) {
    return faktorial($n) / faktorial($n - $r);
}

function nilaiAkademikProdi($siswa, $prodi) {
    if ($siswa["jurusan_sma"] == "IPA") {
        $mtk = $siswa["nilai_mtk"];
        $fisika = $siswa["nilai_fisika"];
        $inggris = $siswa["nilai_inggris"];

        if ($prodi == "Teknik Informatika") {
            return (0.60 * $mtk) + (0.10 * $fisika) + (0.30 * $inggris);
        }

        if ($prodi == "Sistem Informasi") {
            return (0.50 * $mtk) + (0.10 * $fisika) + (0.40 * $inggris);
        }

        if ($prodi == "Teknologi Rekayasa Komputer") {
            return (0.55 * $mtk) + (0.25 * $fisika) + (0.20 * $inggris);
        }

        if ($prodi == "Animasi") {
            return (0.20 * $mtk) + (0.10 * $fisika) + (0.70 * $inggris);
        }

        if ($prodi == "Teknik Elektronika") {
            return (0.35 * $mtk) + (0.55 * $fisika) + (0.10 * $inggris);
        }

        if ($prodi == "Teknik Listrik") {
            return (0.35 * $mtk) + (0.55 * $fisika) + (0.10 * $inggris);
        }

        if ($prodi == "Teknik Mesin") {
            return (0.35 * $mtk) + (0.60 * $fisika) + (0.05 * $inggris);
        }

        if ($prodi == "Teknologi Rekayasa Jaringan Telekomunikasi") {
            return (0.40 * $mtk) + (0.40 * $fisika) + (0.20 * $inggris);
        }

        if ($prodi == "Teknologi Rekayasa Sistem Elektronika") {
            return (0.35 * $mtk) + (0.55 * $fisika) + (0.10 * $inggris);
        }

        if ($prodi == "Teknologi Rekayasa Mekatronika") {
            return (0.40 * $mtk) + (0.50 * $fisika) + (0.10 * $inggris);
        }

        if ($prodi == "Kecerdasan Buatan dan Robotika") {
            return (0.60 * $mtk) + (0.25 * $fisika) + (0.15 * $inggris);
        }

        if ($prodi == "Akuntansi Perpajakan") {
            return (0.35 * $mtk) + (0.05 * $fisika) + (0.60 * $inggris);
        }

        if ($prodi == "Hubungan Masyarakat dan Komunikasi Digital") {
            return (0.20 * $mtk) + (0.05 * $fisika) + (0.75 * $inggris);
        }

        if ($prodi == "Bisnis Digital") {
            return (0.40 * $mtk) + (0.05 * $fisika) + (0.55 * $inggris);
        }
    }

    if ($siswa["jurusan_sma"] == "IPS") {
        $ekonomi = $siswa["nilai_ekonomi"];
        $inggris = $siswa["nilai_inggris"];
        $mtk = $siswa["nilai_mtk"];

        /*
            Untuk anak IPS:
            Jurusan Informasi dan Industri dibuat sama hitungannya.
            Jadi kalau nilai Matematika tinggi, siswa bisa cocok ke TI atau Mesin.
        */
        if (kategoriProdi($prodi) == "Informasi" || kategoriProdi($prodi) == "Industri") {
            return (0.70 * $mtk) + (0.20 * $inggris) + (0.10 * $ekonomi);
        }

        /*
            Untuk Bisnis dan Komunikasi:
            Ekonomi dan Bahasa Inggris lebih berpengaruh.
        */
        if ($prodi == "Akuntansi Perpajakan") {
            return (0.65 * $ekonomi) + (0.20 * $mtk) + (0.15 * $inggris);
        }

        if ($prodi == "Hubungan Masyarakat dan Komunikasi Digital") {
            return (0.60 * $inggris) + (0.25 * $ekonomi) + (0.15 * $mtk);
        }

        if ($prodi == "Bisnis Digital") {
            return (0.45 * $ekonomi) + (0.30 * $inggris) + (0.25 * $mtk);
        }
    }

    return 0;
}

function hitungSkorAkhir($siswa, $prodi) {
    $minat = skorMinat($siswa, $prodi);
    $nilaiAkademik = nilaiAkademikProdi($siswa, $prodi);
    $preferensi = skorPreferensi($siswa, $prodi);

    return (0.20 * $minat) + (0.70 * $nilaiAkademik) + (0.10 * $preferensi);
}

function buatDataAlternatif($siswa, $prodi) {
    $nilaiAkademik = nilaiAkademikProdi($siswa, $prodi);
    $standarMinimal = standarMinimalProdi($prodi);
    $minat = skorMinat($siswa, $prodi);
    $preferensi = skorPreferensi($siswa, $prodi);
    $skor = hitungSkorAkhir($siswa, $prodi);

    return [
        "prodi" => $prodi,
        "kategori" => kategoriProdi($prodi),
        "nilai_akademik" => $nilaiAkademik,
        "standar" => $standarMinimal,
        "minat" => $minat,
        "preferensi" => $preferensi,
        "skor" => $skor,
        "keterangan" => $nilaiAkademik >= $standarMinimal ? "Memenuhi standar" : "Belum memenuhi standar"
    ];
}

function urutkanAlternatif(&$dataAlternatif) {
    usort($dataAlternatif, function($a, $b) {
        if ($b["skor"] == $a["skor"]) {
            if ($b["nilai_akademik"] == $a["nilai_akademik"]) {
                if ($b["minat"] == $a["minat"]) {
                    if ($b["preferensi"] == $a["preferensi"]) {
                        return prioritasProdi($a["prodi"]) <=> prioritasProdi($b["prodi"]);
                    }

                    return $b["preferensi"] <=> $a["preferensi"];
                }

                return $b["minat"] <=> $a["minat"];
            }

            return $b["nilai_akademik"] <=> $a["nilai_akademik"];
        }

        return $b["skor"] <=> $a["skor"];
    });
}

function ambilSemuaAlternatif($siswa) {
    $daftarProdi = semuaProdiPCR();
    $dataAlternatif = [];

    foreach ($daftarProdi as $prodi) {
        $dataAlternatif[] = buatDataAlternatif($siswa, $prodi);
    }

    urutkanAlternatif($dataAlternatif);
    return $dataAlternatif;
}

function ambilTopTigaProdi($siswa) {
    $semuaAlternatif = ambilSemuaAlternatif($siswa);
    $topTiga = [];

    foreach ($semuaAlternatif as $data) {
        if ($data["nilai_akademik"] >= $data["standar"]) {
            $topTiga[] = $data;
        }
    }

    /*
        Jika tidak ada prodi yang mencapai standar 80,
        sistem tetap menampilkan 3 alternatif terbaik berdasarkan urutan skor/nilai.
    */
    if (count($topTiga) == 0) {
        $topTiga = $semuaAlternatif;
    }

    return array_slice($topTiga, 0, 3);
}

function hasilRekomendasi($siswa) {
    $topTiga = ambilTopTigaProdi($siswa);
    return $topTiga[0]["prodi"];
}

function alasanRekomendasi($siswa) {
    $hasil = hasilRekomendasi($siswa);

    return "Siswa direkomendasikan ke " . $hasil . " karena program studi tersebut memiliki kecocokan nilai akademik terbaik berdasarkan nilai yang diinputkan. Nilai akademik menjadi penentu utama, sedangkan minat dan preferensi digunakan sebagai pendukung.";
}

function buatAnalisis($siswa) {
    $hasil = hasilRekomendasi($siswa);

    if ($siswa["jurusan_sma"] == "IPA") {
        return "Berdasarkan data yang diinputkan, siswa berasal dari jurusan IPA dengan nilai Matematika " . $siswa["nilai_mtk"] . ", Fisika " . $siswa["nilai_fisika"] . ", dan Bahasa Inggris " . $siswa["nilai_inggris"] . ". Minat PCR yang dipilih adalah " . $siswa["minat_pcr"] . " dengan preferensi prodi " . $siswa["preferensi_prodi"] . ". Sistem membandingkan seluruh program studi PCR berdasarkan nilai akademik prodi dengan standar minimal 80. Rumus skor menggunakan bobot nilai akademik 70%, minat 20%, dan preferensi 10%. Pada bagian kombinatorika, sistem menghitung kemungkinan susunan 3 rekomendasi prodi dari seluruh daftar prodi PCR menggunakan rumus permutasi. Hasil akhir menunjukkan bahwa " . $hasil . " menjadi rekomendasi utama.";
    }

    return "Berdasarkan data yang diinputkan, siswa berasal dari jurusan IPS dengan nilai Ekonomi " . $siswa["nilai_ekonomi"] . ", Bahasa Inggris " . $siswa["nilai_inggris"] . ", dan Matematika " . $siswa["nilai_mtk"] . ". Minat PCR yang dipilih adalah " . $siswa["minat_pcr"] . " dengan preferensi prodi " . $siswa["preferensi_prodi"] . ". Untuk jurusan Informasi dan Industri, sistem menggunakan perhitungan yang sama yaitu Matematika 70%, Bahasa Inggris 20%, dan Ekonomi 10%. Sistem membandingkan seluruh program studi PCR berdasarkan nilai akademik prodi dengan standar minimal 80. Rumus skor menggunakan bobot nilai akademik 70%, minat 20%, dan preferensi 10%. Pada bagian kombinatorika, sistem menghitung kemungkinan susunan 3 rekomendasi prodi dari seluruh daftar prodi PCR menggunakan rumus permutasi. Hasil akhir menunjukkan bahwa " . $hasil . " menjadi rekomendasi utama.";
}

function prodiKategori($kategori) {
    $hasil = [];

    foreach (semuaProdiPCR() as $prodi) {
        if (kategoriProdi($prodi) == $kategori) {
            $hasil[] = $prodi;
        }
    }

    return $hasil;
}

function buatDataUji() {
    return [
        [
            "nama" => "Data Uji 1",
            "jurusan_sma" => "IPA",
            "nilai_mtk" => 90,
            "nilai_fisika" => 76,
            "nilai_inggris" => 87,
            "nilai_ekonomi" => 0,
            "minat_pcr" => "Informasi",
            "preferensi_prodi" => "Teknik Informatika"
        ],
        [
            "nama" => "Data Uji 2",
            "jurusan_sma" => "IPS",
            "nilai_mtk" => 88,
            "nilai_fisika" => 0,
            "nilai_inggris" => 80,
            "nilai_ekonomi" => 72,
            "minat_pcr" => "Industri",
            "preferensi_prodi" => "Teknik Mesin"
        ],
        [
            "nama" => "Data Uji 3",
            "jurusan_sma" => "IPS",
            "nilai_mtk" => 75,
            "nilai_fisika" => 0,
            "nilai_inggris" => 92,
            "nilai_ekonomi" => 85,
            "minat_pcr" => "Bisnis dan Komunikasi",
            "preferensi_prodi" => "Hubungan Masyarakat dan Komunikasi Digital"
        ]
    ];
}

$semuaAlternatif = ambilSemuaAlternatif($siswa);
$topTiga = ambilTopTigaProdi($siswa);
$hasilUtama = hasilRekomendasi($siswa);
$dataUji = buatDataUji();

/* Variabel untuk himpunan dan kombinatorika */
$jumlahProdi = count(semuaProdiPCR());
$jumlahRekomendasi = 3;
$jumlahKemungkinanSusunan = permutasi($jumlahProdi, $jumlahRekomendasi);
$namaSiswa = $siswa["nama"];
$namaTopTiga = array_column($topTiga, "prodi");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Rekomendasi</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #eef3f8;
            color: #222;
        }

        header {
            background: #1f4e79;
            color: white;
            padding: 25px;
            text-align: center;
        }

        header h1 {
            margin: 0;
        }

        header p {
            margin-top: 8px;
        }

        .container {
            width: 92%;
            max-width: 1200px;
            margin: 25px auto;
        }

        .card {
            background: white;
            padding: 22px;
            margin-bottom: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        h2 {
            margin-top: 0;
            color: #1f4e79;
            border-left: 5px solid #1f4e79;
            padding-left: 10px;
        }

        h3 {
            color: #1f4e79;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: white;
        }

        th, td {
            border: 1px solid #777;
            padding: 10px;
            text-align: center;
            vertical-align: top;
        }

        th {
            background: #d9eaf7;
        }

        .hasil {
            font-weight: bold;
            color: #1f4e79;
        }

        .info {
            background: #eef6ff;
            border-left: 5px solid #1f4e79;
            padding: 12px;
            margin-bottom: 12px;
            line-height: 1.6;
        }

        pre {
            background: #f9f9f9;
            border: 1px solid #aaa;
            padding: 18px;
            overflow-x: auto;
            font-size: 15px;
            line-height: 1.4;
            white-space: pre-wrap;
        }

        .rumus {
            background: #f9f9f9;
            border: 1px solid #aaa;
            padding: 15px;
            line-height: 1.7;
            font-size: 16px;
        }


        .formula-display {
            text-align: left;
            margin: 10px 0 18px;
            padding: 0;
            background: transparent;
            border: none;
        }

        .formula-main {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: "Times New Roman", Georgia, serif;
            color: #111;
            vertical-align: middle;
        }

        .formula-symbol {
            font-size: 24px;
            font-style: italic;
            line-height: 1;
            white-space: nowrap;
        }

        .formula-symbol sub {
            font-size: 13px;
            vertical-align: sub;
            margin-left: 1px;
        }

        .formula-equals {
            font-size: 24px;
            line-height: 1;
            font-family: "Times New Roman", Georgia, serif;
        }

        .formula-fraction {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            min-width: 95px;
            vertical-align: middle;
        }

        .formula-top,
        .formula-bottom {
            font-size: 22px;
            font-style: italic;
            line-height: 1.05;
            white-space: nowrap;
        }

        .formula-bar {
            width: 100%;
            border-top: 2px solid #111;
            margin: 3px 0 4px;
        }

        .formula-step {
            font-family: Arial, sans-serif;
            font-size: 16px;
            text-align: left;
            margin: 12px 0;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .formula-step .formula-main {
            gap: 6px;
        }

        .formula-step .formula-symbol,
        .formula-step .formula-equals {
            font-size: 20px;
        }

        .formula-step .formula-symbol sub {
            font-size: 11px;
        }

        .formula-step .formula-fraction {
            min-width: 105px;
        }

        .formula-step .formula-top,
        .formula-step .formula-bottom {
            font-size: 18px;
        }

        .formula-step .formula-bar {
            border-top-width: 2px;
            margin: 2px 0 3px;
        }

        .list-box {
            background: #f9f9f9;
            border: 1px solid #aaa;
            padding: 15px;
            line-height: 1.7;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }



        .detail-toggle-area {
            margin-top: 18px;
            margin-bottom: 18px;
            text-align: left;
        }

        .detail-btn {
            padding: 11px 18px;
            background: #1f4e79;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            font-size: 15px;
        }

        .detail-btn:hover {
            background: #163a59;
        }

        .detail-area {
            display: none;
            margin-top: 18px;
        }

        .back-area {
            text-align: right;
            margin-top: 25px;
            margin-bottom: 35px;
        }

        .back {
            display: inline-block;
            padding: 12px 20px;
            background: #1f4e79;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }

        .back:hover {
            background: #163a59;
        }



        /* Pohon keputusan dinamis sesuai hasil input dan rekomendasi */
        .tree-frame {
            background: #fff;
            margin-top: 15px;
            overflow-x: auto;
            padding: 15px 20px 35px;
            border: none;
        }

        .tree-svg-wrap {
            width: 100%;
            max-width: 1050px;
            margin: 0 auto;
        }

        .tree-svg {
            display: block;
            width: 100%;
            height: auto;
            background: #fff;
            overflow: visible;
        }

        .flow-box {
            fill: #fff;
            stroke: #111;
            stroke-width: 1.6;
        }

        .flow-line {
            stroke: #111;
            stroke-width: 1.6;
            fill: none;
        }

        .flow-text {
            font-family: Arial, sans-serif;
            font-size: 14px;
            fill: #111;
            font-weight: 400;
        }

        .flow-small {
            font-family: Arial, sans-serif;
            font-size: 13px;
            fill: #111;
            font-weight: 700;
        }

        .active-box {
            fill: #e8f3ff;
            stroke: #1f4e79;
            stroke-width: 2.4;
        }

        .active-line {
            stroke: #1f4e79;
            stroke-width: 2.6;
            fill: none;
        }

        .active-text {
            fill: #1f4e79;
            font-weight: 700;
        }

        @media (max-width: 800px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }

            .tree-svg-wrap {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<header>
    <h1>Hasil Rekomendasi Jurusan PCR</h1>
    <p>Output Sistem Rekomendasi Sesuai Instruksi Tugas</p>
</header>

<div class="container">

    <!-- 1. DATA INPUT SISWA -->
    <div class="card">
        <h2>1. Data Input Siswa</h2>

        <div class="info">
            Bagian ini menampilkan data minat mahasiswa, nilai mata kuliah tertentu, preferensi bidang, dan kriteria rekomendasi yang diinputkan ke sistem.
        </div>

        <table>
            <tr>
                <th>Nama</th>
                <th>Jurusan SMA</th>
                <th>Matematika</th>
                <th>Fisika</th>
                <th>Bahasa Inggris</th>
                <th>Ekonomi</th>
                <th>Minat PCR</th>
                <th>Preferensi Prodi</th>
            </tr>
            <tr>
                <td><?= aman($siswa["nama"]); ?></td>
                <td><?= aman($siswa["jurusan_sma"]); ?></td>
                <td><?= aman($siswa["nilai_mtk"]); ?></td>
                <td><?= aman($siswa["nilai_fisika"]); ?></td>
                <td><?= aman($siswa["nilai_inggris"]); ?></td>
                <td><?= aman($siswa["nilai_ekonomi"]); ?></td>
                <td><?= aman($siswa["minat_pcr"]); ?></td>
                <td><?= aman($siswa["preferensi_prodi"]); ?></td>
            </tr>
        </table>
    </div>

    <!-- 2. HIMPUNAN DATA -->
    <div class="card">
        <h2>2. Himpunan Data</h2>

        <div class="info">
            Himpunan data digunakan untuk mendefinisikan objek yang dipakai dalam sistem rekomendasi, yaitu siswa, jurusan SMA, minat, program studi, kategori, nilai, standar minimal, bobot, dan hasil rekomendasi.
        </div>

        <div class="grid-2">
            <div class="list-box">
                <p><b>Himpunan Siswa</b></p>
                <p>S = {<?= aman($namaSiswa); ?>}</p>

                <p><b>Himpunan Jurusan SMA</b></p>
                <p>J = {IPA, IPS}</p>

                <p><b>Himpunan Minat PCR</b></p>
                <p>M = {Informasi, Industri, Bisnis dan Komunikasi}</p>

                <p><b>Himpunan Nilai Mata Pelajaran</b></p>
                <?php if ($siswa["jurusan_sma"] == "IPA"): ?>
                    <p>N = {Matematika, Fisika, Bahasa Inggris}</p>
                <?php else: ?>
                    <p>N = {Ekonomi, Bahasa Inggris, Matematika}</p>
                <?php endif; ?>

                <p><b>Himpunan Standar Minimal</b></p>
                <p>SM = {80}</p>

                <p><b>Himpunan Bobot Penilaian</b></p>
                <p>B = {Minat 20%, Nilai Akademik 70%, Preferensi 10%}</p>
            </div>

            <div class="list-box">
                <p><b>Himpunan Program Studi PCR</b></p>
                <p>P = {</p>
                <ol>
                    <?php foreach (semuaProdiPCR() as $prodi): ?>
                        <li><?= aman($prodi); ?></li>
                    <?php endforeach; ?>
                </ol>
                <p>}</p>

                <p><b>Himpunan Program Studi Rekomendasi</b></p>
                <p>Pr = {<?= aman(implode(", ", $namaTopTiga)); ?>}</p>

            </div>
        </div>
    </div>

    <!-- 3. RELASI -->
    <div class="card">
        <h2>3. Tabel Relasi</h2>

        <div class="info">
            Relasi menunjukkan hubungan siswa dengan program studi rekomendasi. Angka 1 berarti program studi menjadi rekomendasi utama, sedangkan tanda - berarti bukan rekomendasi utama.
        </div>

        <p><b>Relasi:</b> R = {(<?= aman($siswa["nama"]); ?>, <?= aman($hasilUtama); ?>)}</p>

        <table>
            <tr>
                <th>Nama Siswa</th>
                <?php foreach ($topTiga as $data): ?>
                    <th><?= aman($data["prodi"]); ?></th>
                <?php endforeach; ?>
            </tr>

            <tr>
                <td><?= aman($siswa["nama"]); ?></td>
                <?php foreach ($topTiga as $data): ?>
                    <td><?= $data["prodi"] == $hasilUtama ? "1" : "-"; ?></td>
                <?php endforeach; ?>
            </tr>
        </table>
    </div>

    <!-- 4. SKOR -->
    <div class="card">
        <h2>4. Hasil Skor Setiap Alternatif</h2>

        <div class="info">
            Bagian ini menampilkan 3 rekomendasi tertinggi terlebih dahulu. Detail skor seluruh alternatif program studi dapat dilihat dengan menekan tombol <b>Lihat Detail</b>.
        </div>

        <h3>Top 3 Rekomendasi</h3>

        <table>
            <tr>
                <th>Peringkat</th>
                <th>Program Studi PCR</th>
                <th>Kategori</th>
                <th>Nilai Akademik</th>
                <th>Skor Akhir</th>
                <th>Keterangan</th>
            </tr>

            <?php $rank = 1; ?>
            <?php foreach ($topTiga as $data): ?>
                <tr>
                    <td><?= $rank; ?></td>
                    <td><?= aman($data["prodi"]); ?></td>
                    <td><?= aman($data["kategori"]); ?></td>
                    <td><?= number_format($data["nilai_akademik"], 1); ?></td>
                    <td><?= number_format($data["skor"], 1); ?></td>
                    <td>
                        <?php if ($rank == 1): ?>
                            <span class="hasil">Rekomendasi utama</span>
                        <?php else: ?>
                            <?= aman($data["keterangan"]); ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php $rank++; ?>
            <?php endforeach; ?>
        </table>

        <div class="detail-toggle-area">
            <button type="button" class="detail-btn" id="btnDetail" onclick="toggleDetailAlternatif()">
                Lihat Detail
            </button>
        </div>

        <div id="detailAlternatif" class="detail-area">
            <h3>Skor Seluruh Alternatif Program Studi</h3>

            <table>
                <tr>
                    <th>No</th>
                    <th>Program Studi PCR</th>
                    <th>Kategori</th>
                    <th>Nilai Akademik</th>
                    <th>Standar</th>
                    <th>Minat</th>
                    <th>Preferensi</th>
                    <th>Skor Akhir</th>
                    <th>Keterangan</th>
                </tr>

                <?php $no = 1; ?>
                <?php foreach ($semuaAlternatif as $data): ?>
                    <tr>
                        <td><?= $no; ?></td>
                        <td><?= aman($data["prodi"]); ?></td>
                        <td><?= aman($data["kategori"]); ?></td>
                        <td><?= number_format($data["nilai_akademik"], 1); ?></td>
                        <td><?= number_format($data["standar"], 1); ?></td>
                        <td><?= number_format($data["minat"], 0); ?></td>
                        <td><?= number_format($data["preferensi"], 0); ?></td>
                        <td><?= number_format($data["skor"], 1); ?></td>
                        <td><?= aman($data["keterangan"]); ?></td>
                    </tr>
                    <?php $no++; ?>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

    <!-- 5. KOMBINATORIKA -->
    <div class="card">
        <h2>5. Kombinatorika Rekomendasi Prodi</h2>

        <div class="info">
            Kombinatorika digunakan untuk menghitung banyaknya kemungkinan susunan rekomendasi program studi. Karena hasil rekomendasi ditampilkan berdasarkan urutan peringkat, maka digunakan rumus permutasi.
        </div>

        <div class="rumus">
            <p><b>Rumus Permutasi:</b></p>

            <div class="formula-display">
                <div class="formula-main">
                    <span class="formula-symbol">P<sub>r</sub></span>
                    <span class="formula-equals">=</span>
                    <span class="formula-fraction">
                        <span class="formula-top">n!</span>
                        <span class="formula-bar"></span>
                        <span class="formula-bottom">(n − r)!</span>
                    </span>
                </div>
            </div>

            <p><b>Perhitungan:</b></p>

            <div class="formula-step">
                <span>P(<?= $jumlahProdi; ?>,<?= $jumlahRekomendasi; ?>)</span>
                <span>=</span>
                <span class="formula-main">
                    <span class="formula-fraction">
                        <span class="formula-top"><?= $jumlahProdi; ?>!</span>
                        <span class="formula-bar"></span>
                        <span class="formula-bottom">(<?= $jumlahProdi; ?> − <?= $jumlahRekomendasi; ?>)!</span>
                    </span>
                </span>
            </div>

            <div class="formula-step">
                <span>P(<?= $jumlahProdi; ?>,<?= $jumlahRekomendasi; ?>)</span>
                <span>=</span>
                <span><?= $jumlahProdi; ?> × <?= $jumlahProdi - 1; ?> × <?= $jumlahProdi - 2; ?></span>
                <span>=</span>
                <span><?= number_format($jumlahKemungkinanSusunan, 0, ',', '.'); ?></span>
            </div>

            <p>
                Jadi, terdapat <b><?= number_format($jumlahKemungkinanSusunan, 0, ',', '.'); ?></b>
                kemungkinan susunan 3 rekomendasi prodi dari <?= $jumlahProdi; ?> program studi PCR.
            </p>
        </div>
    </div>

    <!-- 6. POHON KEPUTUSAN -->
    <div class="card">
        <h2>6. Pohon Keputusan</h2>

        <?php
            $rekom1 = $topTiga[0]["prodi"] ?? "-";
            $rekom2 = $topTiga[1]["prodi"] ?? "-";
            $rekom3 = $topTiga[2]["prodi"] ?? "-";

            $utama = $topTiga[0] ?? null;

            $memenuhiStandar = $utama ? ($utama["nilai_akademik"] >= $utama["standar"]) : false;
            $minatSesuai = $utama ? ($utama["minat"] == 100) : false;
            $preferensiSesuai = $utama ? ($utama["preferensi"] == 100) : false;

            $jalurUtama = "";
            if ($memenuhiStandar) {
                if ($minatSesuai) {
                    if ($preferensiSesuai) {
                        $jalurUtama = "kandidat_utama";
                    } else {
                        $jalurUtama = "kandidat_rekomendasi";
                    }
                } else {
                    $jalurUtama = "kandidat_alternatif";
                }
            } else {
                $jalurUtama = "alternatif_pembimbing";
            }

            $clsBox = function($aktif) {
                return $aktif ? "flow-box active-box" : "flow-box";
            };

            $clsLine = function($aktif) {
                return $aktif ? "flow-line active-line" : "flow-line";
            };

            $clsText = function($aktif) {
                return $aktif ? "flow-text active-text" : "flow-text";
            };

            $clsSmall = function($aktif) {
                return $aktif ? "flow-small active-text" : "flow-small";
            };
        ?>

        <div class="info">
            Pohon keputusan berikut menyesuaikan data input siswa dan hasil rekomendasi utama. Jalur yang aktif ditandai warna biru.
        </div>

        <div class="tree-frame">
            <div class="tree-svg-wrap">
                <svg class="tree-svg" viewBox="-80 0 1560 1960" xmlns="http://www.w3.org/2000/svg" aria-label="Pohon keputusan dinamis sistem rekomendasi program studi PCR">
                    <defs>
                        <marker id="flowArrow" markerWidth="10" markerHeight="10" refX="5" refY="5" orient="auto" markerUnits="strokeWidth">
                            <path d="M 0 0 L 10 5 L 0 10 z" fill="#111" />
                        </marker>
                    </defs>

                    <!-- Data input dari user -->
                    <rect x="455" y="20" width="490" height="95" class="<?= $clsBox(true); ?>"/>
                    <text x="700" y="47" text-anchor="middle" class="<?= $clsText(true); ?>">Input Data Mahasiswa</text>
                    <text x="700" y="69" text-anchor="middle" class="<?= $clsText(true); ?>">Jurusan SMA: <?= aman($siswa["jurusan_sma"]); ?></text>
                    <text x="700" y="91" text-anchor="middle" class="<?= $clsText(true); ?>">Minat PCR: <?= aman($siswa["minat_pcr"]); ?></text>
                    <text x="700" y="113" text-anchor="middle" class="<?= $clsText(true); ?>">Preferensi: <?= aman($siswa["preferensi_prodi"]); ?></text>

                    <line x1="700" y1="115" x2="700" y2="150" class="<?= $clsLine(true); ?>" marker-end="url(#flowArrow)"/>

                    <rect x="430" y="160" width="540" height="65" class="<?= $clsBox(true); ?>"/>
                    <text x="700" y="199" text-anchor="middle" class="<?= $clsText(true); ?>">Bentuk Himpunan Data Siswa dan Program Studi</text>

                    <line x1="700" y1="225" x2="700" y2="260" class="<?= $clsLine(true); ?>" marker-end="url(#flowArrow)"/>

                    <rect x="430" y="270" width="540" height="65" class="<?= $clsBox(true); ?>"/>
                    <text x="700" y="309" text-anchor="middle" class="<?= $clsText(true); ?>">Definisi Relasi Siswa dengan Program Studi</text>

                    <line x1="700" y1="335" x2="700" y2="370" class="<?= $clsLine(true); ?>" marker-end="url(#flowArrow)"/>

                    <rect x="430" y="380" width="540" height="65" class="<?= $clsBox(true); ?>"/>
                    <text x="700" y="419" text-anchor="middle" class="<?= $clsText(true); ?>">Hitung Nilai Akademik setiap Alternatif Prodi</text>

                    <line x1="700" y1="445" x2="700" y2="480" class="<?= $clsLine(true); ?>" marker-end="url(#flowArrow)"/>

                    <rect x="430" y="490" width="540" height="75" class="<?= $clsBox(true); ?>"/>
                    <text x="700" y="522" text-anchor="middle" class="<?= $clsText(true); ?>">Nilai Akademik Memenuhi Standar Minimal?</text>
                    <text x="700" y="544" text-anchor="middle" class="<?= $clsText(true); ?>">Nilai <?= $utama ? number_format($utama["nilai_akademik"], 1) : "-"; ?> dari standar <?= $utama ? number_format($utama["standar"], 0) : "80"; ?></text>

                    <!-- Cabang standar -->
                    <text x="300" y="525" text-anchor="middle" class="<?= $clsSmall($memenuhiStandar); ?>">Ya (&gt;= 80)</text>
                    <line x1="430" y1="527" x2="245" y2="527" class="<?= $clsLine($memenuhiStandar); ?>"/>
                    <line x1="245" y1="527" x2="245" y2="620" class="<?= $clsLine($memenuhiStandar); ?>" marker-end="url(#flowArrow)"/>

                    <text x="1110" y="525" text-anchor="middle" class="<?= $clsSmall(!$memenuhiStandar); ?>">Tidak (&lt;80)</text>
                    <line x1="970" y1="527" x2="1160" y2="527" class="<?= $clsLine(!$memenuhiStandar); ?>"/>
                    <line x1="1160" y1="527" x2="1160" y2="620" class="<?= $clsLine(!$memenuhiStandar); ?>" marker-end="url(#flowArrow)"/>

                    <!-- Kiri -->
                    <rect x="110" y="630" width="360" height="65" class="<?= $clsBox($memenuhiStandar); ?>"/>
                    <text x="290" y="669" text-anchor="middle" class="<?= $clsText($memenuhiStandar); ?>">Minat sesuai dengan Program Studi?</text>

                    <text x="110" y="735" text-anchor="middle" class="<?= $clsSmall($memenuhiStandar && $minatSesuai); ?>">Ya</text>
                    <line x1="290" y1="695" x2="160" y2="780" class="<?= $clsLine($memenuhiStandar && $minatSesuai); ?>" marker-end="url(#flowArrow)"/>

                    <text x="470" y="735" text-anchor="middle" class="<?= $clsSmall($memenuhiStandar && !$minatSesuai); ?>">Tidak</text>
                    <line x1="290" y1="695" x2="505" y2="780" class="<?= $clsLine($memenuhiStandar && !$minatSesuai); ?>" marker-end="url(#flowArrow)"/>

                    <rect x="30" y="790" width="260" height="65" class="<?= $clsBox($memenuhiStandar && $minatSesuai); ?>"/>
                    <text x="160" y="818" text-anchor="middle" class="<?= $clsText($memenuhiStandar && $minatSesuai); ?>">Program Studi Sesuai</text>
                    <text x="160" y="840" text-anchor="middle" class="<?= $clsText($memenuhiStandar && $minatSesuai); ?>">Preferensi?</text>

                    <rect x="370" y="790" width="270" height="65" class="<?= $clsBox($memenuhiStandar && !$minatSesuai); ?>"/>
                    <text x="505" y="818" text-anchor="middle" class="<?= $clsText($memenuhiStandar && !$minatSesuai); ?>">Tetap Relevan Sebagai</text>
                    <text x="505" y="840" text-anchor="middle" class="<?= $clsText($memenuhiStandar && !$minatSesuai); ?>">Alternatif?</text>

                    <text x="95" y="915" text-anchor="middle" class="<?= $clsSmall($jalurUtama == 'kandidat_utama'); ?>">Ya</text>
                    <line x1="160" y1="855" x2="80" y2="940" class="<?= $clsLine($jalurUtama == 'kandidat_utama'); ?>" marker-end="url(#flowArrow)"/>

                    <text x="255" y="900" text-anchor="middle" class="<?= $clsSmall($jalurUtama == 'kandidat_rekomendasi'); ?>">Tidak</text>
                    <line x1="160" y1="855" x2="300" y2="940" class="<?= $clsLine($jalurUtama == 'kandidat_rekomendasi'); ?>" marker-end="url(#flowArrow)"/>

                    <text x="455" y="915" text-anchor="middle" class="<?= $clsSmall($jalurUtama == 'kandidat_alternatif'); ?>">Ya</text>
                    <line x1="505" y1="855" x2="545" y2="940" class="<?= $clsLine($jalurUtama == 'kandidat_alternatif'); ?>" marker-end="url(#flowArrow)"/>

                    <text x="680" y="900" text-anchor="middle" class="<?= $clsSmall(false); ?>">Tidak</text>
                    <line x1="505" y1="855" x2="755" y2="940" class="<?= $clsLine(false); ?>" marker-end="url(#flowArrow)"/>

                    <rect x="0" y="950" width="195" height="70" class="<?= $clsBox($jalurUtama == 'kandidat_utama'); ?>"/>
                    <text x="98" y="979" text-anchor="middle" class="<?= $clsText($jalurUtama == 'kandidat_utama'); ?>">Kandidat</text>
                    <text x="98" y="1001" text-anchor="middle" class="<?= $clsText($jalurUtama == 'kandidat_utama'); ?>">Rekomendasi Utama</text>

                    <rect x="225" y="950" width="195" height="70" class="<?= $clsBox($jalurUtama == 'kandidat_rekomendasi'); ?>"/>
                    <text x="323" y="979" text-anchor="middle" class="<?= $clsText($jalurUtama == 'kandidat_rekomendasi'); ?>">Kandidat</text>
                    <text x="323" y="1001" text-anchor="middle" class="<?= $clsText($jalurUtama == 'kandidat_rekomendasi'); ?>">Rekomendasi</text>

                    <rect x="455" y="950" width="195" height="70" class="<?= $clsBox($jalurUtama == 'kandidat_alternatif'); ?>"/>
                    <text x="553" y="979" text-anchor="middle" class="<?= $clsText($jalurUtama == 'kandidat_alternatif'); ?>">Kandidat</text>
                    <text x="553" y="1001" text-anchor="middle" class="<?= $clsText($jalurUtama == 'kandidat_alternatif'); ?>">Alternatif</text>

                    <rect x="690" y="950" width="205" height="70" class="<?= $clsBox(false); ?>"/>
                    <text x="793" y="979" text-anchor="middle" class="<?= $clsText(false); ?>">Tidak</text>
                    <text x="793" y="1001" text-anchor="middle" class="<?= $clsText(false); ?>">Direkomendasikan</text>

                    <!-- Kanan -->
                    <rect x="980" y="630" width="360" height="65" class="<?= $clsBox(!$memenuhiStandar); ?>"/>
                    <text x="1160" y="669" text-anchor="middle" class="<?= $clsText(!$memenuhiStandar); ?>">Tetap Menjadi Alternatif Pembimbing?</text>

                    <text x="1030" y="825" text-anchor="middle" class="<?= $clsSmall($jalurUtama == 'alternatif_pembimbing'); ?>">Ya</text>
                    <line x1="1160" y1="695" x2="1030" y2="940" class="<?= $clsLine($jalurUtama == 'alternatif_pembimbing'); ?>" marker-end="url(#flowArrow)"/>

                    <text x="1305" y="825" text-anchor="middle" class="<?= $clsSmall(false); ?>">Tidak</text>
                    <line x1="1160" y1="695" x2="1305" y2="940" class="<?= $clsLine(false); ?>" marker-end="url(#flowArrow)"/>

                    <rect x="930" y="950" width="220" height="70" class="<?= $clsBox($jalurUtama == 'alternatif_pembimbing'); ?>"/>
                    <text x="1040" y="979" text-anchor="middle" class="<?= $clsText($jalurUtama == 'alternatif_pembimbing'); ?>">Alternatif</text>
                    <text x="1040" y="1001" text-anchor="middle" class="<?= $clsText($jalurUtama == 'alternatif_pembimbing'); ?>">Pembimbing</text>

                    <rect x="1200" y="950" width="200" height="70" class="<?= $clsBox(false); ?>"/>
                    <text x="1300" y="979" text-anchor="middle" class="<?= $clsText(false); ?>">Tidak</text>
                    <text x="1300" y="1001" text-anchor="middle" class="<?= $clsText(false); ?>">Direkomendasikan</text>

                    <!-- Gabung -->
                    <line x1="98" y1="1020" x2="98" y2="1085" class="flow-line"/>
                    <line x1="323" y1="1020" x2="323" y2="1085" class="flow-line"/>
                    <line x1="553" y1="1020" x2="553" y2="1085" class="flow-line"/>
                    <line x1="793" y1="1020" x2="793" y2="1085" class="flow-line"/>
                    <line x1="1040" y1="1020" x2="1040" y2="1085" class="flow-line"/>
                    <line x1="1300" y1="1020" x2="1300" y2="1085" class="flow-line"/>
                    <line x1="98" y1="1085" x2="1300" y2="1085" class="flow-line"/>
                    <line x1="700" y1="1085" x2="700" y2="1130" class="<?= $clsLine(true); ?>" marker-end="url(#flowArrow)"/>

                    <!-- Akhir -->
                    <rect x="400" y="1140" width="600" height="75" class="<?= $clsBox(true); ?>"/>
                    <text x="700" y="1173" text-anchor="middle" class="<?= $clsText(true); ?>">Hitung Skor Akhir</text>
                    <text x="700" y="1195" text-anchor="middle" class="<?= $clsText(true); ?>">Minat 20% + Nilai Akademik 70% + Preferensi 10%</text>

                    <line x1="700" y1="1215" x2="700" y2="1250" class="<?= $clsLine(true); ?>" marker-end="url(#flowArrow)"/>

                    <rect x="430" y="1260" width="540" height="75" class="<?= $clsBox(true); ?>"/>
                    <text x="700" y="1293" text-anchor="middle" class="<?= $clsText(true); ?>">Hitung Permutasi</text>
                    <text x="700" y="1315" text-anchor="middle" class="<?= $clsText(true); ?>">P (<?= $jumlahProdi; ?>,<?= $jumlahRekomendasi; ?>) = <?= number_format($jumlahKemungkinanSusunan, 0, ',', '.'); ?> kemungkinan susunan rekomendasi</text>

                    <line x1="700" y1="1335" x2="700" y2="1370" class="<?= $clsLine(true); ?>" marker-end="url(#flowArrow)"/>

                    <rect x="430" y="1380" width="540" height="150" class="<?= $clsBox(true); ?>"/>
                    <text x="700" y="1415" text-anchor="middle" class="<?= $clsText(true); ?>">Urutkan Rekomendasi</text>
                    <text x="700" y="1443" text-anchor="middle" class="<?= $clsText(true); ?>">1. Skor Akhir Tertinggi</text>
                    <text x="700" y="1469" text-anchor="middle" class="<?= $clsText(true); ?>">2. Nilai Akademik</text>
                    <text x="700" y="1495" text-anchor="middle" class="<?= $clsText(true); ?>">3. Kecocokan Minat</text>
                    <text x="700" y="1521" text-anchor="middle" class="<?= $clsText(true); ?>">4. Preferensi Prodi</text>

                    <line x1="700" y1="1530" x2="700" y2="1565" class="<?= $clsLine(true); ?>" marker-end="url(#flowArrow)"/>

                    <rect x="525" y="1575" width="350" height="60" class="<?= $clsBox(true); ?>"/>
                    <text x="700" y="1611" text-anchor="middle" class="<?= $clsText(true); ?>">Ambil 3 Rekomendasi Teratas</text>

                    <line x1="700" y1="1635" x2="700" y2="1663" class="flow-line"/>
                    <line x1="390" y1="1663" x2="1010" y2="1663" class="flow-line"/>
                    <line x1="390" y1="1663" x2="390" y2="1690" class="flow-line" marker-end="url(#flowArrow)"/>
                    <line x1="700" y1="1663" x2="700" y2="1690" class="flow-line" marker-end="url(#flowArrow)"/>
                    <line x1="1010" y1="1663" x2="1010" y2="1690" class="flow-line" marker-end="url(#flowArrow)"/>

                    <rect x="250" y="1700" width="280" height="65" class="<?= $clsBox($rekom1 == $hasilUtama); ?>"/>
                    <text x="390" y="1727" text-anchor="middle" class="<?= $clsText($rekom1 == $hasilUtama); ?>">Rekomendasi 1</text>
                    <text x="390" y="1749" text-anchor="middle" class="<?= $clsText($rekom1 == $hasilUtama); ?>"><?= aman($rekom1); ?></text>

                    <rect x="560" y="1700" width="280" height="65" class="flow-box"/>
                    <text x="700" y="1727" text-anchor="middle" class="flow-text">Rekomendasi 2</text>
                    <text x="700" y="1749" text-anchor="middle" class="flow-text"><?= aman($rekom2); ?></text>

                    <rect x="870" y="1700" width="280" height="65" class="flow-box"/>
                    <text x="1010" y="1727" text-anchor="middle" class="flow-text">Rekomendasi 3</text>
                    <text x="1010" y="1749" text-anchor="middle" class="flow-text"><?= aman($rekom3); ?></text>

                    <line x1="390" y1="1765" x2="390" y2="1800" class="flow-line"/>
                    <line x1="700" y1="1765" x2="700" y2="1800" class="flow-line"/>
                    <line x1="1010" y1="1765" x2="1010" y2="1800" class="flow-line"/>
                    <line x1="390" y1="1800" x2="1010" y2="1800" class="flow-line"/>
                    <line x1="700" y1="1800" x2="700" y2="1830" class="<?= $clsLine(true); ?>" marker-end="url(#flowArrow)"/>

                    <rect x="405" y="1840" width="590" height="90" class="<?= $clsBox(true); ?>"/>
                    <text x="700" y="1872" text-anchor="middle" class="<?= $clsText(true); ?>">Rekomendasi Utama:</text>
                    <text x="700" y="1896" text-anchor="middle" class="<?= $clsText(true); ?>"><?= aman($hasilUtama); ?></text>
                    <text x="700" y="1918" text-anchor="middle" class="<?= $clsText(true); ?>">Skor Akhir: <?= $utama ? number_format($utama["skor"], 1) : "-"; ?></text>
                </svg>
            </div>
        </div>
    </div>

    <!-- 7. ALASAN REKOMENDASI -->
    <div class="card">
        <h2>7. Alasan Rekomendasi</h2>

        <div class="info">
            <p><?= aman(buatAnalisis($siswa)); ?></p>
            <p><b>Alasan rekomendasi:</b> <?= aman(alasanRekomendasi($siswa)); ?></p>
        </div>
    </div>

    <div class="back-area">
        <a href="index.php?reset=1" class="back">← Back</a>
    </div>

</div>


<script>
    function toggleDetailAlternatif() {
        const detail = document.getElementById("detailAlternatif");
        const button = document.getElementById("btnDetail");

        if (detail.style.display === "none" || detail.style.display === "") {
            detail.style.display = "block";
            button.textContent = "Sembunyikan Detail";
        } else {
            detail.style.display = "none";
            button.textContent = "Lihat Detail";
        }
    }
</script>

</body>
</html>