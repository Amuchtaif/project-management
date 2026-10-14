<!-- Timeline / Roadmap View -->
<div id="timeline-action-content" class="hidden">
    <div class="flex items-center gap-2.5">
        <!-- View Switcher -->
        <div class="flex items-center bg-gray-100 dark:bg-slate-800 p-1 rounded-xl border border-gray-200 dark:border-slate-700 shadow-2xs">
            <a href="<?= BASEURL ?>/task<?= isset($_GET['project_id']) ? '?project_id='.$_GET['project_id'] : '' ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition" title="Tampilan Papan Kanban">
                <i class="fa-solid fa-list-check text-xs"></i>
                <span>Papan</span>
            </a>
            <a href="<?= BASEURL ?>/task/calendar<?= isset($_GET['project_id']) ? '?project_id='.$_GET['project_id'] : '' ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition" title="Tampilan Kalender">
                <i class="fa-solid fa-calendar-days text-xs"></i>
                <span>Kalender</span>
            </a>
            <a href="<?= BASEURL ?>/task/timeline<?= isset($_GET['project_id']) ? '?project_id='.$_GET['project_id'] : '' ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-2xs" title="Tampilan Roadmap">
                <i class="fa-solid fa-timeline text-xs"></i>
                <span>Roadmap</span>
            </a>
        </div>

        <!-- Project Filter -->
        <div class="relative custom-dropdown" id="timelineProjectDropdown">
            <button onclick="toggleDropdown('timelineProjectDropdown')" class="flex items-center gap-2 bg-white dark:bg-slate-800 px-3.5 py-2 rounded-xl border border-gray-200 dark:border-slate-700 shadow-2xs hover:bg-gray-50 dark:hover:bg-slate-700 transition text-xs font-semibold text-gray-700 dark:text-gray-200">
                <span class="text-[11px] text-gray-400 dark:text-gray-500 font-medium">Project:</span>
                <span class="truncate max-w-[140px]">
                    <?php 
                        $selectedProj = 'Semua Project';
                        if(isset($_GET['project_id']) && !empty($_GET['project_id'])) {
                            foreach($data['projects'] as $p) {
                                if($p['id'] == $_GET['project_id']) $selectedProj = $p['name'];
                            }
                        }
                        echo htmlspecialchars($selectedProj);
                    ?>
                </span>
                <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 ml-0.5"></i>
            </button>
            <div class="dropdown-menu absolute top-full right-0 mt-2 w-56 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-xl py-1.5 hidden z-[100] max-h-60 overflow-y-auto">
                <a href="<?= BASEURL ?>/task/timeline" class="block w-full text-left px-4 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition <?= !isset($_GET['project_id']) || empty($_GET['project_id']) ? 'font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50/50 dark:bg-slate-700' : 'font-medium' ?>">Semua Project</a>
                <?php foreach($data['projects'] as $p) : ?>
                    <a href="<?= BASEURL ?>/task/timeline?project_id=<?= $p['id'] ?>" class="block w-full text-left px-4 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition <?= (isset($_GET['project_id']) && $_GET['project_id'] == $p['id']) ? 'font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50/50 dark:bg-slate-700' : 'font-medium' ?>"><?= htmlspecialchars($p['name']) ?></a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Export CSV -->
        <a href="<?= BASEURL ?>/task/exportCsv<?= isset($_GET['project_id']) ? '?project_id='.$_GET['project_id'] : '' ?>" class="flex items-center gap-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-xl text-xs font-semibold hover:bg-gray-50 dark:hover:bg-slate-700 transition shadow-2xs" title="Ekspor daftar tugas ke CSV">
            <i class="fa-solid fa-file-excel text-emerald-600"></i>
            <span>Ekspor CSV</span>
        </a>
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

