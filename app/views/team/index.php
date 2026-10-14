<?php
// Compute summary counts
$totalTeam = count($data['team'] ?? []);
$adminCount = 0;
$memberCount = 0;
$activeCount = 0;
$departments = [];

foreach (($data['team'] ?? []) as $m) {
    if (($m['role'] ?? '') === 'admin') $adminCount++;
    else $memberCount++;
    if (strtoupper($m['status'] ?? 'AKTIF') === 'AKTIF') $activeCount++;
    if (!empty($m['department'])) $departments[$m['department']] = true;
}
ksort($departments);
?>

<!-- Header Actions Teleport Content -->
<div id="team-action-content" class="hidden">
    <div class="flex items-center gap-2.5">
        <!-- Direct PDF Export Button -->
        <a href="<?= BASEURL; ?>/team/exportPdf" target="_blank" class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-200 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-50 dark:hover:bg-slate-700 transition flex items-center gap-2 shadow-2xs" title="Ekspor dan Cetak Laporan Tim ke PDF">
            <i class="fa-solid fa-file-pdf text-rose-500"></i>
            <span>Ekspor PDF</span>
        </a>
        <!-- Add Member Button -->
        <button type="button" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition flex items-center justify-center gap-2 shadow-sm shadow-indigo-200 dark:shadow-none btnTambahTim">
            <i class="fa-solid fa-user-plus text-xs"></i>
            <span>Tambah Anggota</span>
        </button>
    </div>
</div>

<!-- Team Metric Stats Strip -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 mb-6">
    <!-- Card 1: Total Anggota -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-4 shadow-2xs hover:border-indigo-200 dark:hover:border-slate-600 transition-all duration-300 group">
        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-5xl sm:text-6xl text-indigo-600/10 dark:text-indigo-400/15 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300 pointer-events-none select-none">
            <i class="fa-solid fa-users"></i>
        </div>
        <div class="relative z-10">
            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 block">Total Anggota</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100" id="statTotal"><?= $totalTeam; ?></span>
                <span class="text-[11px] font-medium text-gray-400 dark:text-gray-500">orang</span>
            </div>
        </div>
    </div>

    <!-- Card 2: Administrator -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-4 shadow-2xs hover:border-purple-200 dark:hover:border-slate-600 transition-all duration-300 group">
        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-5xl sm:text-6xl text-purple-600/10 dark:text-purple-400/15 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300 pointer-events-none select-none">
            <i class="fa-solid fa-shield-halved"></i>
        </div>
        <div class="relative z-10">
            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 block">Administrator</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-bold text-purple-700 dark:text-purple-300"><?= $adminCount; ?></span>
                <span class="text-[11px] font-medium text-purple-600/70 dark:text-purple-400/70">Akses Penuh</span>
            </div>
        </div>
    </div>

    <!-- Card 3: Member -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-4 shadow-2xs hover:border-blue-200 dark:hover:border-slate-600 transition-all duration-300 group">
        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-5xl sm:text-6xl text-blue-600/10 dark:text-blue-400/15 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300 pointer-events-none select-none">
            <i class="fa-solid fa-user-lock"></i>
        </div>
        <div class="relative z-10">
            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 block">Member</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-bold text-blue-700 dark:text-blue-300"><?= $memberCount; ?></span>
                <span class="text-[11px] font-medium text-blue-600/70 dark:text-blue-400/70">Akses Terbatas</span>
            </div>
        </div>
    </div>

    <!-- Card 4: Status Aktif -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-4 shadow-2xs hover:border-emerald-200 dark:hover:border-slate-600 transition-all duration-300 group">
        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-5xl sm:text-6xl text-emerald-600/10 dark:text-emerald-400/15 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300 pointer-events-none select-none">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="relative z-10">
            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 block">Status Aktif</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-bold text-emerald-700 dark:text-emerald-300"><?= $activeCount; ?></span>
                <span class="text-[11px] font-medium text-emerald-600/70 dark:text-emerald-400/70">Akun Berjalan</span>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filtering Toolbar -->
