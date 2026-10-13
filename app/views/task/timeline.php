<!-- Timeline / Roadmap View -->
<div id="timeline-action-content" class="hidden">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 py-1">
        <div class="flex flex-wrap items-center gap-3">
            <!-- View Switcher -->
            <div class="flex items-center bg-gray-100 dark:bg-slate-800 p-1 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm">
                <a href="<?= BASEURL ?>/task<?= isset($_GET['project_id']) ? '?project_id='.$_GET['project_id'] : '' ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition" title="Tampilan Papan Kanban">
                    <i class="fa-solid fa-list-check text-xs"></i>
                    <span>Papan</span>
                </a>
                <a href="<?= BASEURL ?>/task/calendar<?= isset($_GET['project_id']) ? '?project_id='.$_GET['project_id'] : '' ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition" title="Tampilan Kalender">
                    <i class="fa-solid fa-calendar-days text-xs"></i>
                    <span>Kalender</span>
                </a>
                <a href="<?= BASEURL ?>/task/timeline<?= isset($_GET['project_id']) ? '?project_id='.$_GET['project_id'] : '' ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-black bg-white dark:bg-slate-700 text-[#313491] dark:text-indigo-400 shadow-sm" title="Tampilan Roadmap">
                    <i class="fa-solid fa-timeline text-xs"></i>
                    <span>Roadmap</span>
                </a>
            </div>

            <!-- Project Filter -->
            <div class="relative custom-dropdown" id="timelineProjectDropdown">
                <button onclick="toggleDropdown('timelineProjectDropdown')" class="flex items-center gap-2 bg-white dark:bg-slate-800 px-5 py-2.5 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm hover:bg-gray-50 dark:hover:bg-slate-700 transition border-s-4 border-s-indigo-500">
                    <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-tight">Project:</span>
                    <span class="text-sm font-black text-gray-900 dark:text-gray-100">
                        <?php 
                            $selectedProj = 'Semua Project';
                            if(isset($_GET['project_id']) && !empty($_GET['project_id'])) {
                                foreach($data['projects'] as $p) {
                                    if($p['id'] == $_GET['project_id']) $selectedProj = $p['name'];
                                }
                            }
                            echo $selectedProj;
                        ?>
                    </span>
                    <i class="fa-solid fa-chevron-down text-xs text-indigo-500 ml-1"></i>
                </button>
                <div class="dropdown-menu absolute top-full left-0 mt-2 w-64 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl shadow-2xl py-3 hidden z-[100] max-h-60 overflow-y-auto">
                    <a href="<?= BASEURL ?>/task/timeline" class="block w-full text-left px-5 py-2.5 text-sm text-gray-600 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-slate-700 hover:text-indigo-700 transition <?= !isset($_GET['project_id']) || empty($_GET['project_id']) ? 'font-black text-indigo-700 bg-indigo-50/50 dark:bg-slate-700' : 'font-semibold' ?>">Semua Project</a>
                    <?php foreach($data['projects'] as $p) : ?>
                        <a href="<?= BASEURL ?>/task/timeline?project_id=<?= $p['id'] ?>" class="block w-full text-left px-5 py-2.5 text-sm text-gray-600 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-slate-700 hover:text-indigo-700 transition <?= (isset($_GET['project_id']) && $_GET['project_id'] == $p['id']) ? 'font-black text-indigo-700 bg-indigo-50/50 dark:bg-slate-700' : 'font-semibold' ?>"><?= $p['name'] ?></a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Export CSV -->
            <a href="<?= BASEURL ?>/task/exportCsv<?= isset($_GET['project_id']) ? '?project_id='.$_GET['project_id'] : '' ?>" class="flex items-center gap-2 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-300 px-4 py-2.5 rounded-xl border border-emerald-200 dark:border-emerald-800 hover:bg-emerald-100 transition text-xs font-black shadow-sm" title="Ekspor daftar tugas ke CSV">
                <i class="fa-solid fa-file-excel"></i>
                <span>Ekspor CSV</span>
            </a>
        </div>
    </div>
</div>

<?php
// Hitung metrik tugas
$totalTasks = count($data['tasks']);
$todoTasks = 0;
$inProgressTasks = 0;
$doneTasks = 0;
$overdueTasks = 0;
$todayDate = date('Y-m-d');

foreach ($data['tasks'] as $t) {
    if ($t['status'] === 'done') {
        $doneTasks++;
    } elseif ($t['status'] === 'in_progress') {
        $inProgressTasks++;
        if ($t['due_date'] < $todayDate) $overdueTasks++;
    } else {
        $todoTasks++;
        if ($t['due_date'] < $todayDate) $overdueTasks++;
    }
}

$completionRate = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 0;
?>

