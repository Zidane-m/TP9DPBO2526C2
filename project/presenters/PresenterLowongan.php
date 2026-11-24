<?php
require_once __DIR__ . '/KontrakPresenterLowongan.php';
require_once __DIR__ . '/../models/KontrakModelLowongan.php';
require_once __DIR__ . '/../views/KontrakViewLowongan.php';

// Presenter untuk menghubungkan model dan view pada modul lowongan
class PresenterLowongan implements KontrakPresenterLowongan {
    private KontrakModelLowongan $model;
    private KontrakViewLowongan $view;

    public function __construct(KontrakModelLowongan $model, KontrakViewLowongan $view) {
        $this->model = $model;
        $this->view = $view;
    }

    // Merakit satu baris HTML tabel lowongan agar View tetap pasif
    private function buildRowHtml(int $no, Lowongan $lowongan): string {
        $id = (int)$lowongan->getId();
        $judul = htmlspecialchars($lowongan->getJudul());
        $departemen = htmlspecialchars($lowongan->getDepartemen());
        $lokasi = htmlspecialchars($lowongan->getLokasi());
        $kuota = number_format($lowongan->getKuota()) . ' Orang';
        $status = htmlspecialchars($lowongan->getStatus());

        return "<tr>
            <td class='col-id'>{$no}</td>
            <td><strong>{$judul}</strong></td>
            <td>{$departemen}</td>
            <td>{$lokasi}</td>
            <td>{$kuota}</td>
            <td>{$status}</td>
            <td class='col-actions'>
                <a href='index.php?modul=lowongan&screen=edit&id={$id}' class='btn btn-edit'>Edit</a>
                <form method='post' action='index.php?modul=lowongan' style='display:inline;' onsubmit=\"return confirm('Hapus lowongan ini?')\">
                    <input type='hidden' name='action' value='hapus'>
                    <input type='hidden' name='id' value='{$id}'>
                    <button type='submit' class='btn btn-delete'>Hapus</button>
                </form>
            </td>
        </tr>";
    }

    // Mengambil daftar lowongan dari model dan mengirim hasil HTML ke view
    public function tampilkanLowongan(): string {
        $listLowongan = $this->model->getAllLowongan();
        $totalLowongan = count($listLowongan);

        $rowsHtml = '';
        if (empty($listLowongan)) {
            $rowsHtml = '<tr><td colspan="7" style="text-align:center;color:#6b7280;padding:24px;">Belum ada data lowongan.</td></tr>';
        } else {
            $no = 1;
            foreach ($listLowongan as $lowongan) {
                $rowsHtml .= $this->buildRowHtml($no, $lowongan);
                $no++;
            }
        }

        return $this->view->renderDaftar($rowsHtml, $totalLowongan);
    }

    // Menampilkan form tambah atau ubah data lowongan berdasarkan keberadaan ID
    public function tampilkanFormLowongan(?int $id = null): string {
        if ($id !== null) {
            $lowongan = $this->model->getLowonganById($id);
            if ($lowongan === null) {
                header("Location: index.php?modul=lowongan");
                exit();
            }

            $formData = [
                'id'         => $lowongan->getId(),
                'judul'      => $lowongan->getJudul(),
                'departemen' => $lowongan->getDepartemen(),
                'lokasi'     => $lowongan->getLokasi(),
                'kuota'      => $lowongan->getKuota(),
                'status'     => $lowongan->getStatus(),
            ];
            return $this->view->renderForm("Ubah Data Lowongan", "ubah", $formData);
        }

        return $this->view->renderForm("Tambah Lowongan Baru", "tambah", []);
    }

    // Meneruskan data lowongan baru ke model lalu kembali ke halaman daftar
    public function tambahLowongan(string $judul, string $departemen, string $lokasi, int $kuota, string $status): void {
        $this->model->addLowongan($judul, $departemen, $lokasi, $kuota, $status);
        header("Location: index.php?modul=lowongan");
        exit();
    }

    // Meneruskan pembaruan data lowongan ke model lalu kembali ke halaman daftar
    public function ubahLowongan(int $id, string $judul, string $departemen, string $lokasi, int $kuota, string $status): void {
        $this->model->updateLowongan($id, $judul, $departemen, $lokasi, $kuota, $status);
        header("Location: index.php?modul=lowongan");
        exit();
    }

    // Meneruskan penghapusan data lowongan ke model lalu kembali ke halaman daftar
    public function hapusLowongan(int $id): void {
        $this->model->deleteLowongan($id);
        header("Location: index.php?modul=lowongan");
        exit();
    }
}
?>