<div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-4 mb-5 shadow-2xs transition-colors">
    <div class="flex flex-col lg:flex-row gap-3.5 items-stretch lg:items-center justify-between">
        <!-- Search Input -->
        <div class="relative flex-grow max-w-xl">
            <input type="text" id="teamSearch" placeholder="Cari nama, email, jabatan, atau departemen..." 
                class="w-full bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl pl-10 pr-9 py-2.5 text-sm text-gray-800 dark:text-gray-200 placeholder-gray-400 focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition">
            <i class="fa-solid fa-magnifying-glass text-gray-400 dark:text-gray-500 absolute left-3.5 top-1/2 -translate-y-1/2 text-sm pointer-events-none"></i>
            <button type="button" id="clearSearchBtn" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Filter Controls & Export Group -->
        <div class="flex flex-wrap items-center gap-2.5">
            <!-- Filter Hak Akses -->
            <div class="relative min-w-[140px]">
                <select id="filterRole" class="w-full appearance-none bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl pl-3 pr-8 py-2.5 text-xs font-semibold text-gray-700 dark:text-gray-300 outline-none focus:border-indigo-500 cursor-pointer">
                    <option value="">Semua Hak Akses</option>
                    <option value="admin">Akses Penuh (Admin)</option>
                    <option value="member">Akses Terbatas (Member)</option>
                </select>
                <i class="fa-solid fa-chevron-down text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 text-[10px] pointer-events-none"></i>
            </div>

            <!-- Filter Status -->
            <div class="relative min-w-[120px]">
                <select id="filterStatus" class="w-full appearance-none bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl pl-3 pr-8 py-2.5 text-xs font-semibold text-gray-700 dark:text-gray-300 outline-none focus:border-indigo-500 cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="AKTIF">Aktif</option>
                    <option value="NONAKTIF">Nonaktif</option>
                </select>
                <i class="fa-solid fa-chevron-down text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 text-[10px] pointer-events-none"></i>
            </div>

            <?php if (!empty($departments)) : ?>
            <!-- Filter Departemen -->
            <div class="relative min-w-[130px]">
                <select id="filterDept" class="w-full appearance-none bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl pl-3 pr-8 py-2.5 text-xs font-semibold text-gray-700 dark:text-gray-300 outline-none focus:border-indigo-500 cursor-pointer">
                    <option value="">Semua Departemen</option>
                    <?php foreach ($departments as $deptName => $_) : ?>
                        <option value="<?= htmlspecialchars($deptName, ENT_QUOTES); ?>"><?= htmlspecialchars($deptName); ?></option>
                    <?php endforeach; ?>
                </select>
                <i class="fa-solid fa-chevron-down text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 text-[10px] pointer-events-none"></i>
            </div>
            <?php endif; ?>

            <!-- Export Dropdown -->
            <div class="relative">
                <button type="button" id="btnExportDropdown" class="bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 px-3.5 py-2.5 rounded-xl text-xs font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition flex items-center gap-2">
                    <i class="fa-solid fa-arrow-up-right-from-square text-indigo-600 dark:text-indigo-400 text-xs"></i>
                    <span>Ekspor Data</span>
                    <i class="fa-solid fa-chevron-down text-gray-400 text-[10px]"></i>
                </button>
                <div id="exportMenu" class="hidden absolute right-0 mt-2 w-52 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-lg py-1.5 z-40">
                    <a href="<?= BASEURL; ?>/team/exportPdf" target="_blank" class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-semibold text-gray-700 dark:text-gray-200 hover:bg-indigo-50 dark:hover:bg-slate-700 hover:text-indigo-600 transition">
                        <i class="fa-solid fa-file-pdf text-rose-500 text-sm"></i>
                        <span>Ekspor Laporan PDF</span>
                    </a>
                    <a href="<?= BASEURL; ?>/team/exportCsv" class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-semibold text-gray-700 dark:text-gray-200 hover:bg-emerald-50 dark:hover:bg-slate-700 hover:text-emerald-600 transition">
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