<!-- Metric Stats Strip (Consistent Enterprise Cards with Background Watermark Icons) -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 mb-6">
    <!-- Card 1: Total Tugas -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-4 shadow-2xs hover:border-indigo-200 dark:hover:border-slate-600 transition-all duration-300 group">
        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-5xl sm:text-6xl text-indigo-600/10 dark:text-indigo-400/15 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300 pointer-events-none select-none">
            <i class="fa-solid fa-list-check"></i>
        </div>
        <div class="relative z-10">
            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 block">Total Tugas</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100"><?= $totalTasks ?></span>
                <span class="text-[11px] font-medium text-gray-400 dark:text-gray-500">tugas tercatat</span>
            </div>
        </div>
    </div>

    <!-- Card 2: Dalam Proses -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-4 shadow-2xs hover:border-amber-200 dark:hover:border-slate-600 transition-all duration-300 group">
        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-5xl sm:text-6xl text-amber-600/10 dark:text-amber-400/15 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300 pointer-events-none select-none">
            <i class="fa-solid fa-bolt"></i>
        </div>
        <div class="relative z-10">
            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 block">Dalam Proses</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-bold text-amber-700 dark:text-amber-300"><?= $inProgressTasks ?></span>
                <span class="text-[11px] font-medium text-amber-600/70 dark:text-amber-400/70">Sedang Dikerjakan</span>
            </div>
        </div>
    </div>

    <!-- Card 3: Selesai -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-4 shadow-2xs hover:border-emerald-200 dark:hover:border-slate-600 transition-all duration-300 group">
        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-5xl sm:text-6xl text-emerald-600/10 dark:text-emerald-400/15 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300 pointer-events-none select-none">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="relative z-10">
            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 block">Selesai</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-bold text-emerald-700 dark:text-emerald-300"><?= $doneTasks ?></span>
                <span class="text-[11px] font-medium text-emerald-600/70 dark:text-emerald-400/70"><?= $completionRate ?>% selesai</span>
            </div>
        </div>
    </div>

    <!-- Card 4: Terlambat -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-4 shadow-2xs hover:border-rose-200 dark:hover:border-slate-600 transition-all duration-300 group">
        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-5xl sm:text-6xl text-rose-600/10 dark:text-rose-400/15 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300 pointer-events-none select-none">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div class="relative z-10">
            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 block">Terlambat</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-bold text-rose-700 dark:text-rose-300"><?= $overdueTasks ?></span>
                <span class="text-[11px] font-medium text-rose-600/70 dark:text-rose-400/70">Melewati Deadline</span>
            </div>
        </div>
    </div>
</div>

