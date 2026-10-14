<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; 
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        @page {
            size: A4 landscape;
            margin: 10mm;
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
                border: 1px solid #e2e8f0 !important; 
                margin: 0 !important;
                max-width: 100% !important;
                border-radius: 1rem !important;
            }
            tr { page-break-inside: avoid; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen p-4 sm:p-8 text-gray-800">
    <!-- Action Bar (Hidden on print) -->
    <div class="max-w-5xl mx-auto mb-6 flex flex-wrap items-center justify-between gap-3 no-print bg-white p-3.5 rounded-2xl shadow-sm border border-gray-200">
        <div class="flex items-center gap-3">
            <a href="<?= BASEURL; ?>/team" class="px-3.5 py-2 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-100 transition flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Manajemen Tim
            </a>
            <span class="text-xs text-gray-300">|</span>
            <span class="text-xs font-bold text-gray-700 flex items-center gap-1.5">
                <i class="fa-solid fa-file-pdf text-rose-500"></i> Ekspor PDF Data Tim
            </span>
        </div>
        <div class="flex items-center gap-2 sm:gap-3">
            <a href="<?= BASEURL; ?>/team/exportCsv" class="px-3.5 py-2 rounded-xl text-xs font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 transition flex items-center gap-1.5" title="Download Spreadsheet CSV">
                <i class="fa-solid fa-file-csv text-emerald-600"></i> Unduh CSV
            </a>
            <button type="button" id="btnDownloadPdf" onclick="downloadDirectPDF()" class="px-4 py-2 rounded-xl text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 hover:bg-indigo-100 transition flex items-center gap-2" title="Unduh File PDF">
                <i class="fa-solid fa-download"></i> Unduh PDF
            </button>
            <button type="button" onclick="window.print()" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition flex items-center gap-2" title="Cetak atau Simpan sebagai PDF">
                <i class="fa-solid fa-print"></i> Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Printable Sheet: HANYA TABEL SESUAI PERMINTAAN USER -->
    <div id="printableArea" class="max-w-5xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden print-card">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-[#f8fafc] text-gray-700 font-bold uppercase tracking-wider border-b border-gray-200 text-[11px]">
                        <th class="py-4 px-4 w-12 text-center text-gray-500">NO.</th>
                        <th class="py-4 px-4">NAMA PROFIL</th>
                        <th class="py-4 px-4">EMAIL</th>
                        <th class="py-4 px-4">JABATAN / PROFESI</th>
                        <th class="py-4 px-4">HAK AKSES SISTEM</th>
                        <th class="py-4 px-4">DEPARTEMEN</th>
                        <th class="py-4 px-4 text-center">STATUS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($data['team'])) : ?>
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-400 font-medium">Tidak ada data anggota tim.</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($data['team'] as $idx => $m) : 
                            $isAdmin = ($m['role'] ?? '') === 'admin';
                            $isActive = strtoupper($m['status'] ?? 'AKTIF') === 'AKTIF';
                            $initials = strtoupper(substr($m['name'], 0, 2));
                        ?>
                            <tr class="hover:bg-gray-50/50">
                                <td class="py-3.5 px-4 text-center font-semibold text-gray-400"><?= $idx + 1; ?></td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-700 font-bold text-[11px] flex items-center justify-center shrink-0 border border-slate-200">
                                            <?= $initials; ?>
                                        </div>
                                        <span class="font-bold text-gray-900"><?= htmlspecialchars($m['name']); ?></span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-gray-600 font-normal font-mono text-[11px]"><?= htmlspecialchars($m['email']); ?></td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-block px-2.5 py-1 rounded-md text-[11px] font-semibold bg-gray-50 text-gray-800 border border-gray-200">
                                        <?= htmlspecialchars($m['role_label'] ?? $m['role']); ?>
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <?php if ($isAdmin) : ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                            <i class="fa-solid fa-shield-halved text-[10px]"></i> Akses Penuh (Admin)
                                        </span>
                                    <?php else : ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                            <i class="fa-solid fa-user-lock text-[10px] text-slate-500"></i> Akses Terbatas (Member)
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3.5 px-4 text-gray-700 font-medium"><?= htmlspecialchars($m['department'] ?? '-'); ?></td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-block px-3 py-0.5 rounded-full text-[11px] font-semibold <?= $isActive ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200'; ?>">
                                        <?= $isActive ? 'Aktif' : 'Nonaktif'; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function downloadDirectPDF() {
            const element = document.getElementById('printableArea');
            const btn = document.getElementById('btnDownloadPdf');
            const originalHTML = btn.innerHTML;
            
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyusun PDF...';
            btn.disabled = true;

            const opt = {
                margin:       [10, 10, 10, 10],
                filename:     'Data_Anggota_Tim_' + new Date().toISOString().slice(0, 10) + '.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true, logging: false },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape' }
            };

            html2pdf().set(opt).from(element).save().then(() => {
                btn.innerHTML = originalHTML;
                btn.disabled = false;
            }).catch(err => {
                console.error(err);
                btn.innerHTML = originalHTML;
                btn.disabled = false;
                window.print();
            });
        }
    </script>
</body>
</html>