<!-- Team Table Card -->
<div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 shadow-2xs overflow-hidden transition-colors">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/80 dark:bg-slate-800/90 border-b border-gray-200 dark:border-slate-700 text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    <th class="py-3 px-3 w-12 text-center text-gray-400">No.</th>
                    <th class="py-3 px-4">Profil Pengguna</th>
                    <th class="py-3 px-4">Email</th>
                    <th class="py-3 px-4">Jabatan / Profesi</th>
                    <th class="py-3 px-4">Hak Akses Sistem</th>
                    <th class="py-3 px-4">Departemen</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4">Terakhir Aktif</th>
                    <th class="py-3 px-4 text-right pr-5">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700/60" id="teamTableBody">
                <?php if(empty($data['team'])) : ?>
                    <tr id="emptyTeamRow">
                        <td colspan="9" class="py-16 text-center">
                            <div class="max-w-xs mx-auto text-center">
                                <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-slate-700 text-gray-400 flex items-center justify-center mx-auto mb-3 text-lg">
                                    <i class="fa-solid fa-user-group"></i>
                                </div>
                                <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200">Belum ada anggota tim</h4>
                                <p class="text-xs text-gray-400 mt-1">Gunakan tombol "Tambah Anggota" di atas untuk menambahkan anggota baru.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach($data['team'] as $member) : 
                    $isAdmin = ($member['role'] === 'admin');
                    $isActive = strtoupper($member['status'] ?? 'AKTIF') === 'AKTIF';
                    $roleLabel = $member['role_label'] ?? $member['role'];
                    $initials = strtoupper(substr($member['name'], 0, 2));
                    $isSelf = ($member['id'] == $_SESSION['user']['id']);
                ?>
                <tr class="team-row group hover:bg-gray-50/70 dark:hover:bg-slate-700/30 transition-colors"
                    data-name="<?= strtolower(htmlspecialchars($member['name'], ENT_QUOTES)); ?>"
                    data-email="<?= strtolower(htmlspecialchars($member['email'], ENT_QUOTES)); ?>"
                    data-dept="<?= strtolower(htmlspecialchars($member['department'] ?? '', ENT_QUOTES)); ?>"
                    data-role="<?= strtolower(htmlspecialchars($member['role'] ?? '', ENT_QUOTES)); ?>"
                    data-role-label="<?= strtolower(htmlspecialchars($roleLabel, ENT_QUOTES)); ?>"
                    data-status="<?= strtoupper($member['status'] ?? 'AKTIF'); ?>">
                    
                    <!-- Nomor -->
                    <td class="py-3.5 px-3 text-center text-xs font-semibold text-gray-400 row-number">
                        <?= $idx + 1; ?>
                    </td>

                    <!-- Profil Pengguna -->
                    <td class="py-3.5 px-4">
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($member['name']); ?>&background=random" 
                                onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?= urlencode($member['name']); ?>&background=E2E8F0&color=475569';"
                                class="w-9 h-9 rounded-full border border-gray-200 dark:border-slate-700 object-cover shrink-0" 
                                alt="<?= htmlspecialchars($member['name']); ?>">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-gray-900 dark:text-gray-100 text-sm truncate"><?= htmlspecialchars($member['name']); ?></span>
                                    <?php if($isSelf) : ?>
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200/60 dark:border-indigo-800/40 shrink-0">Anda</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- Email -->
                    <td class="py-3.5 px-4">
                        <span class="text-xs text-gray-600 dark:text-gray-400 font-mono"><?= htmlspecialchars($member['email']); ?></span>
                    </td>

                    <!-- Jabatan / Profesi -->
                    <td class="py-3.5 px-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-700 dark:bg-slate-700/60 dark:text-slate-300 border border-slate-200/70 dark:border-slate-600/60">
                            <?= htmlspecialchars($roleLabel); ?>
                        </span>
                    </td>

                    <!-- Hak Akses Sistem -->
                    <td class="py-3.5 px-4">
                        <?php if($isAdmin) : ?>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-purple-50 text-purple-700 dark:bg-purple-950/40 dark:text-purple-300 border border-purple-200/80 dark:border-purple-800/60 shadow-2xs">
                                <i class="fa-solid fa-shield-halved text-[11px] text-purple-600 dark:text-purple-400"></i>
                                <span>Akses Penuh (Admin)</span>
                            </span>
                        <?php else : ?>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-700 dark:bg-slate-700/60 dark:text-gray-300 border border-gray-200 dark:border-slate-600">
                                <i class="fa-solid fa-user-lock text-[11px] text-gray-400 dark:text-slate-400"></i>
                                <span>Akses Terbatas (Member)</span>
                            </span>
                        <?php endif; ?>
                    </td>

                    <!-- Departemen -->
                    <td class="py-3.5 px-4">
                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300"><?= htmlspecialchars($member['department'] ?? '-'); ?></span>
                    </td>

                    <!-- Status -->
                    <td class="py-3.5 px-4">
                        <?php if($isActive) : ?>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200/70 dark:border-emerald-800/50">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                <span>Aktif</span>
                            </span>
                        <?php else : ?>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-300 border border-rose-200/70 dark:border-rose-800/50">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                <span>Nonaktif</span>
                            </span>
                        <?php endif; ?>
                    </td>

                    <!-- Terakhir Aktif -->
                    <td class="py-3.5 px-4">
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            <?= !empty($member['last_active']) ? date('d M H:i', strtotime($member['last_active'])) : '-'; ?>
                        </span>
                    </td>

                    <!-- Aksi -->
                    <td class="py-3.5 px-4 text-right pr-5">
                        <div class="flex items-center justify-end gap-1">
                            <button type="button" class="p-1.5 text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-amber-50 dark:hover:bg-slate-700 rounded-lg transition btnResetPassword" 
                                title="Reset Kata Sandi" 
                                data-id="<?= $member['id']; ?>" 
                                data-name="<?= htmlspecialchars($member['name'], ENT_QUOTES); ?>" 
                                data-email="<?= htmlspecialchars($member['email'], ENT_QUOTES); ?>">
                                <i class="fa-solid fa-key text-xs"></i>
                            </button>
                            <button type="button" class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-slate-700 rounded-lg transition btnEditTim" 
                                title="Ubah Detail" 
                                data-id="<?= $member['id']; ?>">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                            </button>
                            <?php if(!$isSelf) : ?>
                            <button type="button" class="p-1.5 text-gray-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-slate-700 rounded-lg transition" 
                                title="Hapus Anggota" 
                                onclick="showDeleteModal('<?= BASEURL; ?>/team/delete/<?= $member['id']; ?>', '<?= addslashes($member['name']); ?>')">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Table Pagination Footer -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-3.5 border-t border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50 transition-colors" id="teamPaginationWrapper">
        <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
            <span id="teamPaginationInfo" class="font-medium">Menampilkan <?= $totalTeam; ?> anggota</span>
            <div class="flex items-center gap-2 pl-3 border-l border-gray-300 dark:border-slate-700">
                <span class="text-xs text-gray-400 dark:text-gray-500 font-semibold">Tampilkan:</span>
                <select id="teamPageSize" class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg px-2 py-1 text-xs font-semibold text-gray-700 dark:text-gray-300 outline-none focus:ring-1 focus:ring-indigo-500 cursor-pointer">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="all">Semua</option>
                </select>
            </div>
        </div>

        <div class="flex items-center gap-1" id="teamPaginationControls">
            <!-- Dynamically populated buttons -->
        </div>
    </div>
