<?php
// Kontrak view pasif untuk modul kandidat tanpa akses model atau perulangan data
interface KontrakView {
    public function renderDaftar(string $tableRowsHtml, int $totalData): string;
    public function renderForm(string $title, string $action, array $formData = []): string;
}
?>
