<?php
include '../../conn/koneksi.php';

// Ambil nama database
$dbName = mysqli_fetch_row(mysqli_query($koneksi, "SELECT DATABASE()"))[0];

// Set nama file output
$fileName = $dbName . '.sql';

// Atur header agar file didownload dengan nama yang sesuai
header('Content-Type: application/sql');
header('Content-Disposition: attachment; filename="' . $fileName . '"');

$output = "";

// Loop melalui semua tabel di database
$result = mysqli_query($koneksi, "SHOW TABLES");
while ($row = mysqli_fetch_row($result)) {
    $table = $row[0];

    // Dapatkan struktur tabel
    $createTableResult = mysqli_query($koneksi, "SHOW CREATE TABLE `$table`");
    $createTableRow = mysqli_fetch_row($createTableResult);
    $output .= "\n\n" . $createTableRow[1] . ";\n\n";

    // Dapatkan data dari tabel
    $dataResult = mysqli_query($koneksi, "SELECT * FROM `$table`");
    if (mysqli_num_rows($dataResult) > 0) {
        $fields = mysqli_fetch_fields($dataResult);
        $fieldNames = array_map(fn($field) => "`{$field->name}`", $fields);

        while ($data = mysqli_fetch_assoc($dataResult)) {
            $values = array_map(fn($value) => "'" . mysqli_real_escape_string($koneksi, $value) . "'", $data);
            $output .= "INSERT INTO `$table` (" . implode(", ", $fieldNames) . ") VALUES (" . implode(", ", $values) . ");\n";
        }
    }
}

echo $output;
exit;