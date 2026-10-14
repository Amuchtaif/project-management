<?php
// Compute task distribution counts & percentages
$dist = ['todo' => 0, 'in_progress' => 0, 'done' => 0];
$totalTasks = 0;
foreach (($data['task_distribution'] ?? []) as $d) {
    $st = $d['status'] ?? 'todo';
    $cnt = (int)($d['count'] ?? 0);
    $dist[$st] = $cnt;
    $totalTasks += $cnt;
}
$doneCount = $dist['done'];
$inProgressCount = $dist['in_progress'];
$todoCount = $dist['todo'];

$pArr = [
    'todo' => $totalTasks > 0 ? round(($todoCount / $totalTasks) * 100) : 0,
    'in_progress' => $totalTasks > 0 ? round(($inProgressCount / $totalTasks) * 100) : 0,
    'done' => $totalTasks > 0 ? round(($doneCount / $totalTasks) * 100) : 0
];

if (!function_exists('dashboardTimeAgo')) {
    function dashboardTimeAgo($timestamp) {
        if (!$timestamp) return "-";
        $time = is_numeric($timestamp) ? $timestamp : strtotime($timestamp);
        $diff = time() - $time;
        if ($diff < 60) return "Baru saja";
        if ($diff < 3600) return floor($diff / 60) . " menit lalu";
        if ($diff < 86400) return floor($diff / 3600) . " jam lalu";
        if ($diff < 172800) return "Kemarin";
        if ($diff < 604800) return floor($diff / 86400) . " hari lalu";
        return date('d M Y', $time);
    }
}
?>

<!-- Header Actions Teleport Content -->
<div id="dashboard-action-content" class="hidden">
    <div class="flex items-center gap-2.5">
        <a href="<?= BASEURL; ?>/task/calendar" class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-200 px-3.5 py-2 rounded-xl text-xs font-semibold hover:bg-gray-50 dark:hover:bg-slate-700 transition flex items-center gap-2 shadow-2xs">
            <i class="fa-solid fa-calendar-days text-indigo-600 dark:text-indigo-400"></i>
            <span>Kalender</span>
        </a>
        <a href="<?= BASEURL; ?>/task" class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-200 px-3.5 py-2 rounded-xl text-xs font-semibold hover:bg-gray-50 dark:hover:bg-slate-700 transition flex items-center gap-2 shadow-2xs">
            <i class="fa-solid fa-table-columns text-indigo-600 dark:text-indigo-400"></i>
            <span>Papan Tugas</span>
        </a>
        <?php if(($data['user_role'] ?? 'admin') === 'admin') : ?>
        <a href="<?= BASEURL; ?>/project" class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-xs font-semibold hover:bg-indigo-700 transition flex items-center justify-center gap-2 shadow-sm shadow-indigo-200 dark:shadow-none">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Buat Project</span>
        </a>
        <?php endif; ?>
    </div>
</div>

