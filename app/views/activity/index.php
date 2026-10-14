<?php
// Filter helper variables
$currentUserId = $_GET['user_id'] ?? '';
$currentDate = $_GET['date'] ?? '';
$currentType = $_GET['type'] ?? '';
$hasActiveFilter = !empty($currentUserId) || !empty($currentDate) || !empty($currentType);

function buildActivityUrl($params = []) {
    $current = $_GET;
    foreach ($params as $k => $v) {
        if ($v === null || $v === '') {
            unset($current[$k]);
        } else {
            $current[$k] = $v;
        }
    }
    return BASEURL . '/activity' . (!empty($current) ? '?' . http_build_query($current) : '');
}
?>

<!-- Header Actions Teleport Content -->
<div id="activity-action-content" class="hidden">
    <div class="flex items-center gap-2.5">
        <a href="<?= BASEURL; ?>/activity/exportCsv<?= !empty($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : '' ?>" class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-200 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-50 dark:hover:bg-slate-700 transition flex items-center gap-2 shadow-2xs" title="Ekspor Log Aktivitas ke CSV">
            <i class="fa-solid fa-file-excel text-emerald-600"></i>
            <span>Ekspor CSV</span>
        </a>
    </div>
</div>

<!-- Metric Stats Strip (Consistent Enterprise Cards with Background Watermark Icons) -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 mb-6">
    <!-- Card 1: Total Log -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-4 shadow-2xs hover:border-indigo-200 dark:hover:border-slate-600 transition-all duration-300 group">
        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-5xl sm:text-6xl text-indigo-600/10 dark:text-indigo-400/15 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300 pointer-events-none select-none">
            <i class="fa-solid fa-clock-rotate-left"></i>
        </div>
        <div class="relative z-10">
            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 block">Total Aktivitas</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100"><?= $data['stats']['total']; ?></span>
                <span class="text-[11px] font-medium text-gray-400 dark:text-gray-500">riwayat tercatat</span>
            </div>
        </div>
    </div>

    <!-- Card 2: Hari Ini -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-4 shadow-2xs hover:border-sky-200 dark:hover:border-slate-600 transition-all duration-300 group">
        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-5xl sm:text-6xl text-sky-600/10 dark:text-sky-400/15 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300 pointer-events-none select-none">
            <i class="fa-solid fa-calendar-day"></i>
        </div>
        <div class="relative z-10">
            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 block">Aktivitas Hari Ini</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-bold text-sky-600 dark:text-sky-400"><?= $data['stats']['today']; ?></span>
                <span class="text-[11px] font-medium text-sky-600/70 dark:text-sky-400/70">hari ini</span>
            </div>
        </div>
    </div>

    <!-- Card 3: Penambahan Baru -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-4 shadow-2xs hover:border-emerald-200 dark:hover:border-slate-600 transition-all duration-300 group">
        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-5xl sm:text-6xl text-emerald-600/10 dark:text-emerald-400/15 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300 pointer-events-none select-none">
            <i class="fa-solid fa-plus"></i>
        </div>
        <div class="relative z-10">
            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 block">Penambahan Baru</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-bold text-emerald-600 dark:text-emerald-400"><?= $data['stats']['create']; ?></span>
                <span class="text-[11px] font-medium text-emerald-600/70 dark:text-emerald-400/70">entri dibuat</span>
            </div>
        </div>
    </div>

    <!-- Card 4: Perubahan Data -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-4 shadow-2xs hover:border-amber-200 dark:hover:border-slate-600 transition-all duration-300 group">
        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-5xl sm:text-6xl text-amber-600/10 dark:text-amber-400/15 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300 pointer-events-none select-none">
            <i class="fa-solid fa-pen"></i>
        </div>
        <div class="relative z-10">
            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 block">Perubahan Data</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-bold text-amber-600 dark:text-amber-400"><?= $data['stats']['update']; ?></span>
                <span class="text-[11px] font-medium text-amber-600/70 dark:text-amber-400/70">pembaruan</span>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filtering Toolbar -->
