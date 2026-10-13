<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; color: #000 !important; }
            .print-card { box-shadow: none !important; border: 1px solid #e2e8f0 !important; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen p-4 sm:p-8">
    <!-- Action Bar (Hidden on print) -->
    <div class="max-w-5xl mx-auto mb-6 flex items-center justify-between no-print bg-white p-4 rounded-2xl shadow-sm border border-gray-200">
        <div class="flex items-center gap-3">
            <a href="<?= BASEURL; ?>/project" class="px-4 py-2 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-100 transition flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Proyek
            </a>
            <span class="text-xs text-gray-400">|</span>
            <span class="text-xs font-bold text-gray-600">Laporan Eksekutif Proyek</span>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?= BASEURL; ?>/project/exportCsv" class="px-4 py-2 rounded-xl text-xs font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 transition flex items-center gap-2">
                <i class="fa-solid fa-file-csv text-emerald-600"></i> Unduh CSV
            </a>
            <button onclick="window.print()" class="px-5 py-2.5 rounded-xl text-xs font-black text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-100 transition flex items-center gap-2">
                <i class="fa-solid fa-print"></i> Cetak Dokumen / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Printable Sheet -->
    <div class="max-w-5xl mx-auto bg-white p-8 sm:p-12 rounded-3xl shadow-lg border border-gray-100 print-card">
        <!-- Letterhead Header -->
        <div class="flex items-start justify-between border-b-2 border-gray-900 pb-6 mb-8">
            <div class="flex items-center gap-4">
                <img src="<?= BASEURL; ?>/img/logo.png" alt="Logo" class="w-16 h-16 object-contain" onerror="this.style.display='none'">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Yayasan Assunnah</h1>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mt-0.5">Sistem Manajemen Proyek & Tugas</p>
                    <p class="text-[11px] text-gray-400 mt-1">Laporan Kemajuan & Status Portofolio Proyek</p>
                </div>
            </div>
            <div class="text-right text-xs">
                <p class="font-black text-gray-900">DOKUMEN RESMI</p>
                <p class="text-gray-500 mt-1">Tanggal: <?= $data['generated_at']; ?></p>
                <p class="text-gray-500">Penyusun: <?= htmlspecialchars($data['generated_by']); ?></p>
            </div>
        </div>

        <!-- Summary Metrics -->
        <?php 
            $totalProjects = count($data['projects']);
            $completedProjects = 0;
            $avgProgress = 0;
            $sumProgress = 0;
            foreach ($data['projects'] as $p) {
                if ($p['status'] === 'Selesai' || $p['progress'] >= 100) $completedProjects++;
                $sumProgress += (int)$p['progress'];
            }
            if ($totalProjects > 0) $avgProgress = round($sumProgress / $totalProjects);
        ?>
        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 text-center">
                <p class="text-[10px] font-black uppercase tracking-wider text-gray-400">Total Proyek</p>
                <p class="text-2xl font-black text-gray-900 mt-1"><?= $totalProjects; ?></p>
            </div>
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-center">
                <p class="text-[10px] font-black uppercase tracking-wider text-emerald-600">Proyek Selesai</p>
                <p class="text-2xl font-black text-emerald-600 mt-1"><?= $completedProjects; ?></p>
            </div>
            <div class="p-4 rounded-2xl bg-indigo-50 border border-indigo-100 text-center">
                <p class="text-[10px] font-black uppercase tracking-wider text-indigo-600">Rata-rata Kemajuan</p>
                <p class="text-2xl font-black text-indigo-600 mt-1"><?= $avgProgress; ?>%</p>
            </div>
        </div>

        <!-- Projects Table -->
        <div class="mb-10 overflow-hidden rounded-2xl border border-gray-200">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 font-black uppercase tracking-wider border-b border-gray-200">
                        <th class="p-3.5">#</th>
                        <th class="p-3.5">Nama Proyek</th>
                        <th class="p-3.5">Klien</th>
                        <th class="p-3.5">Leader</th>
                        <th class="p-3.5">Tenggat Waktu</th>
                        <th class="p-3.5">Status</th>
                        <th class="p-3.5 text-right">Progres</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($data['projects'])) : ?>
                        <tr>
                            <td colspan="7" class="p-6 text-center text-gray-400 font-bold">Tidak ada data proyek.</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($data['projects'] as $idx => $p) : ?>
                            <tr class="hover:bg-gray-50">
                                <td class="p-3.5 font-bold text-gray-400"><?= $idx + 1; ?></td>
                                <td class="p-3.5 font-black text-gray-900"><?= htmlspecialchars($p['name']); ?></td>
                                <td class="p-3.5 text-gray-600 font-medium"><?= htmlspecialchars($p['client']); ?></td>
                                <td class="p-3.5 text-gray-700 font-bold"><?= htmlspecialchars($p['leader_name'] ?? '-'); ?></td>
                                <td class="p-3.5 text-gray-600 font-medium"><?= date('d M Y', strtotime($p['due_date'])); ?></td>
                                <td class="p-3.5">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider <?= $p['status'] === 'Selesai' ? 'bg-emerald-100 text-emerald-700' : ($p['status'] === 'Terhambat' ? 'bg-rose-100 text-rose-700' : 'bg-blue-100 text-blue-700'); ?>">
                                        <?= htmlspecialchars($p['status']); ?>
                                    </span>
                                </td>
                                <td class="p-3.5 text-right font-black text-gray-900"><?= (int)$p['progress']; ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Signatures Area -->
        <div class="grid grid-cols-2 gap-8 pt-8 border-t border-gray-200 mt-12 text-xs">
            <div class="text-center">
                <p class="text-gray-500 font-medium mb-16">Penyusun Laporan,</p>
                <p class="font-black text-gray-900 uppercase underline"><?= htmlspecialchars($data['generated_by']); ?></p>
                <p class="text-gray-400 text-[10px]">Staff Manajemen Proyek</p>
            </div>
            <div class="text-center">
                <p class="text-gray-500 font-medium mb-16">Mengetahui / Menyetujui,</p>
                <p class="font-black text-gray-900 uppercase underline">Pimpinan Yayasan Assunnah</p>
                <p class="text-gray-400 text-[10px]">Ketua Pengurus</p>
            </div>
        </div>
    </div>
</body>
</html>
