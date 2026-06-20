<?php
session_start();

if (isset($_GET["reset"])) {
    unset($_SESSION["siswa"]);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Input Data Siswa</title>

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
            font-size: 28px;
        }

        header p {
            margin: 8px 0 0;
            font-size: 15px;
        }

        .container {
            width: 92%;
            max-width: 900px;
            margin: 25px auto;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        h2 {
            margin-top: 0;
            color: #1f4e79;
            border-left: 5px solid #1f4e79;
            padding-left: 10px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px 25px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-full {
            grid-column: span 2;
        }

        label {
            font-weight: bold;
            margin-bottom: 6px;
        }

        input, select {
            padding: 11px;
            border: 1px solid #aaa;
            border-radius: 6px;
            font-size: 14px;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #1f4e79;
        }

        .button-area {
            margin-top: 20px;
        }

        button {
            padding: 11px 18px;
            border: none;
            border-radius: 6px;
            background: #1f4e79;
            color: white;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }

        button:hover {
            background: #163a59;
        }

        .hidden {
            display: none;
        }

        @media (max-width: 750px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-full {
                grid-column: span 1;
            }
        }
    </style>
</head>

<body>

<header>
    <h1>Sistem Rekomendasi Jurusan PCR</h1>
    <p>Untuk Siswa SMA Baru Lulus Berdasarkan Jurusan SMA, Nilai, Minat, dan Preferensi Prodi</p>
</header>

<div class="container">
    <div class="card">
        <h2>Input Data Siswa</h2>

        <form method="POST" action="hasil.php">
            <div class="form-grid">

                <div class="form-group">
                    <label>Nama Siswa</label>
                    <input type="text" name="nama" placeholder="Contoh: Andi" required>
                </div>

                <div class="form-group">
                    <label>Jurusan SMA</label>
                    <select name="jurusan_sma" id="jurusan_sma" onchange="aturJurusanSMA()" required>
                        <option value="">-- Pilih Jurusan SMA --</option>
                        <option value="IPA">IPA</option>
                        <option value="IPS">IPS</option>
                    </select>
                </div>

                <div id="nilai_ipa_1" class="form-group hidden">
                    <label>Nilai Matematika</label>
                    <input type="number" name="nilai_mtk" min="0" max="100" placeholder="0-100">
                </div>

                <div id="nilai_ipa_2" class="form-group hidden">
                    <label>Nilai Fisika</label>
                    <input type="number" name="nilai_fisika" min="0" max="100" placeholder="0-100">
                </div>

                <div id="nilai_ipa_3" class="form-group form-full hidden">
                    <label>Nilai Bahasa Inggris</label>
                    <input type="number" name="nilai_inggris" min="0" max="100" placeholder="0-100">
                </div>

                <div id="nilai_ips_1" class="form-group hidden">
                    <label>Nilai Ekonomi</label>
                    <input type="number" name="nilai_ekonomi" min="0" max="100" placeholder="0-100">
                </div>

                <div id="nilai_ips_2" class="form-group hidden">
                    <label>Nilai Bahasa Inggris</label>
                    <input type="number" name="nilai_inggris_ips" min="0" max="100" placeholder="0-100">
                </div>

                <div id="nilai_ips_3" class="form-group form-full hidden">
                    <label>Nilai Matematika</label>
                    <input type="number" name="nilai_mtk_ips" min="0" max="100" placeholder="0-100">
                </div>

                <div class="form-group">
                    <label>Minat PCR</label>
                    <select name="minat_pcr" id="minat_pcr" onchange="aturPreferensi()" required>
                        <option value="">-- Pilih Minat PCR --</option>
                        <option value="Informasi">Informasi</option>
                        <option value="Industri">Industri</option>
                        <option value="Bisnis dan Komunikasi">Bisnis dan Komunikasi</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Preferensi Prodi</label>
                    <select name="preferensi_prodi" id="preferensi_prodi" required>
                        <option value="">-- Pilih Minat PCR terlebih dahulu --</option>
                    </select>
                </div>

            </div>

            <div class="button-area">
                <button type="submit">Proses Rekomendasi</button>
            </div>
        </form>
    </div>
</div>

<script>
    const prodiInformasi = [
        "Teknik Informatika",
        "Sistem Informasi",
        "Teknologi Rekayasa Komputer",
        "Animasi"
    ];

    const prodiIndustri = [
        "Teknik Elektronika",
        "Teknik Listrik",
        "Teknik Mesin",
        "Teknologi Rekayasa Jaringan Telekomunikasi",
        "Teknologi Rekayasa Sistem Elektronika",
        "Teknologi Rekayasa Mekatronika",
        "Kecerdasan Buatan dan Robotika"
    ];

    const prodiBisnisKomunikasi = [
        "Akuntansi Perpajakan",
        "Hubungan Masyarakat dan Komunikasi Digital",
        "Bisnis Digital"
    ];

    function aturJurusanSMA() {
        const jurusan = document.getElementById("jurusan_sma").value;

        const nilaiIPA = [
            document.getElementById("nilai_ipa_1"),
            document.getElementById("nilai_ipa_2"),
            document.getElementById("nilai_ipa_3")
        ];

        const nilaiIPS = [
            document.getElementById("nilai_ips_1"),
            document.getElementById("nilai_ips_2"),
            document.getElementById("nilai_ips_3")
        ];

        nilaiIPA.forEach(item => item.classList.add("hidden"));
        nilaiIPS.forEach(item => item.classList.add("hidden"));

        document.querySelectorAll("#nilai_ipa_1 input, #nilai_ipa_2 input, #nilai_ipa_3 input").forEach(input => {
            input.required = false;
            input.value = "";
        });

        document.querySelectorAll("#nilai_ips_1 input, #nilai_ips_2 input, #nilai_ips_3 input").forEach(input => {
            input.required = false;
            input.value = "";
        });

        if (jurusan === "IPA") {
            nilaiIPA.forEach(item => item.classList.remove("hidden"));

            document.querySelectorAll("#nilai_ipa_1 input, #nilai_ipa_2 input, #nilai_ipa_3 input").forEach(input => {
                input.required = true;
            });
        }

        if (jurusan === "IPS") {
            nilaiIPS.forEach(item => item.classList.remove("hidden"));

            document.querySelectorAll("#nilai_ips_1 input, #nilai_ips_2 input, #nilai_ips_3 input").forEach(input => {
                input.required = true;
            });
        }
    }

    function aturPreferensi() {
        const minat = document.getElementById("minat_pcr").value;
        const preferensi = document.getElementById("preferensi_prodi");

        preferensi.innerHTML = "";

        let defaultOption = document.createElement("option");
        defaultOption.value = "";
        defaultOption.textContent = "-- Pilih Preferensi Prodi --";
        preferensi.appendChild(defaultOption);

        let daftar = [];

        if (minat === "Informasi") {
            daftar = prodiInformasi;
        } else if (minat === "Industri") {
            daftar = prodiIndustri;
        } else if (minat === "Bisnis dan Komunikasi") {
            daftar = prodiBisnisKomunikasi;
        } else {
            defaultOption.textContent = "-- Pilih Minat PCR terlebih dahulu --";
        }

        daftar.forEach(prodi => {
            let option = document.createElement("option");
            option.value = prodi;
            option.textContent = prodi;
            preferensi.appendChild(option);
        });
    }
</script>

</body>
</html>