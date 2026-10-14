<?php
// Compute summary counts
$totalProjects = count($data['projects'] ?? []);
$onScheduleCount = 0;
$delayedCount = 0;
$completedCount = 0;

foreach (($data['projects'] ?? []) as $p) {
    $st = strtolower(trim($p['status'] ?? ''));
    if ($st === 'sesuai jadwal') $onScheduleCount++;
    elseif ($st === 'terhambat') $delayedCount++;
    elseif ($st === 'selesai') $completedCount++;
}
?>

<!-- Header Actions Teleport Content -->
<div id="project-action-content" class="hidden">
    <div class="flex items-center gap-2.5">
        <!-- Direct PDF / Report Print Button -->
        <a href="<?= BASEURL; ?>/project/reportPrint" target="_blank" class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-200 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-50 dark:hover:bg-slate-700 transition flex items-center gap-2 shadow-2xs" title="Cetak Dokumen / Ekspor PDF Laporan Proyek">
            <i class="fa-solid fa-file-pdf text-rose-500"></i>
            <span>Ekspor PDF</span>
        </a>
        <?php if(isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') : ?>
        <!-- Add Project Button -->
        <button type="button" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition flex items-center justify-center gap-2 shadow-sm shadow-indigo-200 dark:shadow-none btnTambahProyek">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Buat Project Baru</span>
        </button>
        <?php endif; ?>
    </div>
</div>

<!-- Project Metric Stats Strip -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 mb-6">
    <!-- Card 1: Total Project -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-4 shadow-2xs hover:border-indigo-200 dark:hover:border-slate-600 transition-all duration-300 group">
        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-5xl sm:text-6xl text-indigo-600/10 dark:text-indigo-400/15 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300 pointer-events-none select-none">
            <i class="fa-solid fa-diagram-project"></i>
        </div>
        <div class="relative z-10">
            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 block">Total Project</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100" id="statTotal"><?= $totalProjects; ?></span>
                <span class="text-[11px] font-medium text-gray-400 dark:text-gray-500">project</span>
            </div>
        </div>
    </div>

    <!-- Card 2: Sesuai Jadwal -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-4 shadow-2xs hover:border-emerald-200 dark:hover:border-slate-600 transition-all duration-300 group">
        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-5xl sm:text-6xl text-emerald-600/10 dark:text-emerald-400/15 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300 pointer-events-none select-none">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="relative z-10">
            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 block">Sesuai Jadwal</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-bold text-emerald-700 dark:text-emerald-300"><?= $onScheduleCount; ?></span>
                <span class="text-[11px] font-medium text-emerald-600/70 dark:text-emerald-400/70">Berjalan Normal</span>
            </div>
        </div>
    </div>

    <!-- Card 3: Terhambat -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-4 shadow-2xs hover:border-rose-200 dark:hover:border-slate-600 transition-all duration-300 group">
        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-5xl sm:text-6xl text-rose-600/10 dark:text-rose-400/15 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300 pointer-events-none select-none">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div class="relative z-10">
            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 block">Terhambat</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-bold text-rose-700 dark:text-rose-300"><?= $delayedCount; ?></span>
                <span class="text-[11px] font-medium text-rose-600/70 dark:text-rose-400/70">Perlu Perhatian</span>
            </div>
        </div>
    </div>

    <!-- Card 4: Selesai -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-4 shadow-2xs hover:border-blue-200 dark:hover:border-slate-600 transition-all duration-300 group">
        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-5xl sm:text-6xl text-blue-600/10 dark:text-blue-400/15 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300 pointer-events-none select-none">
            <i class="fa-solid fa-flag-checkered"></i>
        </div>
        <div class="relative z-10">
            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 block">Selesai</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-bold text-blue-700 dark:text-blue-300"><?= $completedCount; ?></span>
                <span class="text-[11px] font-medium text-blue-600/70 dark:text-blue-400/70">Tuntas</span>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filtering Toolbar -->
<div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-4 mb-5 shadow-2xs transition-colors">
    <div class="flex flex-col lg:flex-row gap-3.5 items-stretch lg:items-center justify-between">
        <!-- Search Input -->
        <div class="relative flex-grow max-w-xl">
            <input type="text" id="projectSearch" placeholder="Cari nama project, klien, atau leader..." value="<?= htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES); ?>"
                class="w-full bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl pl-10 pr-9 py-2.5 text-sm text-gray-800 dark:text-gray-200 placeholder-gray-400 focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition">
            <i class="fa-solid fa-magnifying-glass text-gray-400 dark:text-gray-500 absolute left-3.5 top-1/2 -translate-y-1/2 text-sm pointer-events-none"></i>
            <button type="button" id="clearSearchBtn" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Filter Controls & Export Group -->
        <div class="flex flex-wrap items-center gap-2.5">
            <!-- Filter Status -->
            <div class="relative min-w-[140px]">
                <select id="filterStatus" class="w-full appearance-none bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl pl-3 pr-8 py-2.5 text-xs font-semibold text-gray-700 dark:text-gray-300 outline-none focus:border-indigo-500 cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="Sesuai Jadwal" <?= (isset($_GET['status']) && $_GET['status'] === 'Sesuai Jadwal') ? 'selected' : ''; ?>>Sesuai Jadwal</option>
                    <option value="Terhambat" <?= (isset($_GET['status']) && $_GET['status'] === 'Terhambat') ? 'selected' : ''; ?>>Terhambat</option>
                    <option value="Selesai" <?= (isset($_GET['status']) && $_GET['status'] === 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                </select>
                <i class="fa-solid fa-chevron-down text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 text-[10px] pointer-events-none"></i>
            </div>

            <!-- Export Dropdown -->
            <div class="relative">
                <button type="button" id="btnExportDropdown" class="bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 px-3.5 py-2.5 rounded-xl text-xs font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition flex items-center gap-2">
                    <i class="fa-solid fa-arrow-up-right-from-square text-indigo-600 dark:text-indigo-400 text-xs"></i>
                    <span>Ekspor Data</span>
                    <i class="fa-solid fa-chevron-down text-gray-400 text-[10px]"></i>
                </button>
                <div id="exportMenu" class="hidden absolute right-0 mt-2 w-52 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-lg py-1.5 z-40">
                    <a href="<?= BASEURL; ?>/project/reportPrint" target="_blank" class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-semibold text-gray-700 dark:text-gray-200 hover:bg-indigo-50 dark:hover:bg-slate-700 hover:text-indigo-600 transition">
                        <i class="fa-solid fa-file-pdf text-rose-500 text-sm"></i>
                        <span>Ekspor Laporan PDF</span>
                    </a>
                    <a href="<?= BASEURL; ?>/project/exportCsv" class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-semibold text-gray-700 dark:text-gray-200 hover:bg-emerald-50 dark:hover:bg-slate-700 hover:text-emerald-600 transition">
                        <i class="fa-solid fa-file-csv text-emerald-600 text-sm"></i>
                        <span>Unduh File CSV</span>
                    </a>
                </div>
            </div>

            <!-- Reset Filter Button -->
            <button type="button" id="btnResetFilters" class="hidden px-2.5 py-2 text-xs font-semibold text-rose-600 hover:text-rose-700 dark:text-rose-400 transition" title="Bersihkan filter">
                <i class="fa-solid fa-rotate-left mr-1"></i> Reset
            </button>
        </div>
    </div>
</div>

<!-- Project Table Card -->
<div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 shadow-2xs overflow-hidden transition-colors">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[850px]">
            <thead>
                <tr class="bg-gray-50/80 dark:bg-slate-800/90 border-b border-gray-200 dark:border-slate-700 text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    <th class="py-3 px-3 w-12 text-center text-gray-400">No.</th>
                    <th class="py-3 px-4">Nama Project</th>
                    <th class="py-3 px-4">Klien</th>
                    <th class="py-3 px-4">Tenggat Waktu</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4">Progres</th>
                    <th class="py-3 px-4">Tim & Leader</th>
                    <?php if(isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') : ?>
                    <th class="py-3 px-4 text-right pr-5">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700/60" id="projectTableBody">
                <?php if(empty($data['projects'])) : ?>
                    <tr id="emptyProjectRow">
                        <td colspan="<?= (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') ? '8' : '7'; ?>" class="py-16 text-center">
                            <div class="max-w-xs mx-auto text-center">
                                <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-slate-700 text-gray-400 flex items-center justify-center mx-auto mb-3 text-lg">
                                    <i class="fa-solid fa-folder-open"></i>
                                </div>
                                <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200">Belum ada project</h4>
                                <p class="text-xs text-gray-400 mt-1">Belum ada data project yang terdaftar.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach($data['projects'] as $index => $p) : 
                    $st = strtolower(trim($p['status']));
                    if ($st === 'sesuai jadwal') {
                        $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200/60 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/60';
                        $dotClass = 'bg-emerald-500';
                    } elseif ($st === 'terhambat') {
                        $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200/60 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800/60';
                        $dotClass = 'bg-rose-500';
                    } elseif ($st === 'selesai') {
                        $badgeClass = 'bg-blue-50 text-blue-700 border-blue-200/60 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800/60';
                        $dotClass = 'bg-blue-500';
                    } else {
                        $badgeClass = 'bg-gray-50 text-gray-700 border-gray-200/60 dark:bg-slate-800 dark:text-gray-300 dark:border-slate-700';
                        $dotClass = 'bg-gray-400';
                    }

                    $progress = (int)round($p['avg_progress'] ?? 0);
                    $barColor = 'bg-rose-500';
                    $textColor = 'text-rose-600 dark:text-rose-400';
                    if ($progress >= 70) {
                        $barColor = 'bg-emerald-500';
                        $textColor = 'text-emerald-600 dark:text-emerald-400';
                    } elseif ($progress >= 40) {
                        $barColor = 'bg-amber-500';
                        $textColor = 'text-amber-600 dark:text-amber-400';
                    }
                ?>
                <tr class="project-row group hover:bg-gray-50/60 dark:hover:bg-slate-700/20 transition-colors"
                    data-id="<?= $p['id']; ?>"
                    data-name="<?= strtolower(htmlspecialchars($p['name'], ENT_QUOTES)); ?>"
                    data-client="<?= strtolower(htmlspecialchars($p['client'], ENT_QUOTES)); ?>"
                    data-status="<?= htmlspecialchars($p['status'], ENT_QUOTES); ?>"
                    data-leader="<?= strtolower(htmlspecialchars($p['leader_name'] ?? '', ENT_QUOTES)); ?>"
                    data-members="<?= strtolower(htmlspecialchars($p['member_names'] ?? '', ENT_QUOTES)); ?>">
                    
                    <!-- Kolom Nomor -->
                    <td class="py-3.5 px-3 w-12 text-center text-xs text-gray-400 dark:text-gray-500 font-semibold row-number"><?= $index + 1; ?></td>

                    <!-- Nama Project -->
                    <td class="py-3.5 px-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-gray-100 dark:bg-slate-700/60 text-gray-600 dark:text-gray-300 flex items-center justify-center text-sm flex-shrink-0">
                                <?php if($p['icon'] == 'rocket') : ?>
                                    <i class="fa-solid fa-rocket text-indigo-500"></i>
                                <?php elseif($p['icon'] == 'bank') : ?>
                                    <i class="fa-solid fa-building-columns text-amber-500"></i>
                                <?php elseif($p['icon'] == 'leaf') : ?>
                                    <i class="fa-solid fa-leaf text-emerald-500"></i>
                                <?php else : ?>
                                    <i class="fa-solid fa-code text-blue-500"></i>
                                <?php endif; ?>
                            </div>
                            <div class="min-w-0">
                                <a href="<?= BASEURL; ?>/task?project_id=<?= $p['id']; ?>" class="font-bold text-gray-900 dark:text-gray-100 text-sm tracking-tight hover:text-indigo-600 dark:hover:text-indigo-400 transition truncate block">
                                    <?= htmlspecialchars($p['name']); ?>
                                </a>
                            </div>
                        </div>
                    </td>

                    <!-- Klien -->
                    <td class="py-3.5 px-4 text-xs font-medium text-gray-600 dark:text-gray-300">
                        <span class="inline-flex items-center gap-1.5">
                            <i class="fa-regular fa-building text-gray-400 text-[11px]"></i>
                            <?= htmlspecialchars($p['client']); ?>
                        </span>
                    </td>

                    <!-- Tenggat Waktu -->
                    <td class="py-3.5 px-4 text-xs font-semibold text-gray-600 dark:text-gray-300 whitespace-nowrap">
                        <span class="inline-flex items-center gap-1.5">
                            <i class="fa-regular fa-calendar text-gray-400 text-[11px]"></i>
                            <?= date('d M Y', strtotime($p['due_date'])); ?>
                        </span>
                    </td>

                    <!-- Status -->
                    <td class="py-3.5 px-4 whitespace-nowrap">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border <?= $badgeClass; ?>">
                            <span class="w-1.5 h-1.5 rounded-full <?= $dotClass; ?>"></span>
                            <?= htmlspecialchars($p['status']); ?>
                        </span>
                    </td>

                    <!-- Progres -->
                    <td class="py-3.5 px-4">
                        <div class="w-28">
                            <div class="flex justify-between items-center text-xs font-semibold mb-1">
                                <span class="text-gray-400 text-[10px]">Tuntas</span>
                                <span class="<?= $textColor ?> font-bold text-xs"><?= $progress; ?>%</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-slate-700 h-1.5 rounded-full overflow-hidden">
                                <div class="<?= $barColor ?> h-full rounded-full transition-all duration-300" style="width: <?= $progress; ?>%"></div>
                            </div>
                        </div>
                    </td>

                    <!-- Tim & Leader -->
                    <td class="py-3.5 px-4">
                        <div class="flex items-center gap-2.5">
                            <?php if(!empty($p['leader_name'])) : 
                                $leadInitials = strtoupper(substr(trim($p['leader_name']), 0, 2));
                            ?>
                            <div class="flex items-center gap-1.5">
                                <div class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 flex items-center justify-center text-[10px] font-bold flex-shrink-0" title="Project Leader: <?= htmlspecialchars($p['leader_name']); ?>">
                                    <?= $leadInitials; ?>
                                </div>
                                <span class="text-xs font-bold text-gray-800 dark:text-gray-200 truncate max-w-[100px]"><?= htmlspecialchars($p['leader_name']); ?></span>
                            </div>
                            <?php endif; ?>

                            <?php if(!empty($p['member_names'])) : 
                                $members = array_filter(array_map('trim', explode(',', $p['member_names'])));
                                $count = count($members);
                            ?>
                            <div class="flex items-center -space-x-1 pl-1.5 <?= !empty($p['leader_name']) ? 'border-l border-gray-200 dark:border-slate-700' : '' ?>">
                                <?php foreach(array_slice($members, 0, 3) as $m) : 
                                    $mInitials = strtoupper(substr($m, 0, 2));
                                ?>
                                    <div class="w-6 h-6 rounded-full bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300 border border-white dark:border-slate-800 flex items-center justify-center text-[9px] font-bold" title="<?= htmlspecialchars($m); ?>">
                                        <?= $mInitials; ?>
                                    </div>
                                <?php endforeach; ?>
                                <?php if($count > 3) : ?>
                                    <div class="w-6 h-6 rounded-full bg-gray-200 dark:bg-slate-600 text-gray-600 dark:text-gray-300 border border-white dark:border-slate-800 flex items-center justify-center text-[9px] font-bold">
                                        +<?= $count - 3; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php elseif(empty($p['leader_name'])) : ?>
                                <span class="text-xs text-gray-400 italic">Belum ada tim</span>
                            <?php endif; ?>
                        </div>
                    </td>

                    <!-- Aksi -->
                    <?php if(isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') : ?>
                    <td class="py-3.5 px-4 text-right pr-5 whitespace-nowrap">
                        <div class="flex items-center justify-end gap-1">
                            <a href="<?= BASEURL; ?>/task?project_id=<?= $p['id']; ?>" class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition" title="Lihat Papan Tugas">
                                <i class="fa-solid fa-list-check text-xs"></i>
                            </a>
                            <button type="button" class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition btnEditProyek" data-id="<?= $p['id']; ?>" title="Edit Project">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                            </button>
                            <button type="button" class="p-1.5 text-gray-400 hover:text-rose-600 dark:hover:text-rose-400 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition" onclick="showDeleteModal('<?= BASEURL; ?>/project/delete/<?= $p['id']; ?>', '<?= addslashes($p['name']); ?>')" title="Hapus Project">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </div>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 border-t border-gray-100 dark:border-slate-700/80 transition-colors" id="projectPaginationWrapper">
        <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500 dark:text-gray-400 font-medium">
            <span id="projectPaginationInfo">Menampilkan <?= count($data['projects']); ?> project</span>
            <div class="flex items-center gap-2 pl-3 border-l border-gray-200 dark:border-slate-700">
                <span class="text-xs text-gray-400 dark:text-gray-500 font-semibold">Tampilkan:</span>
                <select id="projectPageSize" class="bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg px-2 py-1 text-xs font-semibold text-gray-700 dark:text-gray-300 outline-none focus:border-indigo-500 cursor-pointer">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="all">Semua</option>
                </select>
            </div>
        </div>

        <div class="flex items-center gap-1" id="projectPaginationControls">
            <!-- Dynamically populated buttons -->
        </div>
    </div>
</div>

<!-- Modal Form Project -->
<div id="projectModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-40 backdrop-blur-sm modal-overlay opacity-0 transition-opacity" aria-hidden="true" onclick="toggleProjectModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left shadow-2xl transform modal-content sm:my-8 sm:align-middle sm:max-w-xl sm:w-full transition-colors border border-gray-100 dark:border-slate-700">
            <form action="<?= BASEURL; ?>/project/add" method="post" id="projectForm">
                <input type="hidden" name="id" id="project_id">
                <div class="p-6 md:p-8">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 tracking-tight" id="modalTitle">Tambah Project Baru</h3>
                            <p class="text-gray-400 dark:text-gray-500 text-xs mt-0.5">Lengkapi informasi detail project di bawah ini.</p>
                        </div>
                        <button type="button" onclick="toggleProjectModal()" class="w-8 h-8 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700 flex items-center justify-center transition">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 ml-0.5">Nama Project</label>
                            <input type="text" name="name" id="name" required class="w-full bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs font-semibold text-gray-800 dark:text-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition" placeholder="Masukkan nama project...">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="client" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 ml-0.5">Klien</label>
                                <input type="text" name="client" id="client" required class="w-full bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs font-semibold text-gray-800 dark:text-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition" placeholder="Masukkan nama klien...">
                            </div>
                            <div>
                                <label for="due_date" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 ml-0.5">Tenggat Waktu</label>
                                <input type="date" name="due_date" id="due_date" required class="w-full bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs font-semibold text-gray-800 dark:text-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition cursor-pointer" onclick="this.showPicker()">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="status" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 ml-0.5">Status Utama</label>
                                <div class="select-container" id="statusSelectContainer">
                                    <div class="select-trigger">
                                        <span class="select-trigger-text text-gray-400 dark:text-gray-500">Pilih Status...</span>
                                        <i class="fa-solid fa-chevron-down select-trigger-icon flex items-center justify-center text-xs"></i>
                                    </div>
                                    <div class="select-options">
                                        <div class="select-option" data-value="Sesuai Jadwal">Sesuai Jadwal</div>
                                        <div class="select-option" data-value="Terhambat">Terhambat</div>
                                        <div class="select-option" data-value="Selesai">Selesai</div>
                                    </div>
                                </div>
                                <input type="hidden" name="status" id="status" value="Sesuai Jadwal">
                            </div>
                            <div>
                                <label for="icon" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 ml-0.5">Ikon Representasi</label>
                                <div class="select-container" id="iconSelectContainer">
                                    <div class="select-trigger">
                                        <span class="select-trigger-text text-gray-400 dark:text-gray-500">Pilih Ikon...</span>
                                        <i class="fa-solid fa-chevron-down select-trigger-icon flex items-center justify-center text-xs"></i>
                                    </div>
                                    <div class="select-options">
                                        <div class="select-option" data-value="rocket">🚀 Roket</div>
                                        <div class="select-option" data-value="bank">🏦 Finansial</div>
                                        <div class="select-option" data-value="leaf">🌿 Lingkungan</div>
                                        <div class="select-option" data-value="code">💻 Teknologi</div>
                                    </div>
                                </div>
                                <input type="hidden" name="icon" id="icon" value="rocket">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Project Leader (Single Select with Search) -->
                            <div>
                                <label for="leader_id" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 ml-0.5">Project Leader</label>
                                <div class="select-container" id="leaderSelectContainer">
                                    <div class="select-trigger" data-placeholder="Pilih Leader...">
                                        <span class="select-trigger-text text-gray-400 dark:text-gray-500">Pilih Leader...</span>
                                        <i class="fa-solid fa-chevron-down select-trigger-icon flex items-center justify-center text-xs"></i>
                                    </div>
                                    <div class="select-options">
                                        <!-- Search Box Header -->
                                        <div class="p-2 border-b border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 sticky top-0 z-20">
                                            <div class="relative">
                                                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                                                <input type="text" class="select-search-input w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl pl-8 pr-3 py-1.5 text-xs font-semibold text-gray-800 dark:text-gray-200 outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Cari nama leader...">
                                            </div>
                                        </div>
                                        <div class="select-options-list">
                                            <?php foreach($data['team'] as $m) : ?>
                                                <div class="select-option flex items-center justify-between gap-3" data-value="<?= $m['id']; ?>" data-name="<?= htmlspecialchars($m['name'], ENT_QUOTES); ?>">
                                                    <div class="flex items-center gap-2.5 min-w-0">
                                                        <div class="w-6 h-6 rounded-full bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 flex items-center justify-center text-[10px] font-bold flex-shrink-0">
                                                            <?= strtoupper(substr($m['name'], 0, 2)); ?>
                                                        </div>
                                                        <div class="flex flex-col min-w-0">
                                                            <span class="font-bold text-xs truncate leading-tight text-gray-800 dark:text-gray-200"><?= htmlspecialchars($m['name']); ?></span>
                                                            <span class="text-[10px] text-gray-400 dark:text-gray-500 truncate"><?= htmlspecialchars($m['role_label'] ?? $m['role'] ?? ''); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="select-no-results py-4 text-center text-xs text-gray-400 font-medium" style="display: none;">Tidak ada leader yang cocok</div>
                                    </div>
                                </div>
                                <input type="hidden" name="leader_id" id="leader_id">
                            </div>

                            <!-- Anggota Tim Lainnya (Multi-Select with Search) -->
                            <div>
                                <div class="flex items-center justify-between mb-1.5 ml-0.5">
                                    <label for="member_ids" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Anggota Tim Lainnya</label>
                                    <span class="select-count-badge text-[10px] font-bold text-indigo-600 dark:text-indigo-400" style="display: none;">0 dipilih</span>
                                </div>
                                <div class="select-container" id="memberSelectContainer">
                                    <div class="select-trigger" data-placeholder="Pilih Anggota...">
                                        <span class="select-trigger-text text-gray-400 dark:text-gray-500">Pilih Anggota...</span>
                                        <i class="fa-solid fa-chevron-down select-trigger-icon flex items-center justify-center text-xs"></i>
                                    </div>
                                    <div class="select-options">
                                        <!-- Search Box & Quick Actions Header -->
                                        <div class="p-2 border-b border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 sticky top-0 z-20 space-y-1.5">
                                            <div class="relative">
                                                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                                                <input type="text" class="select-search-input w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl pl-8 pr-3 py-1.5 text-xs font-semibold text-gray-800 dark:text-gray-200 outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Cari nama anggota...">
                                            </div>
                                            <div class="flex items-center justify-between text-[11px] px-1 font-bold">
                                                <button type="button" class="select-all-btn text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                                                    <i class="fa-solid fa-check-double text-[10px]"></i> Pilih Semua
                                                </button>
                                                <button type="button" class="select-clear-btn text-gray-400 hover:text-rose-500 hover:underline flex items-center gap-1">
                                                    <i class="fa-solid fa-rotate-left text-[10px]"></i> Hapus
                                                </button>
                                            </div>
                                        </div>
                                        <div class="select-options-list">
                                            <?php foreach($data['team'] as $m) : ?>
                                                <div class="select-option flex justify-between items-center gap-3" data-value="<?= $m['id']; ?>" data-name="<?= htmlspecialchars($m['name'], ENT_QUOTES); ?>">
                                                    <div class="flex items-center gap-2.5 min-w-0">
                                                        <div class="w-6 h-6 rounded-full bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 flex items-center justify-center text-[10px] font-bold flex-shrink-0">
                                                            <?= strtoupper(substr($m['name'], 0, 2)); ?>
                                                        </div>
                                                        <div class="flex flex-col min-w-0">
                                                            <span class="font-bold text-xs truncate leading-tight text-gray-800 dark:text-gray-200"><?= htmlspecialchars($m['name']); ?></span>
                                                            <span class="text-[10px] text-gray-400 dark:text-gray-500 truncate"><?= htmlspecialchars($m['role_label'] ?? $m['role'] ?? ''); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="check-icon opacity-0 bg-indigo-600 text-white rounded-md p-0.5 flex-shrink-0 transition-opacity">
                                                        <i class="fa-solid fa-check text-[10px] w-3 h-3 flex items-center justify-center"></i>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="select-no-results py-4 text-center text-xs text-gray-400 font-medium" style="display: none;">Tidak ada anggota yang cocok</div>
                                    </div>
                                </div>
                                <input type="hidden" name="member_ids" id="member_ids">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 ml-0.5">Warna Aksen</label>
                            <div class="flex items-center gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="status_color" value="text-indigo-600" class="hidden peer" checked>
                                    <div class="w-8 h-8 rounded-lg bg-indigo-600 peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-indigo-600 transition"></div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="status_color" value="text-purple-600" class="hidden peer">
                                    <div class="w-8 h-8 rounded-lg bg-purple-600 peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-purple-600 transition"></div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="status_color" value="text-emerald-600" class="hidden peer">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-600 peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-emerald-600 transition"></div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="status_color" value="text-amber-600" class="hidden peer">
                                    <div class="w-8 h-8 rounded-lg bg-amber-600 peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-amber-600 transition"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-8 pt-5 border-t border-gray-100 dark:border-slate-700/80">
                        <button type="button" onclick="toggleProjectModal()" class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-300 font-semibold text-xs hover:bg-gray-50 dark:hover:bg-slate-700 transition">Batal</button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-semibold text-xs hover:bg-indigo-700 transition shadow-sm shadow-indigo-200 dark:shadow-none">Simpan Project</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const projectModal = document.getElementById('projectModal');
    const projectForm = document.getElementById('projectForm');
    const modalTitle = document.getElementById('modalTitle');

    function toggleProjectModal() {
        if (projectModal.classList.contains('hidden')) {
            projectModal.classList.remove('hidden');
            setTimeout(() => {
                projectModal.classList.add('modal-active');
                document.body.classList.add('overflow-hidden');
            }, 10);
        } else {
            projectModal.classList.remove('modal-active');
            setTimeout(() => {
                projectModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                projectForm.reset();
                document.getElementById('project_id').value = '';
            }, 300);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Teleport action buttons to header
        const actionArea = document.getElementById('header-action-area');
        const actionContent = document.getElementById('project-action-content');
        if (actionArea && actionContent) {
            actionArea.innerHTML = '';
            while (actionContent.firstChild) {
                actionArea.appendChild(actionContent.firstChild);
            }
        }

        // Export Dropdown Toggle
        const btnExportDropdown = document.getElementById('btnExportDropdown');
        const exportMenu = document.getElementById('exportMenu');
        if (btnExportDropdown && exportMenu) {
            btnExportDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
                exportMenu.classList.toggle('hidden');
            });
            document.addEventListener('click', function(e) {
                if (!exportMenu.contains(e.target) && e.target !== btnExportDropdown) {
                    exportMenu.classList.add('hidden');
                }
            });
        }

        function syncMemberExclusion() {
            const leaderId = document.getElementById('leader_id').value;
            const memberIdsInput = document.getElementById('member_ids');
            const memberContainer = document.getElementById('memberSelectContainer');
            if (!memberContainer) return;
            
            const memberOptions = memberContainer.querySelectorAll('.select-option');
            
            // Hide leader from member options
            memberOptions.forEach(opt => {
                const val = opt.getAttribute('data-value');
                if (val && val === leaderId) {
                    opt.classList.add('excluded-member', 'hidden');
                    opt.style.display = 'none';
                    // If this leader was also in members, unselect it
                    if (opt.classList.contains('selected')) {
                        opt.classList.remove('selected');
                        try {
                            let members = JSON.parse(memberIdsInput.value || '[]');
                            members = members.filter(id => id !== leaderId);
                            memberIdsInput.value = JSON.stringify(members);
                            initMultiSelect('memberSelectContainer', 'member_ids');
                        } catch(e) {}
                    }
                } else {
                    opt.classList.remove('excluded-member', 'hidden');
                    opt.style.display = '';
                }
            });
        }

        // Initialize Custom Selects
        if (typeof initCustomSelect === 'function') {
            initCustomSelect('statusSelectContainer', 'status');
            initCustomSelect('iconSelectContainer', 'icon');
            initCustomSelect('leaderSelectContainer', 'leader_id', () => {
                syncMemberExclusion();
            });
        }
        if (typeof initMultiSelect === 'function') {
            initMultiSelect('memberSelectContainer', 'member_ids');
        }

        const btnsTambah = document.querySelectorAll('.btnTambahProyek');
        btnsTambah.forEach(btn => {
            btn.addEventListener('click', function() {
                modalTitle.innerText = 'Tambah Project Baru';
                projectForm.setAttribute('action', '<?= BASEURL; ?>/project/add');
                
                document.getElementById('status').value = 'Sesuai Jadwal';
                document.getElementById('icon').value = 'rocket';
                document.getElementById('leader_id').value = '';
                
                if (typeof initCustomSelect === 'function') {
                    initCustomSelect('statusSelectContainer', 'status');
                    initCustomSelect('iconSelectContainer', 'icon');
                    initCustomSelect('leaderSelectContainer', 'leader_id', () => syncMemberExclusion());
                }
                if (typeof initMultiSelect === 'function') {
                    initMultiSelect('memberSelectContainer', 'member_ids');
                }
                syncMemberExclusion();

                toggleProjectModal();
            });
        });

        const btnsEdit = document.querySelectorAll('.btnEditProyek');
        btnsEdit.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                modalTitle.innerText = 'Ubah Detail Project';
                projectForm.setAttribute('action', '<?= BASEURL; ?>/project/edit');
                
                fetch('<?= BASEURL; ?>/project/getEdit/' + id)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('project_id').value = data.id;
                        document.getElementById('name').value = data.name;
                        document.getElementById('client').value = data.client;
                        document.getElementById('due_date').value = data.due_date;
                        
                        document.getElementById('status').value = data.status;
                        document.getElementById('icon').value = data.icon;
                        document.getElementById('leader_id').value = data.leader_id || '';
                        
                        if (typeof initCustomSelect === 'function') {
                            initCustomSelect('statusSelectContainer', 'status');
                            initCustomSelect('iconSelectContainer', 'icon');
                            initCustomSelect('leaderSelectContainer', 'leader_id', () => syncMemberExclusion());
                        }

                        document.getElementById('member_ids').value = JSON.stringify(data.member_ids || []);
                        if (typeof initMultiSelect === 'function') {
                            initMultiSelect('memberSelectContainer', 'member_ids');
                        }
                        syncMemberExclusion();
                        
                        const radios = document.querySelectorAll('input[name="status_color"]');
                        radios.forEach(r => {
                            if (r.value === data.status_color) r.checked = true;
                        });

                        toggleProjectModal();
                    });
            });
        });

        // Initialize Filter & Table Pagination
        initProjectDataTable();
    });

    function initProjectDataTable() {
        const rows = Array.from(document.querySelectorAll('.project-row'));
        const pageSizeSelect = document.getElementById('projectPageSize');
        const infoEl = document.getElementById('projectPaginationInfo');
        const controlsEl = document.getElementById('projectPaginationControls');
        const searchInput = document.getElementById('projectSearch');
        const clearSearchBtn = document.getElementById('clearSearchBtn');
        const filterStatus = document.getElementById('filterStatus');
        const btnResetFilters = document.getElementById('btnResetFilters');
        
        let currentPage = 1;
        let pageSize = parseInt(pageSizeSelect.value) || 10;
        let filteredRows = [...rows];

        function applyFilter() {
            const query = (searchInput ? searchInput.value.trim().toLowerCase() : '');
            const selectedStatus = (filterStatus ? filterStatus.value.trim().toLowerCase() : '');

            // Show/hide clear search button
            if (clearSearchBtn) {
                if (query.length > 0) clearSearchBtn.classList.remove('hidden');
                else clearSearchBtn.classList.add('hidden');
            }

            // Check if any filter active
            const hasActiveFilter = query || selectedStatus;
            if (btnResetFilters) {
                if (hasActiveFilter) btnResetFilters.classList.remove('hidden');
                else btnResetFilters.classList.add('hidden');
            }

            filteredRows = rows.filter(row => {
                const name = row.getAttribute('data-name') || '';
                const client = row.getAttribute('data-client') || '';
                const status = (row.getAttribute('data-status') || '').toLowerCase();
                const leader = row.getAttribute('data-leader') || '';
                const members = row.getAttribute('data-members') || '';

                // Search query match
                const matchQuery = !query || name.includes(query) || client.includes(query) || leader.includes(query) || members.includes(query) || status.includes(query);
                
                // Status match
                const matchStatus = !selectedStatus || status === selectedStatus;

                return matchQuery && matchStatus;
            });

            currentPage = 1;
            render();
        }

        function render() {
            const total = filteredRows.length;
            const effectivePageSize = (pageSizeSelect.value === 'all') ? (total || 1) : pageSize;
            const totalPages = Math.max(1, Math.ceil(total / effectivePageSize));

            if (currentPage > totalPages) currentPage = totalPages;
            if (currentPage < 1) currentPage = 1;

            const startIndex = (currentPage - 1) * effectivePageSize;
            const endIndex = (pageSizeSelect.value === 'all') ? total : Math.min(startIndex + effectivePageSize, total);

            // Hide all rows first
            rows.forEach(r => r.classList.add('hidden'));

            // Show filtered rows in current page
            for (let i = startIndex; i < endIndex; i++) {
                if (filteredRows[i]) {
                    filteredRows[i].classList.remove('hidden');
                    const numCell = filteredRows[i].querySelector('.row-number');
                    if (numCell) {
                        numCell.innerText = i + 1;
                    }
                }
            }

            // Empty state row
            let noMatchRow = document.getElementById('noMatchProjectRow');
            if (total === 0 && rows.length > 0) {
                if (!noMatchRow) {
                    noMatchRow = document.createElement('tr');
                    noMatchRow.id = 'noMatchProjectRow';
                    const colSpan = <?= (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') ? '8' : '7'; ?>;
                    noMatchRow.innerHTML = `
                        <td colspan="${colSpan}" class="py-12 text-center">
                            <div class="max-w-xs mx-auto text-center">
                                <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-slate-700 text-gray-400 flex items-center justify-center mx-auto mb-2 text-sm">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </div>
                                <h4 class="text-xs font-bold text-gray-800 dark:text-gray-200">Tidak ada project yang cocok</h4>
                                <p class="text-[11px] text-gray-400 mt-0.5">Coba gunakan kata kunci pencarian atau filter yang berbeda.</p>
                            </div>
                        </td>
                    `;
                    document.getElementById('projectTableBody').appendChild(noMatchRow);
                }
                noMatchRow.classList.remove('hidden');
            } else if (noMatchRow) {
                noMatchRow.classList.add('hidden');
            }

            // Update info text
            if (total === 0) {
                infoEl.innerText = 'Menampilkan 0 project';
            } else if (pageSizeSelect.value === 'all' || total <= effectivePageSize) {
                infoEl.innerText = `Menampilkan ${total} project`;
            } else {
                infoEl.innerText = `Menampilkan ${startIndex + 1} - ${endIndex} dari ${total} project`;
            }

            // Render pagination buttons
            controlsEl.innerHTML = '';
            if (totalPages <= 1 && total <= effectivePageSize) {
                return;
            }

            // Prev Button
            const prevBtn = document.createElement('button');
            prevBtn.type = 'button';
            prevBtn.className = `px-2.5 py-1 rounded-lg border text-xs font-semibold transition flex items-center gap-1 ${currentPage === 1 ? 'border-gray-200 dark:border-slate-800 text-gray-300 dark:text-gray-600 cursor-not-allowed opacity-50' : 'border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700'}`;
            prevBtn.disabled = (currentPage === 1);
            prevBtn.innerHTML = '<i class="fa-solid fa-chevron-left text-[10px]"></i> <span class="hidden sm:inline">Sebelumnya</span>';
            prevBtn.onclick = () => { if (currentPage > 1) { currentPage--; render(); } };
            controlsEl.appendChild(prevBtn);

            // Page numbers
            for (let p = 1; p <= totalPages; p++) {
                if (totalPages > 6 && Math.abs(p - currentPage) > 2 && p !== 1 && p !== totalPages) {
                    if (p === 2 || p === totalPages - 1) {
                        const dots = document.createElement('span');
                        dots.className = 'px-1 text-xs text-gray-400 font-bold';
                        dots.innerText = '...';
                        controlsEl.appendChild(dots);
                    }
                    continue;
                }

                const pageBtn = document.createElement('button');
                pageBtn.type = 'button';
                pageBtn.className = `min-w-[28px] h-7 px-2 rounded-lg text-xs font-bold transition ${p === currentPage ? 'bg-indigo-600 text-white shadow-xs' : 'border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700'}`;
                pageBtn.innerText = p;
                pageBtn.onclick = () => { currentPage = p; render(); };
                controlsEl.appendChild(pageBtn);
            }

            // Next Button
            const nextBtn = document.createElement('button');
            nextBtn.type = 'button';
            nextBtn.className = `px-2.5 py-1 rounded-lg border text-xs font-semibold transition flex items-center gap-1 ${currentPage === totalPages ? 'border-gray-200 dark:border-slate-800 text-gray-300 dark:text-gray-600 cursor-not-allowed opacity-50' : 'border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700'}`;
            nextBtn.disabled = (currentPage === totalPages);
            nextBtn.innerHTML = '<span class="hidden sm:inline">Selanjutnya</span> <i class="fa-solid fa-chevron-right text-[10px]"></i>';
            nextBtn.onclick = () => { if (currentPage < totalPages) { currentPage++; render(); } };
            controlsEl.appendChild(nextBtn);
        }

        pageSizeSelect.addEventListener('change', function() {
            pageSize = (this.value === 'all') ? rows.length : parseInt(this.value);
            currentPage = 1;
            render();
        });

        if (searchInput) {
            searchInput.addEventListener('input', applyFilter);
        }

        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', function() {
                searchInput.value = '';
                applyFilter();
                searchInput.focus();
            });
        }

        if (filterStatus) {
            filterStatus.addEventListener('change', applyFilter);
        }

        if (btnResetFilters) {
            btnResetFilters.addEventListener('click', function() {
                if (searchInput) searchInput.value = '';
                if (filterStatus) filterStatus.value = '';
                applyFilter();
            });
        }

        applyFilter();
    }
</script>
