<?php
session_start();

function aman($text) {
    return htmlspecialchars($text, ENT_QUOTES, "UTF-8");
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

function ambilTopTigaProdi($siswa) {
    $daftarProdi = semuaProdiPCR();
    $skorProdi = [];

    foreach ($daftarProdi as $prodi) {
        $nilaiAkademik = nilaiAkademikProdi($siswa, $prodi);
        $standarMinimal = standarMinimalProdi($prodi);
        $skor = hitungSkorAkhir($siswa, $prodi);
        $minat = skorMinat($siswa, $prodi);
        $preferensi = skorPreferensi($siswa, $prodi);

        if ($nilaiAkademik >= $standarMinimal) {
            $skorProdi[] = [
                "prodi" => $prodi,
                "kategori" => kategoriProdi($prodi),
                "nilai_akademik" => $nilaiAkademik,
                "standar" => $standarMinimal,
                "minat" => $minat,
                "preferensi" => $preferensi,
                "skor" => $skor,
                "keterangan" => "Memenuhi standar"
            ];
        }
    }

    /*
        Kalau tidak ada prodi yang mencapai standar 80,
        sistem tetap menampilkan 3 alternatif terbaik berdasarkan nilai akademik.
    */
    if (count($skorProdi) == 0) {
        foreach ($daftarProdi as $prodi) {
            $nilaiAkademik = nilaiAkademikProdi($siswa, $prodi);
            $standarMinimal = standarMinimalProdi($prodi);
            $skor = hitungSkorAkhir($siswa, $prodi);
            $minat = skorMinat($siswa, $prodi);
            $preferensi = skorPreferensi($siswa, $prodi);

            $skorProdi[] = [
                "prodi" => $prodi,
                "kategori" => kategoriProdi($prodi),
                "nilai_akademik" => $nilaiAkademik,
                "standar" => $standarMinimal,
                "minat" => $minat,
                "preferensi" => $preferensi,
                "skor" => $skor,
                "keterangan" => "Alternatif terbaik berdasarkan nilai"
            ];
        }
    }

    usort($skorProdi, function($a, $b) {
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

    return array_slice($skorProdi, 0, 3);
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

$topTiga = ambilTopTigaProdi($siswa);
$hasilUtama = hasilRekomendasi($siswa);

/* Variabel untuk kombinatorika */
$jumlahProdi = count(semuaProdiPCR());
$jumlahRekomendasi = 3;
$jumlahKemungkinanSusunan = permutasi($jumlahProdi, $jumlahRekomendasi);
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
            max-width: 1100px;
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
        }

        .rumus {
            background: #f9f9f9;
            border: 1px solid #aaa;
            padding: 15px;
            line-height: 1.7;
            font-size: 16px;
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
    </style>
</head>

<body>

<header>
    <h1>Hasil Rekomendasi Jurusan PCR</h1>
    <p>Output Sistem Rekomendasi</p>
</header>

<div class="container">

    <div class="card">
        <h2>Tabel Relasi</h2>

        <div class="info">
            Relasi menunjukkan hubungan siswa dengan program studi rekomendasi. Angka 1 berarti program studi menjadi rekomendasi utama.
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

    <div class="card">
        <h2>Hasil Rekomendasi</h2>

        <div class="info">
            Rumus skor yang digunakan:
            <br>
            <b>Skor = (Minat × 20%) + (Nilai Akademik × 70%) + (Preferensi × 10%)</b>
        </div>

        <table>
            <tr>
                <th>No</th>
                <th>Program Studi PCR</th>
                <th>Kategori</th>
                <th>Nilai Akademik</th>
                <th>Standar Minimal</th>
                <th>Skor Akhir</th>
                <th>Keterangan</th>
            </tr>

            <?php $no = 1; ?>
            <?php foreach ($topTiga as $data): ?>
                <tr>
                    <td><?= $no; ?></td>
                    <td><?= aman($data["prodi"]); ?></td>
                    <td><?= aman($data["kategori"]); ?></td>
                    <td><?= number_format($data["nilai_akademik"], 1); ?></td>
                    <td><?= number_format($data["standar"], 1); ?></td>
                    <td><?= number_format($data["skor"], 1); ?></td>
                    <td>
                        <?php if ($no == 1): ?>
                            <span class="hasil">Rekomendasi utama</span>
                        <?php else: ?>
                            <?= aman($data["keterangan"]); ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php $no++; ?>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="card">
        <h2>Kombinatorika Rekomendasi Prodi</h2>

        <div class="info">
            Kombinatorika digunakan untuk menghitung banyaknya kemungkinan susunan rekomendasi program studi.
            Pada sistem ini terdapat <?= $jumlahProdi; ?> program studi PCR sebagai alternatif pilihan.
            Sistem mengambil <?= $jumlahRekomendasi; ?> rekomendasi terbaik berdasarkan nilai akademik, minat, dan preferensi.
            Karena hasil rekomendasi ditampilkan berdasarkan urutan peringkat, maka digunakan rumus kombinasi.
        </div>

        <div class="rumus">
            <p><b>Rumus Kombinasi:</b></p>
            <p>C(n,r) = n! / (r!(n-r)!)</p>

            <p><b>Perhitungan:</b></p>
            <p>
                C(<?= $jumlahProdi; ?>,<?= $jumlahRekomendasi; ?>)
                = <?= $jumlahProdi; ?>! / (<?= $jumlahRekomendasi; ?>! × (<?= $jumlahProdi; ?> - <?= $jumlahRekomendasi; ?>)!)
            </p>

            <p>
                C(<?= $jumlahProdi; ?>,<?= $jumlahRekomendasi; ?>)
                = <?= $jumlahProdi; ?> × <?= $jumlahProdi - 1; ?> × <?= $jumlahProdi - 2; ?>
                = <?= number_format($jumlahKemungkinanSusunan, 0, ',', '.'); ?>
            </p>

            <p>
                Jadi, terdapat <b><?= number_format($jumlahKemungkinanSusunan, 0, ',', '.'); ?></b>
                kemungkinan susunan 3 rekomendasi prodi dari <?= $jumlahProdi; ?> program studi PCR.
            </p>
        </div>
    </div>

    <div class="card">
        <h2>Pohon Keputusan</h2>

        <pre>
Mulai
  |
Input Jurusan SMA: <?= aman($siswa["jurusan_sma"]); ?>
  |
Input Nilai Mata Pelajaran
  |
Pilih Minat PCR: <?= aman($siswa["minat_pcr"]); ?>
  |
Pilih Preferensi Prodi: <?= aman($siswa["preferensi_prodi"]); ?>
  |
Hitung Nilai Akademik Setiap Prodi
  |
Khusus IPS:
- Informasi = MTK 70%, B.Inggris 20%, Ekonomi 10%
- Industri = MTK 70%, B.Inggris 20%, Ekonomi 10%
- Bisnis dan Komunikasi = Ekonomi dan B.Inggris lebih dominan
  |
Cek Standar Minimal Nilai Akademik Prodi = 80
  |
Hitung Skor:
- Minat 20%
- Nilai Akademik Prodi 70%
- Preferensi 10%
  |
Hitung Kombinatorika:
P(<?= $jumlahProdi; ?>,<?= $jumlahRekomendasi; ?>) = <?= number_format($jumlahKemungkinanSusunan, 0, ',', '.'); ?> kemungkinan susunan rekomendasi
  |
Urutkan Rekomendasi:
1. Nilai Akademik Prodi Tertinggi
2. Kecocokan Minat
3. Preferensi Prodi
4. Prioritas Prodi
  |
Ambil 3 Rekomendasi Teratas
  |
Rekomendasi Utama: <?= aman($hasilUtama); ?>
        </pre>
    </div>

    <div class="card">
        <h2>Hasil Analisis</h2>

        <div class="info">
            <p><?= aman(buatAnalisis($siswa)); ?></p>
            <p><b>Alasan rekomendasi:</b> <?= aman(alasanRekomendasi($siswa)); ?></p>
        </div>
    </div>

    <div class="back-area">
        <a href="index.php?reset=1" class="back">← Back</a>
    </div>

</div>

</body>
</html>