<div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-3.5 sm:p-4 mb-6 shadow-2xs transition-colors">
    <div class="flex flex-col lg:flex-row gap-3.5 items-stretch lg:items-center justify-between">
        <!-- Type Chips -->
        <div class="flex flex-wrap items-center gap-1.5">
            <a href="<?= buildActivityUrl(['type' => null]); ?>" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition <?= empty($currentType) ? 'bg-indigo-600 text-white shadow-2xs' : 'bg-gray-100 dark:bg-slate-700/60 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-slate-700' ?>">
                Semua
            </a>
            <a href="<?= buildActivityUrl(['type' => 'menambah']); ?>" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition <?= ($currentType === 'menambah') ? 'bg-indigo-600 text-white shadow-2xs' : 'bg-gray-100 dark:bg-slate-700/60 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-slate-700' ?>">
                Menambah
            </a>
            <a href="<?= buildActivityUrl(['type' => 'mengubah']); ?>" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition <?= ($currentType === 'mengubah') ? 'bg-indigo-600 text-white shadow-2xs' : 'bg-gray-100 dark:bg-slate-700/60 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-slate-700' ?>">
                Mengubah
            </a>
            <a href="<?= buildActivityUrl(['type' => 'menghapus']); ?>" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition <?= ($currentType === 'menghapus') ? 'bg-indigo-600 text-white shadow-2xs' : 'bg-gray-100 dark:bg-slate-700/60 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-slate-700' ?>">
                Menghapus
            </a>
        </div>

        <!-- Right Filters: Date & User Dropdowns -->
        <div class="flex flex-wrap items-center gap-2.5">
            <!-- Date Filter Dropdown -->
            <div class="relative custom-dropdown" id="dateDropdown">
                <button onclick="toggleDropdown('dateDropdown')" class="flex items-center gap-2 bg-gray-50 dark:bg-slate-900 px-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 hover:bg-gray-100 dark:hover:bg-slate-800 transition text-xs font-semibold text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-calendar-days text-gray-400 dark:text-gray-500"></i>
                    <span><?= !empty($currentDate) ? date('d M Y', strtotime($currentDate)) : 'Pilih Tanggal'; ?></span>
                    <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 ml-1"></i>
                </button>
                <div class="dropdown-menu absolute top-full right-0 mt-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-xl p-3.5 hidden z-50 min-w-[220px]">
                    <label class="text-[11px] font-semibold text-gray-500 dark:text-gray-400 block mb-1.5">Filter Tanggal</label>
                    <input type="date" id="filter-date" 
                           class="w-full text-xs bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg px-2.5 py-1.5 text-gray-800 dark:text-gray-200 focus:outline-none focus:border-indigo-500" 
                           value="<?= htmlspecialchars($currentDate); ?>" 
                           onchange="applyFilter('date', this.value)">
                    <?php if(!empty($currentDate)) : ?>
                        <button onclick="applyFilter('date', '')" class="mt-2 text-xs text-rose-600 dark:text-rose-400 font-semibold hover:underline block">
                            Hapus Filter Tanggal
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Member Filter (Admin Only) -->
            <?php if(($data['user_role'] ?? 'admin') === 'admin' && !empty($data['team'])) : ?>
            <div class="relative custom-dropdown" id="userDropdown">
                <button onclick="toggleDropdown('userDropdown')" class="flex items-center gap-2 bg-gray-50 dark:bg-slate-900 px-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 hover:bg-gray-100 dark:hover:bg-slate-800 transition text-xs font-semibold text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-user text-gray-400 dark:text-gray-500"></i>
                    <span class="truncate max-w-[130px]">
                        <?php 
                            $selectedUser = 'Semua Anggota';
                            if(!empty($currentUserId)) {
                                foreach($data['team'] as $m) {
                                    if($m['id'] == $currentUserId) $selectedUser = $m['name'];
                                }
                            }
                            echo htmlspecialchars($selectedUser);
                        ?>
                    </span>
                    <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 ml-1"></i>
                </button>
                <div class="dropdown-menu absolute top-full right-0 mt-2 w-56 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-xl py-1.5 hidden z-50 max-h-60 overflow-y-auto">
                    <button onclick="applyFilter('user_id', '')" class="w-full text-left px-4 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition <?= empty($currentUserId) ? 'font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50/50 dark:bg-slate-700' : 'font-medium' ?>">Semua Anggota</button>
                    <?php foreach($data['team'] as $m) : ?>
                        <button onclick="applyFilter('user_id', '<?= $m['id']; ?>')" class="w-full text-left px-4 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition <?= ($currentUserId == $m['id']) ? 'font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50/50 dark:bg-slate-700' : 'font-medium' ?>"><?= htmlspecialchars($m['name']); ?></button>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Clear Filter Button -->
            <?php if($hasActiveFilter) : ?>
                <a href="<?= BASEURL; ?>/activity" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:text-rose-700 flex items-center gap-1 px-2.5 py-1.5 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/30 transition">
                    <i class="fa-solid fa-xmark text-xs"></i>
                    <span>Reset</span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Unified Activity Timeline Feed -->
<?php if(empty($data['activities'])) : ?>
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-12 text-center shadow-2xs">
        <div class="w-14 h-14 bg-indigo-50 dark:bg-indigo-950/40 rounded-xl flex items-center justify-center mx-auto mb-4 text-indigo-600 dark:text-indigo-400 text-xl">
            <i class="fa-solid fa-clock-rotate-left"></i>
        </div>
        <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100 mb-1">Belum Ada Aktivitas</h3>
        <p class="text-gray-400 dark:text-gray-500 text-xs max-w-sm mx-auto">
            <?= $hasActiveFilter ? 'Tidak ada catatan aktivitas yang cocok dengan filter yang dipilih.' : 'Semua aktivitas dan jejak perubahan sistem akan tampil di sini secara real-time.'; ?>
        </p>
        <?php if($hasActiveFilter) : ?>
            <a href="<?= BASEURL; ?>/activity" class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                <i class="fa-solid fa-rotate-left text-[10px]"></i>
                <span>Reset Semua Filter</span>
            </a>
        <?php endif; ?>
    </div>
