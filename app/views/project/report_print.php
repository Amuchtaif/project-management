<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        @page {
            size: A4;
            margin: 12mm;
        }
        @media print {
            .no-print { display: none !important; }
            body { 
                background: #fff !important; 
                color: #111827 !important; 
                padding: 0 !important;
                margin: 0 !important;
            }
            .print-card { 
                box-shadow: none !important; 
                border: none !important; 
                padding: 0 !important;
                max-width: 100% !important;
                border-radius: 0 !important;
            }
            tr { page-break-inside: avoid; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen p-4 sm:p-8 text-gray-800">
    <!-- Action Bar (Hidden on print) -->
    <div class="max-w-5xl mx-auto mb-6 flex flex-wrap items-center justify-between gap-3 no-print bg-white p-3.5 rounded-xl shadow-2xs border border-gray-200">
        <div class="flex items-center gap-3">
            <a href="<?= BASEURL; ?>/project" class="px-3.5 py-2 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-100 transition flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Project
            </a>
            <span class="text-xs text-gray-300">|</span>
            <span class="text-xs font-bold text-gray-800">Daftar Project</span>
        </div>
        <div class="flex items-center gap-2 sm:gap-3">
            <a href="<?= BASEURL; ?>/project/exportCsv" class="px-3.5 py-2 rounded-lg text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 transition flex items-center gap-1.5" title="Download Spreadsheet CSV">
                <i class="fa-solid fa-file-csv text-emerald-600"></i> Unduh CSV
            </a>
            <button type="button" onclick="window.print()" class="px-4 py-2 rounded-lg text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition flex items-center gap-2 shadow-2xs" title="Cetak atau Simpan sebagai PDF">
                <i class="fa-solid fa-print"></i> Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Printable Sheet (Plain Table Format) -->
    <div class="max-w-5xl mx-auto bg-white p-6 sm:p-8 rounded-xl shadow-2xs border border-gray-200 print-card">
        <!-- Simple Header (No Kop Surat) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 mb-5 border-b border-gray-200 gap-2">
            <div>
                <h1 class="text-lg font-bold text-gray-900">Daftar Project</h1>
                <p class="text-xs text-gray-500 mt-0.5">Total <?= count($data['projects']); ?> project tercatat dalam sistem</p>
            </div>
            <div class="text-xs text-gray-400 font-medium">
                Tanggal Cetak: <span class="text-gray-600"><?= $data['generated_at']; ?></span>
            </div>
        </div>

        <!-- Plain Table -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 font-semibold border-b border-gray-200">
                        <th class="py-2.5 px-3 w-12 text-center">No</th>
                        <th class="py-2.5 px-3">Nama Project</th>
                        <th class="py-2.5 px-3">Klien</th>
                        <th class="py-2.5 px-3">Lead Project</th>
                        <th class="py-2.5 px-3">Tenggat Waktu</th>
                        <th class="py-2.5 px-3">Status</th>
                        <th class="py-2.5 px-3 text-right">Progres</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($data['projects'])) : ?>
                        <tr>
                            <td colspan="7" class="p-6 text-center text-gray-400 italic">Tidak ada data project.</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($data['projects'] as $idx => $p) : 
                            $status = trim($p['status'] ?? 'Sesuai Jadwal');
                            $statusLower = strtolower($status);
                            $progress = (int)round($p['avg_progress'] ?? ($p['progress'] ?? 0));
                            
                            $badgeClass = 'bg-emerald-50 text-emerald-700 border border-emerald-200';
                            if ($statusLower === 'terhambat') {
                                $badgeClass = 'bg-rose-50 text-rose-700 border border-rose-200';
                            } elseif ($statusLower === 'selesai' || $progress >= 100) {
                                $badgeClass = 'bg-blue-50 text-blue-700 border border-blue-200';
                            }
                        ?>
                            <tr class="hover:bg-gray-50/70">
                                <td class="py-2.5 px-3 text-center text-gray-400 font-medium"><?= $idx + 1; ?></td>
                                <td class="py-2.5 px-3 font-semibold text-gray-900"><?= htmlspecialchars($p['name']); ?></td>
                                <td class="py-2.5 px-3 text-gray-600"><?= htmlspecialchars($p['client'] ?? '-'); ?></td>
                                <td class="py-2.5 px-3 text-gray-700 font-medium"><?= htmlspecialchars($p['leader_name'] ?? '-'); ?></td>
                                <td class="py-2.5 px-3 text-gray-600"><?= !empty($p['due_date']) ? date('d M Y', strtotime($p['due_date'])) : '-'; ?></td>
                                <td class="py-2.5 px-3">
                                    <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold <?= $badgeClass; ?>">
                                        <?= htmlspecialchars($status); ?>
                                    </span>
                                </td>
                                <td class="py-2.5 px-3 text-right font-bold text-gray-900"><?= $progress; ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
