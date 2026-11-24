<?php
// Kontrak komunikasi untuk model tabel lowongan
interface KontrakModelLowongan {
    public function getAllLowongan(): array;
    public function getLowonganById(int $id): ?Lowongan;
    public function addLowongan(string $judul, string $departemen, string $lokasi, int $kuota, string $status): void;
    public function updateLowongan(int $id, string $judul, string $departemen, string $lokasi, int $kuota, string $status): void;
    public function deleteLowongan(int $id): void;
}
?>