<!-- Metric Cards -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
    <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Total Tugas</p>
            <h3 class="text-2xl font-black text-gray-900 dark:text-white mt-1"><?= $totalTasks ?></h3>
        </div>
        <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl">
            <i class="fa-solid fa-list-check"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-amber-500 uppercase tracking-wider">Dalam Proses</p>
            <h3 class="text-2xl font-black text-amber-600 dark:text-amber-400 mt-1"><?= $inProgressTasks ?></h3>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl">
            <i class="fa-solid fa-spinner"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-emerald-500 uppercase tracking-wider">Selesai</p>
            <h3 class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1"><?= $doneTasks ?> (<?= $completionRate ?>%)</h3>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl">
            <i class="fa-solid fa-circle-check"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-rose-500 uppercase tracking-wider">Terlambat</p>
            <h3 class="text-2xl font-black text-rose-600 dark:text-rose-400 mt-1"><?= $overdueTasks ?></h3>
        </div>
        <div class="w-12 h-12 rounded-xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center text-xl">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
    </div>
</div>

<?php if (empty($data['tasks'])) : ?>
    <!-- Empty State -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl border-2 border-dashed border-gray-200 dark:border-slate-700 p-16 text-center shadow-sm">
        <div class="w-20 h-20 bg-indigo-50 dark:bg-indigo-950/40 rounded-2xl flex items-center justify-center mx-auto mb-6 text-indigo-600 dark:text-indigo-400 text-3xl">
            <i class="fa-solid fa-timeline"></i>
        </div>
        <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">Tidak Ada Tugas Ditemukan</h3>
        <p class="text-gray-400 dark:text-gray-500 text-sm max-w-md mx-auto mb-6">Belum ada tugas yang dijadwalkan pada project yang dipilih. Tambahkan tugas baru untuk melihat roadmap pengerjaan.</p>
        <a href="<?= BASEURL ?>/task" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 rounded-xl transition shadow-md">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Kembali ke Papan Tugas</span>
        </a>
    </div>