<?php else : ?>
    <div class="space-y-6">
        <?php foreach($data['activities'] as $date => $logs) : ?>
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 shadow-2xs overflow-hidden transition-colors">
                <!-- Group Date Header -->
                <div class="bg-gray-50/80 dark:bg-slate-900/60 px-5 py-2.5 border-b border-gray-100 dark:border-slate-700/80 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                        <h3 class="text-xs font-bold text-gray-800 dark:text-gray-200"><?= htmlspecialchars($date); ?></h3>
                    </div>
                    <span class="text-[11px] font-medium text-gray-400 dark:text-gray-500">
                        <?= count($logs); ?> aktivitas
                    </span>
                </div>

                <!-- Log Rows -->
                <div class="divide-y divide-gray-100 dark:divide-slate-700/60">
                    <?php foreach($logs as $log) : 
                        $actionLower = strtolower($log['action']);
                        
                        // Badge icon & bg
                        $nodeBg = "bg-blue-50 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400";
                        $nodeIcon = '<i class="fa-solid fa-comment-dots text-[9px]"></i>';

                        if (strpos($actionLower, 'menambah') !== false || strpos($actionLower, 'membuat') !== false) {
                            $nodeBg = "bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400";
                            $nodeIcon = '<i class="fa-solid fa-plus text-[9px]"></i>';
                        } elseif (strpos($actionLower, 'perbarui') !== false || strpos($actionLower, 'edit') !== false || strpos($actionLower, 'ubah') !== false) {
                            $nodeBg = "bg-amber-50 text-amber-600 dark:bg-amber-950/60 dark:text-amber-400";
                            $nodeIcon = '<i class="fa-solid fa-pen text-[9px]"></i>';
                        } elseif (strpos($actionLower, 'hapus') !== false) {
                            $nodeBg = "bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400";
                            $nodeIcon = '<i class="fa-solid fa-trash-can text-[9px]"></i>';
                        } elseif (strpos($actionLower, 'anggota') !== false) {
                            $nodeBg = "bg-purple-50 text-purple-600 dark:bg-purple-950/60 dark:text-purple-400";
                            $nodeIcon = '<i class="fa-solid fa-user-plus text-[9px]"></i>';
                        }
                    ?>
                    <div class="px-5 py-3.5 hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition flex items-start sm:items-center justify-between gap-4 group">
                        <div class="flex items-start gap-3 min-w-0">
                            <!-- Avatar + Mini Action Badge -->
                            <div class="relative flex-shrink-0 mt-0.5 sm:mt-0">
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($log['user']); ?>&background=random" 
                                     class="w-9 h-9 rounded-full border border-gray-200 dark:border-slate-700" 
                                     alt="<?= htmlspecialchars($log['user']); ?>">
                                <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border border-white dark:border-slate-800 flex items-center justify-center <?= $nodeBg ?> shadow-2xs">
                                    <?= $nodeIcon ?>
                                </div>
                            </div>

                            <!-- Activity Description -->
                            <div class="min-w-0">
                                <p class="text-xs text-gray-700 dark:text-gray-300 leading-snug">
                                    <span class="font-semibold text-gray-900 dark:text-gray-100"><?= htmlspecialchars($log['user']); ?></span>
                                    <span class="text-gray-500 dark:text-gray-400"><?= htmlspecialchars($log['action']); ?></span>
                                    <span class="text-indigo-600 dark:text-indigo-400 font-semibold"><?= htmlspecialchars($log['target']); ?></span>
                                    <?php if(!empty($log['to'])) : ?>
                                        <span class="text-gray-400 dark:text-gray-500">ke</span>
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60">
                                            <?= htmlspecialchars(strtoupper($log['to'])); ?>
                                        </span>
                                    <?php endif; ?>
                                </p>
                                <?php if(!empty($log['info']) && $log['info'] != $log['target']) : ?>
                                    <p class="text-[11px] text-gray-400 dark:text-gray-500 italic mt-0.5 line-clamp-1 group-hover:line-clamp-none transition-all">
                                        "<?= htmlspecialchars($log['info']); ?>"
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Right Timestamp -->
                        <div class="flex-shrink-0 text-right">
                            <span class="text-xs font-medium text-gray-400 dark:text-gray-500 whitespace-nowrap">
                                <?= htmlspecialchars($log['time']); ?> WIB
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Teleport action content to topbar
        const actionArea = document.getElementById('header-action-area');
        const actionContent = document.getElementById('activity-action-content');
        if (actionArea && actionContent) {
            actionArea.innerHTML = '';
            while (actionContent.firstChild) {
                actionArea.appendChild(actionContent.firstChild);
            }
        }
    });

    function applyFilter(param, value) {
        const url = new URL(window.location.href);
        if (value) {
            url.searchParams.set(param, value);
        } else {
            url.searchParams.delete(param);
        }
        window.location.href = url.toString();
    }
</script>
