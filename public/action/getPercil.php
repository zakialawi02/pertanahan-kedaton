<?php
// Include the database connection file
include 'db_connect.php';

// jika request method get biasa
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $blokFilter = '';
    if (isset($_GET['blok']) && !empty($_GET['blok'])) {
        $blok = $_GET['blok'];
        $blokFilter = ' AND WajibPajak."Blok" = \'' . $blok . '\'';
    }
    // Filter Agama
    $agamaFilter = '';
    if (isset($_GET['agama'])) {
        $agama = $_GET['agama'];
        if ($agama === 'null') {
            $agamaFilter = ' AND AgamaPemilik."Nama_Agama" IS NULL';
        } elseif (!empty($agama)) {
            $agamaFilter = ' AND AgamaPemilik."Nama_Agama" = \'' . $agama . '\'';
        }
    }
    // Filter Tipe Hak
    $tipeHakFilter = '';
    if (isset($_GET['tipeHak'])) {
        $tipeHak = $_GET['tipeHak'];
        if ($tipeHak === 'null') {
            $tipeHakFilter = ' AND TipeHak."Nama_TipeHak" IS NULL';
        } elseif (!empty($tipeHak)) {
            $tipeHakFilter = ' AND TipeHak."Nama_TipeHak" = \'' . $tipeHak . '\'';
        }
    }
    // Filter Search Field
    $searchField = '';
    $searchValue = '';
    $searchQuery = '';
    if (isset($_GET['searchField']) && isset($_GET['searchValue'])) {
        $searchField = $_GET['searchField'];
        $searchValue = $_GET['searchValue'];

        // Validasi searchField
        switch ($searchField) {
            case 'nama_pemilik':
                $column = 'OrangPemilik."Nama"';
                break;
            case 'nama_wajib_pajak':
                $column = 'OrangWP."Nama"';
                break;
            case 'nib':
                $column = 'BidangTanah."NIB"::text';
                break;
            case 'nop':
                $column = 'BidangTanah."NOP"::text';
                break;
            default:
                $column = ''; // Invalid field
                break;
        }

        if (!empty($column) && !empty($searchValue)) {
            // Sanitasi dasar, gunakan pg_escape_string kalau perlu
            $escapedValue = pg_escape_string($dbconn, $searchValue);
            $searchQuery = ' AND ' . $column . ' ILIKE \'%' . $escapedValue . '%\'';
        }
    }

    $query = '
        SELECT
            BidangTanah."Id_Bidang",
            BidangTanah."NIB",
            OrangPemilik."Nama" AS "Nama Pemilik",
            OrangPemilik."NIK",
            OrangPemilik."Tanggal Lahir",
            OrangPemilik."Umur",
            PekerjaanPemilik."Nama_Pekerjaan" AS "Pekerjaan",
            AgamaPemilik."Nama_Agama" AS "Agama",
            OrangPemilik."Alamat",
            BidangTanah."NOP",
            OrangWP."Nama" AS "Nama Wajib Pajak",
            OrangWP."NIK" AS "NIK WP",
            OrangWP."Tanggal Lahir" AS "Tanggal Lahir WP",
            OrangWP."Umur" AS "Umur WP",
            PekerjaanWP."Nama_Pekerjaan" AS "Pekerjaan WP",
            AgamaWP."Nama_Agama" AS "Agama WP",
            OrangWP."Alamat" AS "Alamat WP",
            WajibPajak."Kategori",
            WajibPajak."Blok",
            WajibPajak."NJOP",
            WajibPajak."Tahun SPPT",
            Penggunaan."Nama_Penggunaan" AS "Penggunaan",
            TipeHak."Nama_TipeHak" AS "Tipe Hak",
            PemilikTanah."Tahun Perolehan",
            DasarPerolehan."Nama_DasarPerolehan" AS "Dasar Perolehan",
            BuktiPerolehan."Nama_BuktiPerolehan" AS "Bukti Perolehan",
            PemilikTanah."Tanggal Berkas",
            BidangTanah."Luas",
            Geometry."Geometry",
            Desa."Nama_Desa" AS "Desa",
            Provinsi."Nama_Provinsi" AS "Provinsi",
            Kabupaten."Nama_Kabupaten" AS "Kabupaten",
            Kecamatan."Nama_Kecamatan" AS "Kecamatan"
        FROM
            "Provinsi" Provinsi
        LEFT JOIN "Kabupaten" Kabupaten ON Provinsi."Id_Provinsi" = Kabupaten."Id_Provinsi"
        LEFT JOIN "Kecamatan" Kecamatan ON Kabupaten."Id_Kabupaten" = Kecamatan."Id_Kabupaten"
        LEFT JOIN "Desa" Desa ON Kecamatan."Id_Kecamatan" = Desa."Id_Kecamatan"
        LEFT JOIN "Bidang Tanah" BidangTanah ON Desa."Id_Desa" = BidangTanah."Desa"
        LEFT JOIN "Geometry" Geometry ON BidangTanah."Geometry" = Geometry."Id_Geometry"
        LEFT JOIN "Pemilik Tanah" PemilikTanah ON BidangTanah."NIB" = PemilikTanah."NIB"
        LEFT JOIN "Wajib Pajak" WajibPajak ON BidangTanah."NOP" = WajibPajak."NOP"
        LEFT JOIN "Orang" OrangWP ON WajibPajak."Nama" = OrangWP."Id_Nama"
        LEFT JOIN "Orang" OrangPemilik ON PemilikTanah."Nama" = OrangPemilik."Id_Nama"
        LEFT JOIN "Tipe Hak" TipeHak ON PemilikTanah."Tipe Hak" = TipeHak."Id_TipeHak"
        LEFT JOIN "Dasar Perolehan" DasarPerolehan ON PemilikTanah."Dasar Perolehan" = DasarPerolehan."Id_DasarPerolehan"
        LEFT JOIN "Bukti Perolehan" BuktiPerolehan ON PemilikTanah."Bukti Perolehan" = BuktiPerolehan."Id_BuktiPerolehan"
        LEFT JOIN "Penggunaan" Penggunaan ON WajibPajak."Penggunaan" = Penggunaan."Id_Penggunaan"
        LEFT JOIN "Pekerjaan" PekerjaanPemilik ON OrangPemilik."Pekerjaan" = PekerjaanPemilik."Id_Pekerjaan"
        LEFT JOIN "Pekerjaan" PekerjaanWP ON OrangWP."Pekerjaan" = PekerjaanWP."Id_Pekerjaan"
        LEFT JOIN "Agama" AgamaPemilik ON OrangPemilik."Agama" = AgamaPemilik."Id_Agama"
        LEFT JOIN "Agama" AgamaWP ON OrangWP."Agama" = AgamaWP."Id_Agama"
        WHERE BidangTanah."Id_Bidang" IS NOT NULL
        ' . $blokFilter . $agamaFilter . $tipeHakFilter . $searchQuery . '
        ORDER BY BidangTanah."Id_Bidang" ASC
    ';

    // --- MODE EXPLAIN (untuk uji kecepatan murni di DB) ---
    if (isset($_GET['bench'])) {
        $n = (int)$_GET['bench'];
        if ($n <= 0) $n = 10;
        if ($n > 200) $n = 200; // safety

        $holdMs = 100;

        $mode = $_GET['mode'] ?? 'explain'; // 'explain' atau 'select'

        $times = [];
        $errors = [];

        for ($i = 0; $i < $n; $i++) {
            $t0 = microtime(true);

            if ($mode === 'select') {
                $res = pg_query($dbconn, $query);
                if ($res === false) {
                    $errors[] = pg_last_error($dbconn);
                    break;
                }
                // Kalau benar-benar mau ukur query+fetch:
                $rows = pg_fetch_all($res); // hati-hati memory kalau besar
            } else {
                $sqlNoSemicolon = rtrim(trim($query), " \t\n\r\0\x0B;");
                $explainSql = 'EXPLAIN (ANALYZE, BUFFERS, VERBOSE, FORMAT TEXT) ' . $sqlNoSemicolon;

                $res = pg_query($dbconn, $explainSql);
                if ($res === false) {
                    $errors[] = pg_last_error($dbconn);
                    break;
                }
                // fetch plan text
                $planRows = pg_fetch_all($res) ?: [];
                // optional: parse Execution Time dari plan
            }

            $t1 = microtime(true);
            $times[] = ($t1 - $t0) * 1000.0;

            // HOLD TIME sebelum query berikutnya
            usleep($holdMs * 1000);
        }

        // summary
        sort($times);
        $count = count($times);
        $avg = $count ? array_sum($times) / $count : 0;

        $p50 = $count ? $times[(int)floor(0.50 * ($count - 1))] : 0;
        $p95 = $count ? $times[(int)floor(0.95 * ($count - 1))] : 0;
        $min = $count ? $times[0] : 0;
        $max = $count ? $times[$count - 1] : 0;

        header('Content-Type: application/json');
        echo json_encode([
            "meta" => [
                "runs" => $n,
                "mode" => $mode,
                "avg_ms" => round($avg, 3),
                "p50_ms" => round($p50, 3),
                "p95_ms" => round($p95, 3),
                "min_ms" => round($min, 3),
                "max_ms" => round($max, 3),
                "errors" => $errors,
            ],
            "runs_ms" => array_map(fn($x) => round($x, 3), $times),
        ]);
        pg_close($dbconn);
        exit;
    }

    // --- END MODE EXPLAIN ---


    // Eksekusi query
    $startTime = microtime(true);
    $result = pg_query($dbconn, $query);
    if ($result === false) {
        error_log('getPercil.php query failed: ' . pg_last_error($dbconn));
        http_response_code(500);
        echo json_encode(['error' => 'Internal server error']);
        pg_close($dbconn);
        exit;
    }
    $rows = pg_fetch_all($result, PGSQL_ASSOC);
    if ($rows === false) {
        $rows = [];
    }
    $executionTimeMs = (microtime(true) - $startTime) * 1000;

    $resultDB = array_map(function ($row) use ($executionTimeMs) {
        $row['execution_time_ms'] = $executionTimeMs;
        return $row;
    }, $rows);

    // Contoh output hasilnya
    header('Content-Type: application/json');
    echo json_encode([
        'execution_time_ms' => $executionTimeMs,
        'records' => $rows,
    ], JSON_PRETTY_PRINT);

    // Tutup koneksi
    pg_close($dbconn);
}