<?php else : ?>
    <?php
    // Hitung rentang tanggal Timeline (Min Date & Max Date)
    $allDates = [];
    $todayTs = strtotime(date('Y-m-d'));
    $allDates[] = $todayTs - (3 * 86400); // 3 hari sebelum hari ini
    $allDates[] = $todayTs + (14 * 86400); // 14 hari setelah hari ini

    foreach ($data['tasks'] as $t) {
        if (!empty($t['due_date'])) {
            $allDates[] = strtotime($t['due_date']);
        }
        if (!empty($t['created_at'])) {
            $allDates[] = strtotime(substr($t['created_at'], 0, 10));
        }
    }

    $minTs = min($allDates);
    $maxTs = max($allDates);

    // Padding tanggal awal dan akhir (tambahkan margin)
    $startDateTs = strtotime('first day of this month', $minTs);
    if (($minTs - $startDateTs) > (10 * 86400)) {
        $startDateTs = $minTs - (2 * 86400);
    }
    $endDateTs = $maxTs + (5 * 86400);

    $totalDays = max(1, round(($endDateTs - $startDateTs) / 86400));
    $colWidth = 45; // lebar per hari (px)
    $totalTimelineWidth = $totalDays * $colWidth;

    $monthsIndo = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    $daysIndo = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
    ?>

    <!-- Gantt Chart Container -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden transition-colors">
        <!-- Top Toolbar -->
        <div class="p-5 border-b border-gray-100 dark:border-slate-700 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                    <i class="fa-solid fa-calendar-week text-lg"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-gray-900 dark:text-white">Garis Waktu Project (Gantt Chart)</h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500 font-semibold">Rentang waktu: <?= date('d M Y', $startDateTs) ?> s.d. <?= date('d M Y', $endDateTs) ?> (<?= $totalDays ?> Hari)</p>
                </div>
            </div>

            <!-- Legend Pills -->
            <div class="flex flex-wrap items-center gap-3 text-xs font-bold">
                <span class="flex items-center gap-1.5 text-gray-600 dark:text-gray-300">
                    <span class="w-3 h-3 rounded-full bg-slate-400 inline-block"></span> To Do
                </span>
                <span class="flex items-center gap-1.5 text-gray-600 dark:text-gray-300">
                    <span class="w-3 h-3 rounded-full bg-amber-400 inline-block"></span> In Progress
                </span>
                <span class="flex items-center gap-1.5 text-gray-600 dark:text-gray-300">
                    <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span> Selesai
                </span>
                <span class="flex items-center gap-1.5 text-rose-600 dark:text-rose-400">
                    <span class="w-3 h-3 rounded-full bg-rose-500 inline-block animate-pulse"></span> Terlambat
                </span>
                <span class="flex items-center gap-1.5 text-indigo-600 dark:text-indigo-400">
                    <span class="w-0.5 h-3.5 bg-indigo-600 dark:bg-indigo-400 inline-block"></span> Hari Ini
                </span>
            </div>
        </div>

        <!-- Scrollable Roadmap Table -->
        <div class="overflow-x-auto relative" id="timelineScrollContainer">
            <div class="min-w-fit flex">
                <!-- Left Column: Task Info (Sticky) -->
                <div class="w-72 sm:w-80 flex-shrink-0 bg-gray-50/80 dark:bg-slate-900/80 border-r border-gray-200 dark:border-slate-700 sticky left-0 z-20 backdrop-blur-sm">
                    <!-- Column Header -->
                    <div class="h-16 px-4 flex items-center justify-between border-b border-gray-200 dark:border-slate-700 bg-gray-100/90 dark:bg-slate-800/90">
                        <span class="text-xs font-black text-gray-600 dark:text-gray-300 uppercase tracking-wider">Daftar Tugas Tim</span>
                        <span class="text-[11px] font-bold text-gray-400 bg-white dark:bg-slate-700 px-2 py-0.5 rounded-md border border-gray-200 dark:border-slate-600"><?= count($data['tasks']) ?> Item</span>
                    </div>

                    <!-- Task Rows Header/Titles -->
                    <div class="divide-y divide-gray-100 dark:divide-slate-800">
                        <?php foreach ($data['tasks'] as $t) : 
                            $isOverdue = ($t['due_date'] < $todayDate) && ($t['status'] !== 'done');
                        ?>
                            <div class="h-16 px-4 flex items-center justify-between gap-2 hover:bg-white dark:hover:bg-slate-800/60 transition group">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-1.5 mb-0.5">
                                        <?php if ($isOverdue) : ?>
                                            <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                                        <?php endif; ?>
                                        <h4 class="text-xs font-bold text-gray-800 dark:text-gray-100 truncate group-hover:text-indigo-600 transition" title="<?= htmlspecialchars($t['title']) ?>">
                                            <?= htmlspecialchars($t['title']) ?>
                                        </h4>
                                    </div>
                                    <div class="flex items-center gap-2 text-[10px] text-gray-400 dark:text-gray-500">
                                        <span class="truncate max-w-[120px] font-medium"><?= htmlspecialchars($t['project_name']) ?></span>
                                        <span>•</span>
                                        <span class="font-bold text-indigo-500"><?= $t['progress'] ?>%</span>
                                    </div>
                                </div>
                                <div class="flex-shrink-0 flex items-center gap-1.5">
                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($t['assignee_name']) ?>&background=random" class="w-6 h-6 rounded-full border border-gray-200 dark:border-slate-700" alt="Avatar" title="<?= htmlspecialchars($t['assignee_name']) ?>">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Right Column: Timeline Grid & Horizontal Bars -->
                <div class="flex-1 relative" style="width: <?= $totalTimelineWidth ?>px; min-width: <?= $totalTimelineWidth ?>px;">
                    <!-- Timeline Days Header -->
                    <div class="h-16 flex border-b border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50">
                        <?php for ($d = 0; $d < $totalDays; $d++) : 
                            $currTs = $startDateTs + ($d * 86400);
                            $currYmd = date('Y-m-d', $currTs);
                            $isToday = ($currYmd === $todayDate);
                            $dayOfWeek = (int)date('w', $currTs);
                            $isWeekend = ($dayOfWeek === 0 || $dayOfWeek === 6);
                        ?>
                            <div class="flex-shrink-0 text-center border-r border-gray-100 dark:border-slate-800/80 flex flex-col justify-center items-center <?= $isToday ? 'bg-indigo-50/70 dark:bg-indigo-950/40 text-indigo-600 font-black' : ($isWeekend ? 'bg-gray-50 dark:bg-slate-900/40 text-gray-400' : 'text-gray-500 dark:text-gray-400') ?>" style="width: <?= $colWidth ?>px;">
                                <span class="text-[9px] uppercase font-bold tracking-tight"><?= $daysIndo[$dayOfWeek] ?></span>
                                <span class="text-xs font-black <?= $isToday ? 'bg-indigo-600 text-white rounded-md px-1.5 py-0.5 shadow-sm' : '' ?>"><?= date('d', $currTs) ?></span>
                                <span class="text-[8px] opacity-60"><?= $monthsIndo[(int)date('m', $currTs)] ?></span>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <!-- Vertical Current Day Indicator Line -->
                    <?php 
                    $todayDiff = round(($todayTs - $startDateTs) / 86400);
                    if ($todayDiff >= 0 && $todayDiff < $totalDays) : 
                        $todayLeftPx = ($todayDiff * $colWidth) + ($colWidth / 2);
                    ?>
                        <div class="absolute top-0 bottom-0 z-10 pointer-events-none" style="left: <?= $todayLeftPx ?>px; width: 2px;">
                            <div class="h-full bg-indigo-500/80 dark:bg-indigo-400/80 shadow-[0_0_8px_rgba(99,102,241,0.6)]"></div>
                        </div>
                    <?php endif; ?>

                    <!-- Task Gantt Bars Rows -->
                    <div class="divide-y divide-gray-100 dark:divide-slate-800 relative">
                        <?php foreach ($data['tasks'] as $t) : 
                            $taskDueTs = !empty($t['due_date']) ? strtotime($t['due_date']) : $todayTs;
                            $taskStartTs = !empty($t['created_at']) ? strtotime(substr($t['created_at'], 0, 10)) : ($taskDueTs - (3 * 86400));
                            
                            // Jangan biarkan start > due
                            if ($taskStartTs > $taskDueTs) {
                                $taskStartTs = $taskDueTs - 86400;
                            }

                            // Calculate offset and bar width
                            $startOffsetDays = max(0, round(($taskStartTs - $startDateTs) / 86400));
                            $durationDays = max(1, round(($taskDueTs - $taskStartTs) / 86400) + 1);

                            $barLeftPx = $startOffsetDays * $colWidth;
                            $barWidthPx = max($colWidth, $durationDays * $colWidth);

                            // Status Styling
                            $isOverdue = ($t['due_date'] < $todayDate) && ($t['status'] !== 'done');
                            
                            if ($isOverdue) {
                                $barBg = 'bg-rose-500 dark:bg-rose-600 text-white shadow-rose-200 dark:shadow-none';
                                $progressBg = 'bg-rose-700';
                            } elseif ($t['status'] === 'done') {
                                $barBg = 'bg-emerald-500 dark:bg-emerald-600 text-white shadow-emerald-200 dark:shadow-none';
                                $progressBg = 'bg-emerald-700';
                            } elseif ($t['status'] === 'in_progress') {
                                $barBg = 'bg-amber-400 dark:bg-amber-500 text-amber-950 font-black shadow-amber-200 dark:shadow-none';
                                $progressBg = 'bg-amber-600';
                            } else {
                                $barBg = 'bg-indigo-500 dark:bg-indigo-600 text-white shadow-indigo-200 dark:shadow-none';
                                $progressBg = 'bg-indigo-700';
                            }
                        ?>
                            <div class="h-16 relative flex items-center group hover:bg-gray-50/50 dark:hover:bg-slate-800/40 transition">
                                <!-- Background Day Guidelines -->
                                <div class="absolute inset-0 flex pointer-events-none">
                                    <?php for ($d = 0; $d < $totalDays; $d++) : ?>
                                        <div class="flex-shrink-0 border-r border-gray-50 dark:border-slate-800/40 h-full" style="width: <?= $colWidth ?>px;"></div>
                                    <?php endfor; ?>
                                </div>

                                <!-- Gantt Bar -->
                                <div class="absolute h-9 rounded-xl <?= $barBg ?> shadow-md cursor-pointer transition-all duration-200 hover:scale-[1.01] hover:brightness-105 overflow-hidden flex items-center px-3 z-0"
                                     style="left: <?= $barLeftPx ?>px; width: <?= $barWidthPx ?>px;"
                                     title="<?= htmlspecialchars($t['title']) ?> | Progress: <?= $t['progress'] ?>% | Deadline: <?= date('d M Y', $taskDueTs) ?>">
                                    
                                    <!-- Inner Progress Bar Fill -->
                                    <div class="absolute inset-y-0 left-0 <?= $progressBg ?> opacity-30 rounded-xl pointer-events-none" style="width: <?= $t['progress'] ?>%;"></div>
                                    
                                    <!-- Bar Content -->
                                    <div class="relative z-10 flex items-center justify-between w-full min-w-0 gap-2">
                                        <span class="text-xs font-bold truncate tracking-tight">
                                            <?= htmlspecialchars($t['title']) ?>
                                        </span>
                                        <span class="text-[10px] font-black opacity-90 px-1.5 py-0.5 rounded bg-black/20 flex-shrink-0">
                                            <?= $t['progress'] ?>%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Teleport action buttons to header
        const actionArea = document.getElementById('header-action-area');
        const actionContent = document.getElementById('timeline-action-content');
        if (actionArea && actionContent) {
            actionArea.innerHTML = '';
            while (actionContent.firstChild) {
                actionArea.appendChild(actionContent.firstChild);
            }
        }

        // Auto-scroll timeline to current day on load
        const scrollContainer = document.getElementById('timelineScrollContainer');
        if (scrollContainer) {
            const todayIndicator = scrollContainer.querySelector('.shadow-\\[0_0_8px_rgba\\(99\\,102\\,241\\,0\\.6\\)\\]');
            if (todayIndicator) {
                const scrollLeftPos = Math.max(0, todayIndicator.parentElement.offsetLeft - 320);
                scrollContainer.scrollLeft = scrollLeftPos;
            }
        }
    });
</script>