</div>

<!-- Modal Form Tambah/Ubah Tim -->
<div id="teamModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-xs modal-overlay opacity-0 transition-opacity" aria-hidden="true" onclick="toggleTeamModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left shadow-xl border border-gray-100 dark:border-slate-700 transform modal-content sm:my-8 sm:align-middle sm:max-w-lg sm:w-full transition-all">
            <form action="<?= BASEURL; ?>/team/add" method="post" id="teamForm">
                <input type="hidden" name="id" id="member_id">
                <div class="p-6 sm:p-7">
                    <!-- Modal Header -->
                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100 dark:border-slate-700">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100" id="teamModalTitle">Tambah Anggota Baru</h3>
                            <p class="text-gray-500 dark:text-gray-400 text-xs mt-0.5">Kelola data profil, jabatan kerja, dan level hak akses sistem.</p>
                        </div>
                        <button type="button" onclick="toggleTeamModal()" class="w-8 h-8 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-slate-700 transition flex items-center justify-center">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="name" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Nama Lengkap</label>
                            <input type="text" name="name" id="name" required 
                                class="w-full bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-gray-800 dark:text-gray-100 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition" 
                                placeholder="Masukkan nama lengkap">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Alamat Email</label>
                            <input type="email" name="email" id="email" required 
                                class="w-full bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-gray-800 dark:text-gray-100 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition" 
                                placeholder="nama@assunnah.com">
                        </div>

                        <!-- Password -->
                        <div id="passwordGroup">
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="password" class="text-xs font-semibold text-gray-700 dark:text-gray-300">
                                    Kata Sandi <span id="passwordHint" class="font-normal text-gray-400"></span>
                                </label>
                            </div>
                            <div class="relative">
                                <input type="password" name="password" id="password" value="password123" 
                                    class="w-full bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-gray-800 dark:text-gray-100 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition pr-10" 
                                    placeholder="Masukkan kata sandi">
                                <button type="button" onclick="togglePasswordVisibility()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                                    <i id="pwdToggleIcon" class="fa-solid fa-eye text-sm"></i>
                                </button>
                            </div>
                            <div id="defaultPasswordBtnContainer" class="flex items-center gap-2 mt-2">
                                <button type="button" onclick="document.getElementById('password').value='password123'" class="px-2.5 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800 text-[11px] font-semibold hover:bg-indigo-100 dark:hover:bg-indigo-900/40 transition flex items-center gap-1.5">
                                    <i class="fa-solid fa-key text-[10px]"></i>
                                    <span>Gunakan Default: <strong class="font-mono">password123</strong></span>
                                </button>
                            </div>
                        </div>

                        <!-- Jabatan & Departemen -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label for="role" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Jabatan / Profesi</label>
                                <input type="text" name="role" id="role" required 
                                    class="w-full bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-gray-800 dark:text-gray-100 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition" 
                                    placeholder="Contoh: UI Designer">
                            </div>
                            <div>
                                <label for="department" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Departemen</label>
                                <input type="text" name="department" id="department" required 
                                    class="w-full bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-gray-800 dark:text-gray-100 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition" 
                                    placeholder="Contoh: IT / Programming">
                            </div>
                        </div>

                        <!-- Status Keanggotaan -->
                        <div>
                            <label for="status" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Status Keanggotaan</label>
                            <div class="select-container" id="statusSelectContainer">
                                <div class="select-trigger">
                                    <span class="select-trigger-text text-gray-700 dark:text-gray-300">Pilih Status...</span>
                                    <i class="fa-solid fa-chevron-down select-trigger-icon flex items-center justify-center text-xs"></i>
                                </div>
                                <div class="select-options">
                                    <div class="select-option" data-value="AKTIF">Aktif</div>
                                    <div class="select-option" data-value="NONAKTIF">Nonaktif</div>
                                </div>
                            </div>
                            <input type="hidden" name="status" id="status" value="AKTIF">
                        </div>

                        <!-- Hak Akses Sistem (Level Otoritas) -->
                        <div class="pt-2">
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Hak Akses Sistem
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="systemRoleSelector">
                                <!-- Member Card -->
                                <label class="relative flex flex-col p-3.5 rounded-xl border-2 border-indigo-500 bg-indigo-50/50 dark:bg-indigo-950/30 cursor-pointer transition-all role-card" id="role-card-member">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <div class="flex items-center gap-2">
                                            <span class="w-6 h-6 rounded-md bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs">
                                                <i class="fa-solid fa-user-lock"></i>
                                            </span>
                                            <span class="font-bold text-xs text-gray-900 dark:text-gray-100">Member</span>
                                        </div>
                                        <input type="radio" name="system_role" id="role_radio_member" value="member" checked class="w-3.5 h-3.5 text-indigo-600 focus:ring-indigo-500 accent-indigo-600 cursor-pointer">
                                    </div>
                                    <p class="text-[11px] text-gray-500 dark:text-gray-400 leading-normal">
                                        Hanya mengelola tugas & proyek yang didelegasikan.
                                    </p>
                                </label>

                                <!-- Admin Card -->
                                <label class="relative flex flex-col p-3.5 rounded-xl border-2 border-gray-200 dark:border-slate-700 hover:border-indigo-300 dark:hover:border-slate-600 cursor-pointer transition-all role-card" id="role-card-admin">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <div class="flex items-center gap-2">
                                            <span class="w-6 h-6 rounded-md bg-purple-100 dark:bg-purple-900/50 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xs">
                                                <i class="fa-solid fa-shield-halved"></i>
                                            </span>
                                            <span class="font-bold text-xs text-gray-900 dark:text-gray-100">Admin</span>
                                        </div>
                                        <input type="radio" name="system_role" id="role_radio_admin" value="admin" class="w-3.5 h-3.5 text-indigo-600 focus:ring-indigo-500 accent-indigo-600 cursor-pointer">
                                    </div>
                                    <p class="text-[11px] text-gray-500 dark:text-gray-400 leading-normal">
                                        Akses menyeluruh terhadap semua proyek, tim, & log sistem.
                                    </p>
                                </label>
                            </div>
                            <p id="selfRoleNotice" class="hidden text-xs text-amber-600 dark:text-amber-400 font-semibold mt-2">
                                <i class="fa-solid fa-triangle-exclamation mr-1"></i> Anda tidak dapat mengubah hak akses akun Anda sendiri demi keamanan.
                            </p>
                        </div>

                        <!-- Hidden Presets -->
                        <input type="hidden" name="role_color" id="role_color" value="bg-blue-50 text-blue-600">
                        <input type="hidden" name="status_color" id="status_color" value="text-green-500">
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex items-center justify-end gap-2.5 mt-7 pt-4 border-t border-gray-100 dark:border-slate-700">
                        <button type="button" onclick="toggleTeamModal()" class="px-4 py-2.5 rounded-xl bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300 font-semibold text-xs hover:bg-gray-200 dark:hover:bg-slate-600 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-semibold text-xs hover:bg-indigo-700 transition shadow-sm shadow-indigo-200 dark:shadow-none">
                            Simpan Anggota
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Reset Password -->
<div id="resetPasswordModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-xs modal-overlay opacity-0 transition-opacity" aria-hidden="true" onclick="toggleResetPasswordModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left shadow-xl border border-gray-100 dark:border-slate-700 transform modal-content sm:my-8 sm:align-middle sm:max-w-md sm:w-full transition-all">
            <form action="<?= BASEURL; ?>/team/resetPassword" method="post" id="resetPasswordForm">
                <input type="hidden" name="id" id="reset_member_id">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-5 pb-3 border-b border-gray-100 dark:border-slate-700">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center text-base">
                                <i class="fa-solid fa-key"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">Reset Kata Sandi</h3>
                                <p class="text-gray-400 dark:text-gray-500 text-xs mt-0.5">Atur kata sandi baru untuk pengguna</p>
                            </div>
                        </div>
                        <button type="button" onclick="toggleResetPasswordModal()" class="w-8 h-8 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-slate-700 transition flex items-center justify-center">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <!-- Member info preview -->
                    <div class="bg-gray-50 dark:bg-slate-900/80 rounded-xl p-3 border border-gray-200 dark:border-slate-700/60 mb-4 flex items-center gap-3">
                        <img id="reset_member_avatar" src="" class="w-9 h-9 rounded-full border border-gray-200 dark:border-slate-700 object-cover shrink-0" alt="Avatar">
                        <div class="flex flex-col min-w-0">
                            <span id="reset_member_name" class="font-bold text-gray-900 dark:text-gray-100 text-xs truncate"></span>
                            <span id="reset_member_email" class="text-[11px] text-gray-400 dark:text-gray-500 font-mono truncate"></span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label for="reset_new_password" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                Kata Sandi Baru
                            </label>
                            <div class="relative">
                                <input type="password" name="password" id="reset_new_password" required minlength="4"
                                    class="w-full bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-gray-800 dark:text-gray-100 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none transition pr-10"
                                    placeholder="Masukkan kata sandi baru">
                                <button type="button" onclick="toggleResetPasswordVisibility()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                                    <i id="resetPwdToggleIcon" class="fa-solid fa-eye text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Quick presets -->
                        <div class="flex flex-wrap items-center gap-2 pt-1">
                            <button type="button" id="btnUseDefaultPassword123" class="px-2.5 py-1.5 rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800 text-xs font-semibold hover:bg-amber-100 transition flex items-center gap-1.5">
                                <i class="fa-solid fa-key text-[10px]"></i>
                                <span>Default: <strong class="font-mono">password123</strong></span>
                            </button>
                            <button type="button" id="btnGenerateRandomPassword" class="px-2.5 py-1.5 rounded-lg bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-slate-600 text-xs font-semibold hover:bg-gray-200 dark:hover:bg-slate-600 transition flex items-center gap-1.5">
                                <i class="fa-solid fa-shuffle text-[10px]"></i>
                                Acak Aman
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 mt-6 pt-3 border-t border-gray-100 dark:border-slate-700">
                        <button type="button" onclick="toggleResetPasswordModal()" class="px-4 py-2.5 rounded-xl bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300 font-semibold text-xs hover:bg-gray-200 dark:hover:bg-slate-600 transition">Batal</button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-semibold text-xs transition shadow-sm shadow-amber-200 dark:shadow-none">Simpan Sandi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const teamModal = document.getElementById('teamModal');
    const teamForm = document.getElementById('teamForm');
    const teamModalTitle = document.getElementById('teamModalTitle');

    const resetPasswordModal = document.getElementById('resetPasswordModal');
    const resetPasswordForm = document.getElementById('resetPasswordForm');
    const currentLoggedUserId = '<?= $_SESSION['user']['id']; ?>';

    function toggleTeamModal() {
        if (teamModal.classList.contains('hidden')) {
            teamModal.classList.remove('hidden');
            setTimeout(() => {
                teamModal.classList.add('modal-active');
                document.body.classList.add('overflow-hidden');
            }, 10);
        } else {
            teamModal.classList.remove('modal-active');
            setTimeout(() => {
                teamModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                teamForm.reset();
                document.getElementById('member_id').value = '';
            }, 300);
        }
    }

    function toggleResetPasswordModal() {
        if (resetPasswordModal.classList.contains('hidden')) {
            resetPasswordModal.classList.remove('hidden');
            setTimeout(() => {
                resetPasswordModal.classList.add('modal-active');
                document.body.classList.add('overflow-hidden');
            }, 10);
        } else {
            resetPasswordModal.classList.remove('modal-active');
            setTimeout(() => {
                resetPasswordModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                resetPasswordForm.reset();
                document.getElementById('reset_member_id').value = '';
            }, 300);
        }
    }

    function togglePasswordVisibility() {
        const pwdInput = document.getElementById('password');
        const icon = document.getElementById('pwdToggleIcon');
        if (pwdInput.type === 'password') {
            pwdInput.type = 'text';
            icon.className = 'fa-solid fa-eye-slash text-sm';
        } else {
            pwdInput.type = 'password';
            icon.className = 'fa-solid fa-eye text-sm';
        }
    }

    function toggleResetPasswordVisibility() {
        const pwdInput = document.getElementById('reset_new_password');
        const icon = document.getElementById('resetPwdToggleIcon');
        if (pwdInput.type === 'password') {
            pwdInput.type = 'text';
            icon.className = 'fa-solid fa-eye-slash text-sm';
        } else {
            pwdInput.type = 'password';
            icon.className = 'fa-solid fa-eye text-sm';
        }
    }

    function selectSystemRole(role) {
        const cardMember = document.getElementById('role-card-member');
        const cardAdmin = document.getElementById('role-card-admin');
        const radioMember = document.getElementById('role_radio_member');
        const radioAdmin = document.getElementById('role_radio_admin');

        if (role === 'admin') {
            radioAdmin.checked = true;
            cardAdmin.classList.add('border-indigo-500', 'bg-indigo-50/50', 'dark:bg-indigo-950/30');
            cardAdmin.classList.remove('border-gray-200', 'dark:border-slate-700');

            cardMember.classList.remove('border-indigo-500', 'bg-indigo-50/50', 'dark:bg-indigo-950/30');
            cardMember.classList.add('border-gray-200', 'dark:border-slate-700');
        } else {
            radioMember.checked = true;
            cardMember.classList.add('border-indigo-500', 'bg-indigo-50/50', 'dark:bg-indigo-950/30');
            cardMember.classList.remove('border-gray-200', 'dark:border-slate-700');

            cardAdmin.classList.remove('border-indigo-500', 'bg-indigo-50/50', 'dark:bg-indigo-950/30');
            cardAdmin.classList.add('border-gray-200', 'dark:border-slate-700');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Teleport action button group to header
        const actionArea = document.getElementById('header-action-area');
        const actionContent = document.getElementById('team-action-content');
        if (actionArea && actionContent) {
            actionArea.innerHTML = '';
            while (actionContent.firstChild) {
                actionArea.appendChild(actionContent.firstChild);
            }
        }

        // Initialize Custom Select for status
        if (typeof initCustomSelect === 'function') {
            initCustomSelect('statusSelectContainer', 'status', (val) => {
                document.getElementById('status_color').value = val === 'AKTIF' ? 'text-green-500' : 'text-red-500';
            });
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

        // Role Card Selection Listeners
        const cardMember = document.getElementById('role-card-member');
        const cardAdmin = document.getElementById('role-card-admin');
        if (cardMember) {
            cardMember.addEventListener('click', function() {
                if (!document.getElementById('role_radio_member').disabled) {
                    selectSystemRole('member');
                }
            });
        }
        if (cardAdmin) {
            cardAdmin.addEventListener('click', function() {
                if (!document.getElementById('role_radio_admin').disabled) {
                    selectSystemRole('admin');
                }
            });
        }

        // Add Member Button Listener
        const btnsTambah = document.querySelectorAll('.btnTambahTim');
        btnsTambah.forEach(btn => {
            btn.addEventListener('click', function() {
                teamModalTitle.innerText = 'Tambah Anggota Baru';
                teamForm.setAttribute('action', '<?= BASEURL; ?>/team/add');
                
                // Reset fields
                document.getElementById('status').value = 'AKTIF';
                document.getElementById('password').value = 'password123';
                document.getElementById('password').removeAttribute('placeholder');
                document.getElementById('password').setAttribute('placeholder', 'Masukkan kata sandi');
                document.getElementById('passwordHint').innerText = '';
                const defPwdBtn = document.getElementById('defaultPasswordBtnContainer');
                if (defPwdBtn) defPwdBtn.classList.remove('hidden');
                
                // Enable system role options
                document.getElementById('role_radio_member').disabled = false;
                document.getElementById('role_radio_admin').disabled = false;
                document.getElementById('selfRoleNotice').classList.add('hidden');
                selectSystemRole('member');

                if (typeof initCustomSelect === 'function') {
                    initCustomSelect('statusSelectContainer', 'status', (val) => {
                        document.getElementById('status_color').value = val === 'AKTIF' ? 'text-green-500' : 'text-red-500';
                    });
                }

                toggleTeamModal();
            });
        });

        // Edit Member Button Listener
        const btnsEdit = document.querySelectorAll('.btnEditTim');
        btnsEdit.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                teamModalTitle.innerText = 'Ubah Detail Anggota';
                teamForm.setAttribute('action', '<?= BASEURL; ?>/team/edit');
                
                fetch('<?= BASEURL; ?>/team/getEdit/' + id)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('member_id').value = data.id;
                        document.getElementById('name').value = data.name;
                        document.getElementById('email').value = data.email;
                        document.getElementById('role').value = data.role_label || data.role;
                        document.getElementById('department').value = data.department || '';
                        
                        // Set status
                        document.getElementById('status').value = data.status || 'AKTIF';
                        if (typeof initCustomSelect === 'function') {
                            initCustomSelect('statusSelectContainer', 'status', (val) => {
                                document.getElementById('status_color').value = val === 'AKTIF' ? 'text-green-500' : 'text-red-500';
                            });
                        }
                        
                        document.getElementById('role_color').value = data.role_color || 'bg-blue-50 text-blue-600';
                        document.getElementById('status_color').value = data.status_color || 'text-green-500';
                        
                        // Password hint saat edit
                        document.getElementById('password').value = '';
                        document.getElementById('passwordHint').innerText = '(kosongkan jika tidak ingin mengubah)';
                        const defPwdBtn = document.getElementById('defaultPasswordBtnContainer');
                        if (defPwdBtn) defPwdBtn.classList.add('hidden');

                        // Set system role (admin vs member)
                        selectSystemRole(data.role || 'member');

                        // If editing current logged in user, protect from self demotion
                        const isSelf = (String(data.id) === String(currentLoggedUserId));
                        document.getElementById('role_radio_member').disabled = isSelf;
                        document.getElementById('role_radio_admin').disabled = isSelf;
                        if (isSelf) {
                            document.getElementById('selfRoleNotice').classList.remove('hidden');
                        } else {
                            document.getElementById('selfRoleNotice').classList.add('hidden');
                        }

                        toggleTeamModal();
                    });
            });
        });

        // Reset Password Buttons
        const btnsReset = document.querySelectorAll('.btnResetPassword');
        btnsReset.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const email = this.getAttribute('data-email');

                document.getElementById('reset_member_id').value = id;
                document.getElementById('reset_member_name').innerText = name;
                document.getElementById('reset_member_email').innerText = email;
                document.getElementById('reset_member_avatar').src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(name) + '&background=random';
                
                const pwdInput = document.getElementById('reset_new_password');
                pwdInput.value = '';
                pwdInput.type = 'password';
                document.getElementById('resetPwdToggleIcon').className = 'fa-solid fa-eye text-sm';

                toggleResetPasswordModal();
            });
        });

        // Preset Button: Default Password (password123)
        const btnDefaultPwd = document.getElementById('btnUseDefaultPassword123');
        if (btnDefaultPwd) {
            btnDefaultPwd.addEventListener('click', function() {
                const pwdInput = document.getElementById('reset_new_password');
                pwdInput.value = 'password123';
                pwdInput.type = 'text';
                document.getElementById('resetPwdToggleIcon').className = 'fa-solid fa-eye-slash text-sm';
                pwdInput.focus();
            });
        }

        // Preset Button: Random Password
        const btnRandom = document.getElementById('btnGenerateRandomPassword');
        if (btnRandom) {
            btnRandom.addEventListener('click', function() {
                const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789!@#$%';
                let randomPwd = '';
                for (let i = 0; i < 10; i++) {
                    randomPwd += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                const pwdInput = document.getElementById('reset_new_password');
                pwdInput.value = randomPwd;
                pwdInput.type = 'text';
                document.getElementById('resetPwdToggleIcon').className = 'fa-solid fa-eye-slash text-sm';
            });
        }

        // Initialize Filter & Table Pagination
        initTeamDataTable();
    });

    function initTeamDataTable() {
        const rows = Array.from(document.querySelectorAll('.team-row'));
        const pageSizeSelect = document.getElementById('teamPageSize');
        const infoEl = document.getElementById('teamPaginationInfo');
        const controlsEl = document.getElementById('teamPaginationControls');
        const searchInput = document.getElementById('teamSearch');
        const clearSearchBtn = document.getElementById('clearSearchBtn');
        const filterRole = document.getElementById('filterRole');
        const filterStatus = document.getElementById('filterStatus');
        const filterDept = document.getElementById('filterDept');
        const btnResetFilters = document.getElementById('btnResetFilters');
        
        let currentPage = 1;
        let pageSize = parseInt(pageSizeSelect.value) || 10;
        let filteredRows = [...rows];

        function applyFilter() {
            const query = (searchInput ? searchInput.value.trim().toLowerCase() : '');
            const selectedRole = (filterRole ? filterRole.value.toLowerCase() : '');
            const selectedStatus = (filterStatus ? filterStatus.value.toUpperCase() : '');
            const selectedDept = (filterDept ? filterDept.value.toLowerCase() : '');

            // Show/hide clear search button
            if (clearSearchBtn) {
                if (query.length > 0) clearSearchBtn.classList.remove('hidden');
                else clearSearchBtn.classList.add('hidden');
            }

            // Check if any filter active
            const hasActiveFilter = query || selectedRole || selectedStatus || selectedDept;
            if (btnResetFilters) {
                if (hasActiveFilter) btnResetFilters.classList.remove('hidden');
                else btnResetFilters.classList.add('hidden');
            }

            filteredRows = rows.filter(row => {
                const name = row.getAttribute('data-name') || '';
                const email = row.getAttribute('data-email') || '';
                const dept = row.getAttribute('data-dept') || '';
                const role = row.getAttribute('data-role') || '';
                const roleLabel = row.getAttribute('data-role-label') || '';
                const status = (row.getAttribute('data-status') || '').toUpperCase();

                // Search query match
                const matchQuery = !query || name.includes(query) || email.includes(query) || dept.includes(query) || role.includes(query) || roleLabel.includes(query);
                
                // Role match
                const matchRole = !selectedRole || role === selectedRole;

                // Status match
                const matchStatus = !selectedStatus || status === selectedStatus;

                // Department match
                const matchDept = !selectedDept || dept === selectedDept;

                return matchQuery && matchRole && matchStatus && matchDept;
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
            let noMatchRow = document.getElementById('noMatchTeamRow');
            if (total === 0 && rows.length > 0) {
                if (!noMatchRow) {
                    noMatchRow = document.createElement('tr');
                    noMatchRow.id = 'noMatchTeamRow';
                    noMatchRow.innerHTML = `
                        <td colspan="9" class="py-12 text-center">
                            <div class="max-w-xs mx-auto text-center">
                                <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-slate-700 text-gray-400 flex items-center justify-center mx-auto mb-2 text-sm">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </div>
                                <h4 class="text-xs font-bold text-gray-800 dark:text-gray-200">Tidak ada anggota yang cocok</h4>
                                <p class="text-[11px] text-gray-400 mt-0.5">Coba gunakan kata kunci pencarian atau filter yang berbeda.</p>
                            </div>
                        </td>
                    `;
                    document.getElementById('teamTableBody').appendChild(noMatchRow);
                }
                noMatchRow.classList.remove('hidden');
            } else if (noMatchRow) {
                noMatchRow.classList.add('hidden');
            }

            // Update info text
            if (total === 0) {
                infoEl.innerText = 'Menampilkan 0 anggota';
            } else if (pageSizeSelect.value === 'all' || total <= effectivePageSize) {
                infoEl.innerText = `Menampilkan ${total} anggota`;
            } else {
                infoEl.innerText = `Menampilkan ${startIndex + 1} - ${endIndex} dari ${total} anggota`;
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

        if (filterRole) filterRole.addEventListener('change', applyFilter);
        if (filterStatus) filterStatus.addEventListener('change', applyFilter);
        if (filterDept) filterDept.addEventListener('change', applyFilter);

        if (btnResetFilters) {
            btnResetFilters.addEventListener('click', function() {
                if (searchInput) searchInput.value = '';
                if (filterRole) filterRole.value = '';
                if (filterStatus) filterStatus.value = '';
                if (filterDept) filterDept.value = '';
                applyFilter();
            });
        }

        applyFilter();
    }
</script>