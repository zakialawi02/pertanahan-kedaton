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

        $result = pg_query($dbconn, $query);
        $suggestions = [];

        while ($row = pg_fetch_assoc($result)) {
            $suggestions[] = $row['result'];
        }

        header('Content-Type: application/json');
        echo json_encode($suggestions, JSON_PRETTY_PRINT);
    }
}

// Tutup koneksi
pg_close($dbconn);
