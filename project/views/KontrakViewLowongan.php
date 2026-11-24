<?php
// Kontrak view pasif untuk modul lowongan
interface KontrakViewLowongan {
    public function renderDaftar(string $tableRowsHtml, int $totalData): string;
    public function renderForm(string $title, string $action, array $formData = []): string;
}
?>
