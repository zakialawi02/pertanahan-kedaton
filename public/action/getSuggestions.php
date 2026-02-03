<?php
// Include the database connection file
include 'db_connect.php';

$searchField = $_GET['searchField'] ?? '';
$searchValue = $_GET['searchValue'] ?? '';

if ($searchField && $searchValue) {
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
            $column = '';
    }

    if ($column) {
        $safeSearchValue = pg_escape_string($dbconn, $searchValue);

        $query = "
        SELECT DISTINCT $column AS result FROM \"Bidang Tanah\"
        LEFT JOIN \"Pemilik Tanah\" PemilikTanah ON \"Bidang Tanah\".\"NIB\" = PemilikTanah.\"NIB\"
        LEFT JOIN \"Orang\" OrangPemilik ON PemilikTanah.\"Nama\" = OrangPemilik.\"Id_Nama\"
        LEFT JOIN \"Wajib Pajak\" WajibPajak ON \"Bidang Tanah\".\"NOP\" = WajibPajak.\"NOP\"
        LEFT JOIN \"Orang\" OrangWP ON WajibPajak.\"Nama\" = OrangWP.\"Id_Nama\"
        WHERE $column ILIKE '%$safeSearchValue%'
        LIMIT 10
    ";

        $startTime = microtime(true);
        $result = pg_query($dbconn, $query);
        if ($result === false) {
            error_log('getSuggestions.php query failed: ' . pg_last_error($dbconn));
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
        $suggestions = [];

        foreach ($rows as $row) {
            $suggestions[] = [
                'result' => $row['result'],
                'execution_time_ms' => $executionTimeMs,
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($suggestions, JSON_PRETTY_PRINT);
    }
}

// Tutup koneksi
pg_close($dbconn);
