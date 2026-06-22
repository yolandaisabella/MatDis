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



        /* Pohon keputusan rapi dengan panah */
        .tree-frame {
            border: 2px solid #111;
            border-radius: 24px;
            padding: 18px;
            background: #fff;
            margin-top: 15px;
            overflow-x: auto;
        }

        .tree-svg-wrap {
            width: 78%;
            min-width: 720px;
            max-width: 860px;
            margin: 0 auto;
        }

        .tree-svg {
            display: block;
            width: 100%;
            height: auto;
        }

        .tree-svg-second {
            margin-top: -8px;
        }

        .svg-box {
            fill: #fff;
            stroke: #222;
            stroke-width: 2.2;
        }

        .svg-oval {
            fill: #f9f9f9;
        }

        .svg-final {
            fill: #eef6ff;
        }

        .svg-line {
            stroke: #222;
            stroke-width: 2.2;
            fill: none;
        }

        .svg-text {
            font-family: Arial, sans-serif;
            font-size: 18px;
            font-weight: 700;
            fill: #222;
        }

        .svg-final-text {
            font-family: Arial, sans-serif;
            font-size: 19px;
            font-weight: 700;
            fill: #1f4e79;
        }

        @media (max-width: 800px) {
            .grid-2 {
                grid-template-columns: 1fr;
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

        <div class="info">
            Pohon keputusan dibuat dalam bentuk alur rapi. Setiap kotak menunjukkan langkah proses, panah menunjukkan alur, dan cabang menunjukkan hasil pengecekan standar nilai.
        </div>

        <div class="tree-frame">
            <div class="tree-svg-wrap">
                <svg class="tree-svg" viewBox="0 0 1100 1220" xmlns="http://www.w3.org/2000/svg" aria-label="Pohon keputusan sistem rekomendasi jurusan PCR">
                    <defs>
                        <marker id="arrowDown" markerWidth="10" markerHeight="10" refX="5" refY="5" orient="auto" markerUnits="strokeWidth">
                            <path d="M 0 0 L 10 5 L 0 10 z" fill="#222" />
                        </marker>
                    </defs>

                    <!-- kotak-kotak utama -->
                    <rect x="410" y="20" width="280" height="52" rx="0" ry="0" class="svg-box"/>
                    <text x="550" y="52" text-anchor="middle" class="svg-text">Mulai</text>

                    <line x1="550" y1="72" x2="550" y2="105" class="svg-line" marker-end="url(#arrowDown)"/>

                    <rect x="375" y="115" width="350" height="58" class="svg-box"/>
                    <text x="550" y="150" text-anchor="middle" class="svg-text">Input Data Siswa</text>

                    <line x1="550" y1="173" x2="550" y2="208" class="svg-line" marker-end="url(#arrowDown)"/>

                    <rect x="245" y="218" width="610" height="95" class="svg-box"/>
                    <text x="550" y="253" text-anchor="middle" class="svg-text">Jurusan SMA: <?= aman($siswa["jurusan_sma"]); ?></text>
                    <text x="550" y="279" text-anchor="middle" class="svg-text">Minat PCR: <?= aman($siswa["minat_pcr"]); ?></text>
                    <text x="550" y="305" text-anchor="middle" class="svg-text">Preferensi Prodi: <?= aman($siswa["preferensi_prodi"]); ?></text>

                    <line x1="550" y1="313" x2="550" y2="348" class="svg-line" marker-end="url(#arrowDown)"/>

                    <rect x="225" y="358" width="650" height="58" class="svg-box"/>
                    <text x="550" y="393" text-anchor="middle" class="svg-text">Bentuk Himpunan Data Siswa dan Program Studi</text>

                    <line x1="550" y1="416" x2="550" y2="451" class="svg-line" marker-end="url(#arrowDown)"/>

                    <rect x="225" y="461" width="650" height="58" class="svg-box"/>
                    <text x="550" y="496" text-anchor="middle" class="svg-text">Definisikan Relasi Siswa dengan Program Studi</text>

                    <line x1="550" y1="519" x2="550" y2="554" class="svg-line" marker-end="url(#arrowDown)"/>

                    <rect x="225" y="564" width="650" height="58" class="svg-box"/>
                    <text x="550" y="599" text-anchor="middle" class="svg-text">Hitung Nilai Akademik Setiap Alternatif Prodi</text>

                    <line x1="550" y1="622" x2="550" y2="657" class="svg-line" marker-end="url(#arrowDown)"/>

                    <rect x="225" y="667" width="650" height="58" class="svg-box"/>
                    <text x="550" y="702" text-anchor="middle" class="svg-text">Cek Standar Minimal Nilai Akademik = 80</text>

                    <!-- cabang -->
                    <line x1="550" y1="725" x2="550" y2="760" class="svg-line"/>
                    <line x1="275" y1="760" x2="825" y2="760" class="svg-line"/>
                    <line x1="275" y1="760" x2="275" y2="790" class="svg-line" marker-end="url(#arrowDown)"/>
                    <line x1="825" y1="760" x2="825" y2="790" class="svg-line" marker-end="url(#arrowDown)"/>

                    <rect x="185" y="800" width="180" height="48" class="svg-box small-box"/>
                    <text x="275" y="829" text-anchor="middle" class="svg-text">Nilai ≥ 80</text>
                    <line x1="275" y1="848" x2="275" y2="878" class="svg-line" marker-end="url(#arrowDown)"/>
                    <rect x="95" y="888" width="360" height="58" rx="28" ry="28" class="svg-box svg-oval"/>
                    <text x="275" y="923" text-anchor="middle" class="svg-text">Masuk kandidat rekomendasi</text>

                    <rect x="735" y="800" width="180" height="48" class="svg-box small-box"/>
                    <text x="825" y="829" text-anchor="middle" class="svg-text">Nilai &lt; 80</text>
                    <line x1="825" y1="848" x2="825" y2="878" class="svg-line" marker-end="url(#arrowDown)"/>
                    <rect x="645" y="888" width="360" height="58" rx="28" ry="28" class="svg-box svg-oval"/>
                    <text x="825" y="923" text-anchor="middle" class="svg-text">Tetap menjadi alternatif pembanding</text>

                    <!-- gabung lagi -->
                    <line x1="275" y1="946" x2="275" y2="982" class="svg-line"/>
                    <line x1="825" y1="946" x2="825" y2="982" class="svg-line"/>
                    <line x1="275" y1="982" x2="825" y2="982" class="svg-line"/>
                    <line x1="550" y1="982" x2="550" y2="1012" class="svg-line" marker-end="url(#arrowDown)"/>

                    <rect x="225" y="1022" width="650" height="72" class="svg-box"/>
                    <text x="550" y="1053" text-anchor="middle" class="svg-text">Hitung Skor Akhir</text>
                    <text x="550" y="1081" text-anchor="middle" class="svg-text">Minat 20% + Nilai Akademik 70% + Preferensi 10%</text>

                    <line x1="550" y1="1094" x2="550" y2="1128" class="svg-line" marker-end="url(#arrowDown)"/>

                    <rect x="225" y="1138" width="650" height="72" class="svg-box"/>
                    <text x="550" y="1168" text-anchor="middle" class="svg-text">Hitung Kombinatorika</text>
                    <text x="550" y="1196" text-anchor="middle" class="svg-text">P(<?= $jumlahProdi; ?>,<?= $jumlahRekomendasi; ?>) = <?= number_format($jumlahKemungkinanSusunan, 0, ',', '.'); ?> kemungkinan susunan rekomendasi</text>
                </svg>

                <svg class="tree-svg tree-svg-second" viewBox="0 0 1100 380" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <defs>
                        <marker id="arrowDown2" markerWidth="10" markerHeight="10" refX="5" refY="5" orient="auto" markerUnits="strokeWidth">
                            <path d="M 0 0 L 10 5 L 0 10 z" fill="#222" />
                        </marker>
                    </defs>
                    <line x1="550" y1="0" x2="550" y2="34" class="svg-line" marker-end="url(#arrowDown2)"/>

                    <rect x="225" y="44" width="650" height="94" class="svg-box"/>
                    <text x="550" y="80" text-anchor="middle" class="svg-text">Urutkan Rekomendasi</text>
                    <text x="550" y="108" text-anchor="middle" class="svg-text">1. Nilai Akademik Tertinggi   2. Kecocokan Minat</text>
                    <text x="550" y="132" text-anchor="middle" class="svg-text">3. Preferensi Prodi   4. Prioritas Prodi</text>

                    <line x1="550" y1="138" x2="550" y2="172" class="svg-line" marker-end="url(#arrowDown2)"/>

                    <rect x="360" y="182" width="380" height="58" class="svg-box"/>
                    <text x="550" y="217" text-anchor="middle" class="svg-text">Ambil 3 Rekomendasi Teratas</text>

                    <line x1="550" y1="240" x2="550" y2="274" class="svg-line" marker-end="url(#arrowDown2)"/>

                    <rect x="225" y="284" width="650" height="72" rx="36" ry="36" class="svg-box svg-final"/>
                    <text x="550" y="315" text-anchor="middle" class="svg-final-text">Rekomendasi Utama</text>
                    <text x="550" y="343" text-anchor="middle" class="svg-final-text"><?= aman($hasilUtama); ?></text>
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