<!-- Metric Stats Strip (4 Cards) -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 mb-6">
    <!-- Card 1: Total Project -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-4 shadow-2xs hover:border-indigo-200 dark:hover:border-slate-600 transition-all duration-300 group">
        <!-- Background Overlay Icon -->
        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-5xl sm:text-6xl text-indigo-600/10 dark:text-indigo-400/15 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300 pointer-events-none select-none">
            <i class="fa-solid fa-diagram-project"></i>
        </div>
        <div class="relative z-10">
            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 block"><?= ($data['user_role'] ?? 'admin') === 'admin' ? 'Total Project' : 'Project Saya'; ?></span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100"><?= $data['stats']['projects']; ?></span>
                <span class="text-[11px] font-medium text-gray-400 dark:text-gray-500">project aktif</span>
            </div>
        </div>
    </div>

    <!-- Card 2: Tugas Aktif / Tugas Saya -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-4 shadow-2xs hover:border-sky-200 dark:hover:border-slate-600 transition-all duration-300 group">
        <!-- Background Overlay Icon -->
        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-5xl sm:text-6xl text-sky-600/10 dark:text-sky-400/15 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300 pointer-events-none select-none">
            <i class="fa-solid fa-list-check"></i>
        </div>
        <div class="relative z-10">
            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 block"><?= ($data['user_role'] ?? 'admin') === 'admin' ? 'Tugas Aktif' : 'Tugas Saya'; ?></span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100"><?= $data['stats']['tasks']; ?></span>
                <span class="text-[11px] font-medium text-gray-400 dark:text-gray-500">tugas tercatat</span>
            </div>
        </div>
    </div>

    <!-- Card 3: Tugas Selesai -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-4 shadow-2xs hover:border-emerald-200 dark:hover:border-slate-600 transition-all duration-300 group">
        <!-- Background Overlay Icon -->
        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-5xl sm:text-6xl text-emerald-600/10 dark:text-emerald-400/15 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300 pointer-events-none select-none">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="relative z-10">
            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 block">Tugas Selesai</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-bold text-emerald-600 dark:text-emerald-400"><?= $doneCount; ?></span>
                <span class="text-[11px] font-medium text-emerald-600/70 dark:text-emerald-400/70"><?= $totalTasks > 0 ? round(($doneCount / $totalTasks) * 100) . '%' : '0%'; ?> selesai</span>
            </div>
        </div>
    </div>

    <!-- Card 4: Anggota Tim (Admin) atau Hak Akses (Member) -->
    <?php if(($data['user_role'] ?? 'admin') === 'admin') : ?>
    <div class="relative overflow-hidden bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-4 shadow-2xs hover:border-purple-200 dark:hover:border-slate-600 transition-all duration-300 group">
        <!-- Background Overlay Icon -->
        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-5xl sm:text-6xl text-purple-600/10 dark:text-purple-400/15 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300 pointer-events-none select-none">
            <i class="fa-solid fa-users"></i>
        </div>
        <div class="relative z-10">
            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 block">Anggota Tim</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100"><?= $data['stats']['members']; ?></span>
                <span class="text-[11px] font-medium text-gray-400 dark:text-gray-500">anggota aktif</span>
            </div>
        </div>
    </div>
    <?php else : ?>
    <div class="relative overflow-hidden bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-4 shadow-2xs hover:border-amber-200 dark:hover:border-slate-600 transition-all duration-300 group">
        <!-- Background Overlay Icon -->
        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-5xl sm:text-6xl text-amber-600/10 dark:text-amber-400/15 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300 pointer-events-none select-none">
            <i class="fa-solid fa-user-shield"></i>
        </div>
        <div class="relative z-10">
            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 block">Hak Akses</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100 capitalize"><?= htmlspecialchars($_SESSION['user']['role']); ?></span>
                <span class="text-[11px] font-medium text-gray-400 dark:text-gray-500">level pengguna</span>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Left Column: Project Progress & Compact Sub-Grid (2 cols) -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Progres Project Card -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-5 md:p-6 shadow-2xs transition-colors">
            <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-slate-700/80 mb-5">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-bars-progress"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900 dark:text-gray-100"><?= ($data['user_role'] ?? 'admin') === 'admin' ? 'Progres Project Aktif' : 'Progres Project Saya'; ?></h2>
                    </div>
                </div>
                <a href="<?= BASEURL; ?>/project" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 flex items-center gap-1 transition">
                    <span>Lihat Semua</span>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </a>
            </div>

            <div class="space-y-3.5">
                <?php if(empty($data['projects_progress'])) : ?>
                    <div class="py-8 text-center text-gray-400 dark:text-gray-500 text-xs italic">
                        <i class="fa-solid fa-folder-open text-2xl mb-2 block text-gray-300 dark:text-gray-600"></i>
                        <?= ($data['user_role'] ?? 'admin') === 'admin' ? 'Belum ada project aktif.' : 'Anda belum ditugaskan ke project manapun.'; ?>
                    </div>
                <?php else : ?>
                    <?php foreach(array_slice($data['projects_progress'], 0, 3) as $p) : 
                        $progress = (int)round($p['avg_progress'] ?? 0);
                        $statusText = $p['status'] ?? 'Sesuai Jadwal';
                        $stLower = strtolower(trim($statusText));

                        // Status Badge
                        $badgeClass = 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border-emerald-200/60 dark:border-emerald-800/60';
                        if ($stLower === 'terhambat') {
                            $badgeClass = 'bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-300 border-rose-200/60 dark:border-rose-800/60';
                        } elseif ($stLower === 'selesai' || $progress === 100) {
                            $badgeClass = 'bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-300 border-blue-200/60 dark:border-blue-800/60';
                        }

                        // Bar Color
                        $barColor = 'bg-indigo-600';
                        if ($progress === 100) $barColor = 'bg-emerald-500';
                        elseif ($stLower === 'terhambat') $barColor = 'bg-rose-500';
                    ?>
                    <div class="p-3.5 rounded-xl border border-gray-100 dark:border-slate-700/60 bg-gray-50/50 dark:bg-slate-900/30 hover:border-gray-200 dark:hover:border-slate-700 transition">
                        <div class="flex items-center justify-between gap-3 mb-2">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-6 h-6 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0 text-xs">
                                    <i class="fa-solid fa-folder"></i>
                                </div>
                                <a href="<?= BASEURL; ?>/task?project_id=<?= $p['id']; ?>" class="text-xs font-bold text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 truncate transition">
                                    <?= htmlspecialchars($p['name']); ?>
                                </a>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold border <?= $badgeClass ?> flex-shrink-0">
                                    <?= htmlspecialchars($statusText); ?>
                                </span>
                            </div>
                            <span class="text-xs font-bold text-gray-900 dark:text-gray-100 flex-shrink-0"><?= $progress; ?>%</span>
                        </div>

                        <!-- Progress Bar Track -->
                        <div class="w-full bg-gray-200 dark:bg-slate-700 h-2 rounded-full overflow-hidden mb-2.5">
                            <div class="h-full rounded-full <?= $barColor ?> transition-all duration-500" style="width: <?= $progress; ?>%"></div>
                        </div>

                        <div class="flex items-center justify-between text-[11px] text-gray-500 dark:text-gray-400">
                            <span class="flex items-center gap-1.5">
                                <span>Lead:</span>
                                <strong class="text-gray-700 dark:text-gray-300 font-semibold"><?= htmlspecialchars($p['leader_name'] ?? 'Belum ada'); ?></strong>
                            </span>
                            <a href="<?= BASEURL; ?>/task?project_id=<?= $p['id']; ?>" class="text-indigo-600 dark:text-indigo-400 font-semibold hover:underline flex items-center gap-1">
                                <span>Buka Tugas</span>
                                <i class="fa-solid fa-arrow-right text-[9px]"></i>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- 2-Column Sub-Grid: Near Deadline Tasks & Task Distribution -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Card A: Tugas Deadline Mendatang -->
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-5 md:p-6 shadow-2xs transition-colors flex flex-col">
                <div class="flex items-center justify-between pb-3.5 border-b border-gray-100 dark:border-slate-700/80 mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-lg bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center text-[11px]">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <h3 class="text-xs font-bold text-gray-900 dark:text-gray-100">Deadline Dekat</h3>
                    </div>
                    <a href="<?= BASEURL; ?>/task" class="text-[11px] font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700">Lihat Semua</a>
                </div>

                <div class="space-y-2.5 flex-grow">
                    <?php if(empty($data['near_deadline_tasks'])) : ?>
                        <div class="py-6 text-center text-gray-400 dark:text-gray-500 text-xs italic">
                            <i class="fa-solid fa-circle-check text-xl mb-1.5 text-emerald-500 block"></i>
                            Tidak ada tugas deadline mendesak.
                        </div>
                    <?php else : ?>
                        <?php foreach(array_slice($data['near_deadline_tasks'], 0, 4) as $task) : 
                            $daysLeft = (int)$task['days_left'];
                            $prio = strtolower($task['priority'] ?? 'biasa');
                            
                            $prioDot = 'bg-blue-500';
                            if ($prio === 'tinggi') $prioDot = 'bg-rose-500';
                            elseif ($prio === 'sedang') $prioDot = 'bg-amber-500';

                            if ($daysLeft < 0) {
                                $badgeClass = 'bg-rose-50 text-rose-700 dark:bg-rose-950/50 dark:text-rose-300 border border-rose-200/60 dark:border-rose-800/60';
                                $badgeText = abs($daysLeft) . ' Hari Terlewat';
                            } elseif ($daysLeft === 0) {
                                $badgeClass = 'bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/60';
                                $badgeText = 'Hari Ini';
                            } else {
                                $badgeClass = 'bg-blue-50 text-blue-700 dark:bg-blue-950/50 dark:text-blue-300 border border-blue-200/60 dark:border-blue-800/60';
                                $badgeText = $daysLeft . ' Hari Lagi';
                            }
                        ?>
                        <div class="p-2.5 rounded-lg border border-gray-100 dark:border-slate-700/60 hover:bg-gray-50 dark:hover:bg-slate-700/30 transition flex items-center justify-between gap-2.5">
                            <div class="min-w-0 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full <?= $prioDot ?> flex-shrink-0" title="Prioritas <?= ucfirst($prio); ?>"></span>
                                <div class="truncate">
                                    <h4 class="text-xs font-semibold text-gray-800 dark:text-gray-200 truncate"><?= htmlspecialchars($task['title']); ?></h4>
                                    <p class="text-[10px] text-gray-400 dark:text-gray-500 truncate"><?= htmlspecialchars($task['project_name']); ?></p>
                                </div>
                            </div>
                            <span class="text-[9px] font-bold px-2 py-0.5 rounded flex-shrink-0 <?= $badgeClass ?>">
                                <?= $badgeText ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Card B: Distribusi Status Tugas -->
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-5 md:p-6 shadow-2xs transition-colors flex flex-col">
                <div class="flex items-center justify-between pb-3.5 border-b border-gray-100 dark:border-slate-700/80 mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-lg bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-[11px]">
                            <i class="fa-solid fa-chart-pie"></i>
                        </div>
                        <h3 class="text-xs font-bold text-gray-900 dark:text-gray-100">Distribusi Tugas</h3>
                    </div>
                    <a href="<?= BASEURL; ?>/task" class="text-[11px] font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700">Papan Tugas</a>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 flex-grow">
                    <!-- SVG Donut Chart -->
                    <div class="relative w-28 h-28 flex-shrink-0">
                        <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                            <!-- Background Track (Todo) -->
                            <path class="text-gray-100 dark:text-slate-700" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            
                            <!-- Done Segment (Green) -->
                            <path class="text-emerald-500 transition-all duration-700" stroke-width="3.5" stroke-dasharray="<?= $pArr['done']; ?>, 100" stroke-dashoffset="0" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            
                            <!-- In Progress Segment (Indigo) -->
                            <path class="text-indigo-600 transition-all duration-700" stroke-width="3.5" stroke-dasharray="<?= $pArr['in_progress']; ?>, 100" stroke-dashoffset="-<?= $pArr['done']; ?>" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                            <span class="text-lg font-bold text-gray-900 dark:text-gray-100 leading-none"><?= $totalTasks; ?></span>
                            <span class="text-[9px] text-gray-400 dark:text-gray-500 font-medium mt-0.5">Tugas</span>
                        </div>
                    </div>

                    <!-- Breakdown Legend -->
                    <div class="space-y-2 w-full text-xs">
                        <div class="flex items-center justify-between p-1.5 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700/30 transition">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 flex-shrink-0"></span>
                                <span class="text-gray-600 dark:text-gray-300 font-medium">Selesai</span>
                            </div>
                            <span class="font-bold text-gray-900 dark:text-gray-100"><?= $doneCount; ?> <span class="text-[10px] text-gray-400 font-normal">(<?= $pArr['done']; ?>%)</span></span>
                        </div>

                        <div class="flex items-center justify-between p-1.5 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700/30 transition">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="w-2 h-2 rounded-full bg-indigo-600 flex-shrink-0"></span>
                                <span class="text-gray-600 dark:text-gray-300 font-medium">In Progress</span>
                            </div>
                            <span class="font-bold text-gray-900 dark:text-gray-100"><?= $inProgressCount; ?> <span class="text-[10px] text-gray-400 font-normal">(<?= $pArr['in_progress']; ?>%)</span></span>
                        </div>

                        <div class="flex items-center justify-between p-1.5 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700/30 transition">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="w-2 h-2 rounded-full bg-gray-300 dark:bg-slate-600 flex-shrink-0"></span>
                                <span class="text-gray-600 dark:text-gray-300 font-medium">To Do</span>
                            </div>
                            <span class="font-bold text-gray-900 dark:text-gray-100"><?= $todoCount; ?> <span class="text-[10px] text-gray-400 font-normal">(<?= $pArr['todo']; ?>%)</span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Recent Activity Feed (1 col) -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-5 md:p-6 shadow-2xs flex flex-col transition-colors">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-slate-700/80 mb-5">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <h2 class="text-sm font-bold text-gray-900 dark:text-gray-100">Aktivitas Terbaru</h2>
            </div>
            <a href="<?= BASEURL; ?>/activity" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 flex items-center gap-1 transition">
                <span>Lihat Semua</span>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
            </a>
        </div>

        <div class="flex-grow flex flex-col justify-between">
            <?php if(empty($data['activities'])) : ?>
                <div class="my-auto py-12 text-center text-gray-400 dark:text-gray-500 text-xs italic">
                    <i class="fa-solid fa-clock-rotate-left text-2xl mb-2 text-gray-300 dark:text-gray-600 block"></i>
                    Belum ada riwayat aktivitas baru.
                </div>
            <?php else : ?>
                <div class="relative border-l border-gray-100 dark:border-slate-700/80 ml-3.5 space-y-3.5 pl-4 pb-2">
                    <?php foreach(array_slice($data['activities'], 0, 8) as $activity) : 
                        $action = strtolower($activity['action']);
                        
                        $nodeBg = "bg-blue-50 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400";
                        $nodeIcon = '<i class="fa-solid fa-comment-dots"></i>';

                        if (strpos($action, 'menambah') !== false || strpos($action, 'membuat') !== false) {
                            $nodeBg = "bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400";
                            $nodeIcon = '<i class="fa-solid fa-plus"></i>';
                            if (strpos($action, 'tugas') !== false) {
                                $nodeIcon = '<i class="fa-solid fa-file-circle-plus"></i>';
                            }
                        } elseif (strpos($action, 'perbarui') !== false || strpos($action, 'edit') !== false || strpos($action, 'ubah') !== false) {
                            $nodeBg = "bg-amber-50 text-amber-600 dark:bg-amber-950/60 dark:text-amber-400";
                            $nodeIcon = '<i class="fa-solid fa-pen"></i>';
                        } elseif (strpos($action, 'hapus') !== false) {
                            $nodeBg = "bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400";
                            $nodeIcon = '<i class="fa-solid fa-trash-can"></i>';
                        } elseif (strpos($action, 'anggota') !== false) {
                            $nodeBg = "bg-purple-50 text-purple-600 dark:bg-purple-950/60 dark:text-purple-400";
                            $nodeIcon = '<i class="fa-solid fa-user-plus"></i>';
                        }
                        
                        $isCurrentUser = ($activity['user_id'] == $_SESSION['user']['id']);
                    ?>
                    <div class="relative group">
                        <!-- Node Circle -->
                        <div class="absolute -left-[23px] top-0.5 w-4 h-4 rounded-full border-2 border-white dark:border-slate-800 flex items-center justify-center <?= $nodeBg ?> text-[8px] shadow-2xs">
                            <?= $nodeIcon ?>
                        </div>

                        <div class="min-w-0">
                            <p class="text-xs text-gray-700 dark:text-gray-300 leading-snug">
                                <span class="font-semibold text-gray-900 dark:text-gray-100"><?= $isCurrentUser ? 'Anda' : htmlspecialchars($activity['user_name']); ?></span>
                                <span class="text-gray-500 dark:text-gray-400"><?= htmlspecialchars($activity['action']); ?></span>
                                <span class="text-indigo-600 dark:text-indigo-400 font-semibold"><?= htmlspecialchars($activity['target']); ?></span>
                            </p>

                            <?php if(!empty($activity['info']) && $activity['info'] != $activity['target']) : ?>
                                <p class="text-[11px] text-gray-400 dark:text-gray-500 italic mt-0.5 border-l-2 border-gray-100 dark:border-slate-700 pl-2 line-clamp-1">
                                    "<?= htmlspecialchars($activity['info']); ?>"
                                </p>
                            <?php endif; ?>

                            <span class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5 block font-medium">
                                <?= dashboardTimeAgo($activity['created_at']); ?>
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Grounded Footer Bar -->
                <div class="mt-auto pt-3.5 border-t border-gray-100 dark:border-slate-700/80 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/60">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Live Feed
                        </span>
                        <span class="text-[11px] text-gray-400 dark:text-gray-500 font-medium">
                            <?= count($data['activities']); ?> aktivitas
                        </span>
                    </div>
                    <a href="<?= BASEURL; ?>/activity" class="text-[11px] font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 flex items-center gap-1 transition">
                        <span>Riwayat Lengkap</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Dashboard Footer -->
<footer class="mt-8 pt-5 pb-4 border-t border-gray-100 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-400 dark:text-gray-500">
    <div class="flex items-center gap-1.5 font-medium">
        <span>Created with</span>
        <i class="fa-solid fa-heart text-rose-500 text-xs inline-block animate-pulse"></i>
        <span>by</span>
        <span class="font-bold text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Code With Andi</span>
    </div>
    <div class="text-[11px] text-gray-400 dark:text-gray-500">
        &copy; <?= date('Y'); ?> Manajemen Project Yayasan Assunnah. All rights reserved.
    </div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Teleport action content to topbar header area
        const actionArea = document.getElementById('header-action-area');
        const actionContent = document.getElementById('dashboard-action-content');
        if (actionArea && actionContent) {
            actionArea.innerHTML = '';
            while (actionContent.firstChild) {
                actionArea.appendChild(actionContent.firstChild);
            }
        }
    });
</script>
