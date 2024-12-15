<?php
require '../assets/mpdf/vendor/autoload.php';

// Membuat objek mPDF
$mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/tmp']);

// Menggunakan output buffering untuk menangkap HTML
ob_start();
require_once('cetak_all.php');  // Pastikan ini merujuk pada file yang berisi konten HTML untuk PDF
$html = ob_get_contents();
ob_end_clean();

// Menulis HTML ke PDF
$mpdf->WriteHTML($html);

// Membuat nama file PDF
$nama_file = "Rekap_Bimbingan_" . date('d-m-Y_H-i-s') . ".pdf";

// Mengirim PDF ke browser dalam mode 'inline' (ditampilkan di browser)
$mpdf->Output($nama_file, \Mpdf\Output\Destination::INLINE);