<?php if (empty($data['tasks'])) : ?>
    <!-- Empty State -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-12 text-center shadow-2xs">
        <div class="w-14 h-14 bg-indigo-50 dark:bg-indigo-950/40 rounded-xl flex items-center justify-center mx-auto mb-4 text-indigo-600 dark:text-indigo-400 text-xl">
            <i class="fa-solid fa-timeline"></i>
        </div>
        <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100 mb-1">Tidak Ada Tugas Ditemukan</h3>
        <p class="text-gray-400 dark:text-gray-500 text-xs max-w-sm mx-auto mb-5">Belum ada tugas yang dijadwalkan pada project ini. Tambahkan tugas baru untuk melihat roadmap pengerjaan.</p>
        <a href="<?= BASEURL ?>/task" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-xl text-xs transition shadow-2xs">
            <i class="fa-solid fa-arrow-left text-[10px]"></i>
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
    $colWidth = 44; // lebar per hari (px)
    $totalTimelineWidth = $totalDays * $colWidth;

    $monthsIndo = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    $daysIndo = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
    ?>

    <!-- Gantt Chart Container -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 shadow-2xs overflow-hidden transition-colors">
        <!-- Top Toolbar -->
        <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-slate-700/80 flex flex-wrap items-center justify-between gap-3.5">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-timeline"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">Garis Waktu Project (Roadmap Gantt)</h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500 font-normal">Rentang: <?= date('d M Y', $startDateTs) ?> s.d. <?= date('d M Y', $endDateTs) ?> (<?= $totalDays ?> Hari)</p>
                </div>
            </div>

            <!-- Legend Pills -->
            <div class="flex flex-wrap items-center gap-3 text-xs text-gray-600 dark:text-gray-300">
                <span class="flex items-center gap-1.5 font-medium">
                    <span class="w-2 h-2 rounded-full bg-slate-400 inline-block"></span> To Do
                </span>
                <span class="flex items-center gap-1.5 font-medium">
                    <span class="w-2 h-2 rounded-full bg-amber-400 inline-block"></span> In Progress
                </span>
                <span class="flex items-center gap-1.5 font-medium">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span> Selesai
                </span>
                <span class="flex items-center gap-1.5 font-medium text-rose-600 dark:text-rose-400">
                    <span class="w-2 h-2 rounded-full bg-rose-500 inline-block"></span> Terlambat
                </span>
                <span class="flex items-center gap-1.5 font-semibold text-indigo-600 dark:text-indigo-400 border-l border-gray-200 dark:border-slate-700 pl-3">
                    <span class="w-1.5 h-3 bg-indigo-600 dark:bg-indigo-400 rounded-full inline-block"></span> Hari Ini
                </span>
            </div>
        </div>

        <!-- Scrollable Roadmap Table -->
        <div class="overflow-x-auto relative" id="timelineScrollContainer">
            <div class="min-w-fit flex">
                <!-- Left Column: Task Info (Sticky Opaque) -->
                <div class="w-72 sm:w-80 flex-shrink-0 bg-white dark:bg-slate-800 border-r border-gray-200 dark:border-slate-700 sticky left-0 z-20 shadow-xs">
                    <!-- Column Header -->
                    <div class="h-14 px-4 flex items-center justify-between border-b border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900">
                        <span class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Daftar Tugas</span>
                        <span class="text-[11px] font-semibold text-gray-500 dark:text-gray-400 bg-white dark:bg-slate-800 px-2 py-0.5 rounded-md border border-gray-200 dark:border-slate-700"><?= count($data['tasks']) ?> Tugas</span>
                    </div>

                    <!-- Task Rows Header/Titles -->
                    <div class="divide-y divide-gray-100 dark:divide-slate-700/60 bg-white dark:bg-slate-800">
                        <?php foreach ($data['tasks'] as $t) : 
                            $isOverdue = ($t['due_date'] < $todayDate) && ($t['status'] !== 'done');
                        ?>
                            <div class="h-14 px-4 flex items-center justify-between gap-2 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700/40 transition group">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-1.5">
                                        <?php if ($isOverdue) : ?>
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 flex-shrink-0" title="Terlambat"></span>
                                        <?php endif; ?>
                                        <h4 class="text-xs font-semibold text-gray-800 dark:text-gray-200 truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition" title="<?= htmlspecialchars($t['title']) ?>">
                                            <?= htmlspecialchars($t['title']) ?>
                                        </h4>
                                    </div>
                                    <div class="flex items-center gap-2 text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">
                                        <span class="truncate max-w-[130px] font-medium"><?= htmlspecialchars($t['project_name']) ?></span>
                                        <span>•</span>
                                        <span class="font-semibold text-indigo-600 dark:text-indigo-400"><?= $t['progress'] ?>%</span>
                                    </div>
                                </div>
                                <div class="flex-shrink-0 flex items-center">
                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($t['assignee_name']) ?>&background=random" class="w-6 h-6 rounded-full border border-gray-200 dark:border-slate-700" alt="Avatar" title="<?= htmlspecialchars($t['assignee_name']) ?>">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Right Column: Timeline Grid & Horizontal Bars -->
                <div class="flex-1 relative" style="width: <?= $totalTimelineWidth ?>px; min-width: <?= $totalTimelineWidth ?>px;">
                    <!-- Timeline Days Header -->
                    <div class="h-14 flex border-b border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50">
                        <?php for ($d = 0; $d < $totalDays; $d++) : 
                            $currTs = $startDateTs + ($d * 86400);
                            $currYmd = date('Y-m-d', $currTs);
                            $isToday = ($currYmd === $todayDate);
                            $dayOfWeek = (int)date('w', $currTs);
                            $isWeekend = ($dayOfWeek === 0 || $dayOfWeek === 6);
                        ?>
                            <div class="flex-shrink-0 text-center border-r border-gray-100 dark:border-slate-800 flex flex-col justify-center items-center <?= $isToday ? 'bg-indigo-50/80 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 font-bold' : ($isWeekend ? 'bg-gray-50/60 dark:bg-slate-900/40 text-gray-400' : 'text-gray-500 dark:text-gray-400') ?>" style="width: <?= $colWidth ?>px;">
                                <span class="text-[9px] uppercase font-semibold"><?= $daysIndo[$dayOfWeek] ?></span>
                                <span class="text-xs font-bold <?= $isToday ? 'bg-indigo-600 text-white rounded px-1.5 py-0.2' : '' ?>"><?= date('d', $currTs) ?></span>
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
                            <div class="h-full bg-indigo-500 dark:bg-indigo-400"></div>
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
                                $barBg = 'bg-rose-500 dark:bg-rose-600 text-white';
                                $progressBg = 'bg-rose-700';
                            } elseif ($t['status'] === 'done') {
                                $barBg = 'bg-emerald-600 dark:bg-emerald-500 text-white';
                                $progressBg = 'bg-emerald-800';
                            } elseif ($t['status'] === 'in_progress') {
                                $barBg = 'bg-amber-500 text-white';
                                $progressBg = 'bg-amber-700';
                            } else {
                                $barBg = 'bg-indigo-600 dark:bg-indigo-500 text-white';
                                $progressBg = 'bg-indigo-800';
                            }
                        ?>
                            <div class="h-14 relative flex items-center group hover:bg-gray-50/50 dark:hover:bg-slate-800/40 transition">
                                <!-- Background Day Guidelines -->
                                <div class="absolute inset-0 flex pointer-events-none">
                                    <?php for ($d = 0; $d < $totalDays; $d++) : ?>
                                        <div class="flex-shrink-0 border-r border-gray-100 dark:border-slate-800/60 h-full" style="width: <?= $colWidth ?>px;"></div>
                                    <?php endfor; ?>
                                </div>

                                <!-- Gantt Bar -->
                                <div class="absolute h-8 rounded-lg <?= $barBg ?> shadow-2xs cursor-pointer transition-all duration-150 hover:brightness-105 overflow-hidden flex items-center px-2.5 z-0"
                                     style="left: <?= $barLeftPx ?>px; width: <?= $barWidthPx ?>px;"
                                     title="<?= htmlspecialchars($t['title']) ?> | Progress: <?= $t['progress'] ?>% | Deadline: <?= date('d M Y', $taskDueTs) ?>">
                                    
                                    <!-- Inner Progress Bar Fill -->
                                    <div class="absolute inset-y-0 left-0 <?= $progressBg ?> opacity-30 rounded-lg pointer-events-none" style="width: <?= $t['progress'] ?>%;"></div>
                                    
                                    <!-- Bar Content -->
                                    <div class="relative z-10 flex items-center justify-between w-full min-w-0 gap-1.5">
                                        <?php if ($barWidthPx >= 90) : ?>
                                            <span class="text-xs font-semibold truncate tracking-tight">
                                                <?= htmlspecialchars($t['title']) ?>
                                            </span>
                                        <?php endif; ?>
                                        <span class="text-[10px] font-bold opacity-90 px-1 py-0.2 rounded bg-black/20 flex-shrink-0 <?= $barWidthPx < 90 ? 'mx-auto' : '' ?>">
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
            const todayIndicator = scrollContainer.querySelector('.bg-indigo-500');
            if (todayIndicator) {
                const scrollLeftPos = Math.max(0, todayIndicator.parentElement.offsetLeft - 320);
                scrollContainer.scrollLeft = scrollLeftPos;
            }
        }
    });
</script>
