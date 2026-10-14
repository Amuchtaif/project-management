<?php
// app/views/task/calendar.php

// Indonesian Date Constants & Helper Variables
$indoMonths = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];
$indoDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

$currMonth = $data['calendar_month'];
$currYear = $data['calendar_year'];

// Previous Month & Year
$prevMonth = $currMonth == 1 ? 12 : $currMonth - 1;
$prevYear = $currMonth == 1 ? $currYear - 1 : $currYear;

// Next Month & Year
$nextMonth = $currMonth == 12 ? 1 : $currMonth + 1;
$nextYear = $currMonth == 12 ? $currYear + 1 : $currYear;

// Calendar Calculation
$daysInMonth = (int)date('t', mktime(0, 0, 0, $currMonth, 1, $currYear));
$firstDayOfWeek = (int)date('N', mktime(0, 0, 0, $currMonth, 1, $currYear)); // 1 (Mon) to 7 (Sun)
$leadingDays = $firstDayOfWeek - 1;

$daysInPrevMonth = (int)date('t', mktime(0, 0, 0, $prevMonth, 1, $prevYear));
$prevStartDay = $daysInPrevMonth - $leadingDays + 1;

$totalCells = $leadingDays + $daysInMonth;
$trailingDays = ($totalCells % 7 == 0) ? 0 : 7 - ($totalCells % 7);
$totalGridCells = $totalCells + $trailingDays;

// Current Date string
$todayDate = date('Y-m-d');

// Helper URL builders
$activeProjectId = $data['active_project_id'] ?? '';
$searchQuery = $data['search'] ?? '';
$statusFilter = $data['selected_status'] ?? 'all';

function buildCalUrl($month, $year, $proj = '', $status = 'all', $search = '') {
    $params = ['month' => $month, 'year' => $year];
    if (!empty($proj)) $params['project_id'] = $proj;
    if (!empty($status) && $status !== 'all') $params['status'] = $status;
    if (!empty($search)) $params['search'] = $search;
    return BASEURL . '/task/calendar?' . http_build_query($params);
}

$currentCalUrl = buildCalUrl($currMonth, $currYear, $activeProjectId, $statusFilter, $searchQuery);
?>

<!-- ACTION CONTENT TELEPORTED TO TOPBAR -->
<div id="task-action-content" class="hidden">
    <div class="flex items-center gap-2.5">
        <!-- View Switcher (Papan / Kalender) -->
        <div class="flex items-center bg-gray-100 dark:bg-slate-800 p-1 rounded-xl border border-gray-200 dark:border-slate-700 shadow-2xs">
            <a href="<?= BASEURL ?>/task<?= !empty($activeProjectId) ? '?project_id='.$activeProjectId : '' ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition" title="Tampilan Papan Kanban">
                <i class="fa-solid fa-list-check text-xs"></i>
                <span>Papan</span>
            </a>
            <a href="<?= $currentCalUrl ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-2xs" title="Tampilan Kalender">
                <i class="fa-solid fa-calendar-days text-xs"></i>
                <span>Kalender</span>
            </a>
        </div>

        <!-- Add Task Button -->
        <button onclick="openAddTaskModal()" type="button" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition flex items-center justify-center gap-2 shadow-sm shadow-indigo-200 dark:shadow-none">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Tambah Tugas</span>
        </button>
    </div>
</div>

<!-- Task Metric Stats Strip -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 mb-6">
    <!-- Card 1: Total Tugas -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-4 shadow-2xs hover:border-indigo-200 dark:hover:border-slate-600 transition-all duration-300 group">
        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-5xl sm:text-6xl text-indigo-600/10 dark:text-indigo-400/15 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300 pointer-events-none select-none">
            <i class="fa-solid fa-calendar-days"></i>
        </div>
        <div class="relative z-10">
            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 block">Total Tugas</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100"><?= $data['month_stats']['total']; ?></span>
                <span class="text-[11px] font-medium text-gray-400 dark:text-gray-500">tugas bulan ini</span>
            </div>
        </div>
    </div>

    <!-- Card 2: To Do -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-4 shadow-2xs hover:border-amber-200 dark:hover:border-slate-600 transition-all duration-300 group">
        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-5xl sm:text-6xl text-amber-600/10 dark:text-amber-400/15 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300 pointer-events-none select-none">
            <i class="fa-solid fa-clock"></i>
        </div>
        <div class="relative z-10">
            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 block">To Do</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-bold text-amber-700 dark:text-amber-300"><?= $data['month_stats']['todo']; ?></span>
                <span class="text-[11px] font-medium text-amber-600/70 dark:text-amber-400/70">Perlu Dikerjakan</span>
            </div>
        </div>
    </div>

    <!-- Card 3: In Progress -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-4 shadow-2xs hover:border-indigo-200 dark:hover:border-slate-600 transition-all duration-300 group">
        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-5xl sm:text-6xl text-indigo-600/10 dark:text-indigo-400/15 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300 pointer-events-none select-none">
            <i class="fa-solid fa-bolt"></i>
        </div>
        <div class="relative z-10">
            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 block">In Progress</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-bold text-indigo-700 dark:text-indigo-300"><?= $data['month_stats']['in_progress']; ?></span>
                <span class="text-[11px] font-medium text-indigo-600/70 dark:text-indigo-400/70">Dalam Proses</span>
            </div>
        </div>
    </div>

    <!-- Card 4: Selesai -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-4 shadow-2xs hover:border-emerald-200 dark:hover:border-slate-600 transition-all duration-300 group">
        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-5xl sm:text-6xl text-emerald-600/10 dark:text-emerald-400/15 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300 pointer-events-none select-none">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="relative z-10">
            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 block">Selesai</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-bold text-emerald-700 dark:text-emerald-300"><?= $data['month_stats']['done']; ?></span>
                <span class="text-[11px] font-medium text-emerald-600/70 dark:text-emerald-400/70">Tuntas</span>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filtering Toolbar -->
<div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 p-4 mb-5 shadow-2xs transition-colors">
    <div class="flex flex-col lg:flex-row gap-3.5 items-stretch lg:items-center justify-between">
        <!-- Search Input -->
        <div class="relative flex-grow max-w-xl">
            <input type="text" id="calSearch" placeholder="Cari judul tugas..." value="<?= htmlspecialchars($searchQuery, ENT_QUOTES); ?>"
                class="w-full bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl pl-10 pr-9 py-2.5 text-sm text-gray-800 dark:text-gray-200 placeholder-gray-400 focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition"
                onkeypress="if(event.key === 'Enter') applyCalFilter('search', this.value)">
            <i class="fa-solid fa-magnifying-glass text-gray-400 dark:text-gray-500 absolute left-3.5 top-1/2 -translate-y-1/2 text-sm pointer-events-none"></i>
            <?php if(!empty($searchQuery)) : ?>
                <button type="button" onclick="applyCalFilter('search', '')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            <?php endif; ?>
        </div>

        <!-- Filter Controls Group -->
        <div class="flex flex-wrap items-center gap-2.5">
            <!-- Project Filter -->
            <div class="relative min-w-[160px]">
                <select onchange="applyCalFilter('project_id', this.value)" class="w-full appearance-none bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl pl-3 pr-8 py-2.5 text-xs font-semibold text-gray-700 dark:text-gray-300 outline-none focus:border-indigo-500 cursor-pointer">
                    <option value="">Semua Project</option>
                    <?php foreach($data['projects'] as $p) : ?>
                        <option value="<?= $p['id'] ?>" <?= ($activeProjectId == $p['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <i class="fa-solid fa-chevron-down text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 text-[10px] pointer-events-none"></i>
            </div>

            <!-- Status Filter -->
            <div class="relative min-w-[130px]">
                <select onchange="applyCalFilter('status', this.value)" class="w-full appearance-none bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl pl-3 pr-8 py-2.5 text-xs font-semibold text-gray-700 dark:text-gray-300 outline-none focus:border-indigo-500 cursor-pointer">
                    <option value="all" <?= ($statusFilter === 'all') ? 'selected' : '' ?>>Semua Status</option>
                    <option value="todo" <?= ($statusFilter === 'todo') ? 'selected' : '' ?>>To Do</option>
                    <option value="in_progress" <?= ($statusFilter === 'in_progress') ? 'selected' : '' ?>>In Progress</option>
                    <option value="done" <?= ($statusFilter === 'done') ? 'selected' : '' ?>>Selesai</option>
                </select>
                <i class="fa-solid fa-chevron-down text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 text-[10px] pointer-events-none"></i>
            </div>

            <!-- Reset Filter Button -->
            <?php if(!empty($activeProjectId) || (!empty($statusFilter) && $statusFilter !== 'all') || !empty($searchQuery)) : ?>
                <a href="<?= buildCalUrl($currMonth, $currYear) ?>" class="px-3 py-2 text-xs font-semibold text-rose-600 hover:text-rose-700 dark:text-rose-400 transition flex items-center gap-1" title="Bersihkan filter">
                    <i class="fa-solid fa-rotate-left"></i>
                    <span>Reset</span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- CALENDAR & UPCOMING TASKS CONTAINER -->
<div class="grid grid-cols-1 xl:grid-cols-12 gap-5 items-start">
    <!-- MAIN CALENDAR CONTAINER -->
    <div class="xl:col-span-8 2xl:col-span-9 bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 shadow-2xs overflow-hidden transition-colors">
        <!-- CALENDAR TOP BAR / CONTROLS -->
        <div class="p-4 md:px-5 border-b border-gray-200 dark:border-slate-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-3.5 bg-white dark:bg-slate-800">
            <!-- Month Navigation -->
            <div class="flex items-center gap-2">
                <a href="<?= buildCalUrl($prevMonth, $prevYear, $activeProjectId, $statusFilter, $searchQuery) ?>" 
                   class="p-2 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition" title="Bulan Sebelumnya">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </a>
                
                <h2 class="text-base md:text-lg font-bold text-gray-900 dark:text-gray-100 tracking-tight px-1">
                    <?= $indoMonths[$currMonth]; ?> <span class="font-normal text-gray-500 dark:text-gray-400"><?= $currYear; ?></span>
                </h2>

                <a href="<?= buildCalUrl($nextMonth, $nextYear, $activeProjectId, $statusFilter, $searchQuery) ?>" 
                   class="p-2 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition" title="Bulan Selanjutnya">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </a>

                <!-- Jump to Today -->
                <a href="<?= buildCalUrl((int)date('n'), (int)date('Y'), $activeProjectId, $statusFilter, $searchQuery) ?>" 
                   class="ml-1.5 px-2.5 py-1 text-xs font-semibold rounded-lg bg-gray-50 dark:bg-slate-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 border border-gray-200 dark:border-slate-700 transition">
                    Hari Ini
                </a>
            </div>

            <!-- Jump Selectors & Legends -->
            <div class="flex flex-wrap items-center gap-2.5">
                <!-- Quick Month Jump -->
                <div class="relative">
                    <select onchange="applyCalFilter('month', this.value)" class="appearance-none bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg pl-2.5 pr-7 py-1.5 text-xs font-semibold text-gray-700 dark:text-gray-300 outline-none focus:border-indigo-500 cursor-pointer">
                        <?php foreach($indoMonths as $mNum => $mName) : ?>
                            <option value="<?= $mNum; ?>" <?= ($mNum == $currMonth) ? 'selected' : ''; ?>><?= $mName; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <i class="fa-solid fa-chevron-down text-gray-400 absolute right-2.5 top-1/2 -translate-y-1/2 text-[9px] pointer-events-none"></i>
                </div>

                <!-- Quick Year Jump -->
                <div class="relative">
                    <select onchange="applyCalFilter('year', this.value)" class="appearance-none bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg pl-2.5 pr-7 py-1.5 text-xs font-semibold text-gray-700 dark:text-gray-300 outline-none focus:border-indigo-500 cursor-pointer">
                        <?php for($y = (int)date('Y') - 2; $y <= (int)date('Y') + 3; $y++) : ?>
                            <option value="<?= $y; ?>" <?= ($y == $currYear) ? 'selected' : ''; ?>><?= $y; ?></option>
                        <?php endfor; ?>
                    </select>
                    <i class="fa-solid fa-chevron-down text-gray-400 absolute right-2.5 top-1/2 -translate-y-1/2 text-[9px] pointer-events-none"></i>
                </div>

                <!-- Status Legends -->
                <div class="hidden sm:flex items-center gap-3 pl-2.5 border-l border-gray-200 dark:border-slate-700 text-xs font-medium text-gray-500 dark:text-gray-400">
                    <span class="inline-flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span> To Do
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span> In Progress
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Selesai
                    </span>
                </div>
            </div>
        </div>

        <!-- CALENDAR GRID -->
        <div class="overflow-x-auto">
            <div class="min-w-[760px]">
                <!-- Day of Week Header -->
                <div class="grid grid-cols-7 border-b border-gray-200 dark:border-slate-700 bg-gray-50/80 dark:bg-slate-800/90 text-center">
                    <?php foreach($indoDays as $idx => $dayName) : ?>
                        <div class="py-2.5 px-2 text-[11px] font-bold uppercase tracking-wider <?= ($idx >= 5) ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' ?>">
                            <?= $dayName; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Date Cells Grid -->
                <div class="grid grid-cols-7 auto-rows-fr divide-x divide-y divide-gray-200 dark:divide-slate-700/80">
                    <?php
                    // 1. LEADING CELLS (Previous Month)
                    for ($d = $prevStartDay; $d <= $daysInPrevMonth; $d++) {
                        $prevDateString = sprintf('%04d-%02d-%02d', $prevYear, $prevMonth, $d);
                        $cellTasks = $data['tasks_by_date'][$prevDateString] ?? [];
                    ?>
                        <div class="min-h-[105px] md:min-h-[125px] p-2 bg-gray-50/40 dark:bg-slate-900/30 opacity-50 hover:opacity-100 transition group flex flex-col justify-between">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold text-gray-400 dark:text-gray-600 ml-1"><?= $d; ?></span>
                                <button onclick="openAddTaskModal('<?= $prevDateString ?>')" class="opacity-0 group-hover:opacity-100 p-1 text-gray-400 hover:text-indigo-600 transition" title="Tambah Tugas">
                                    <i class="fa-solid fa-plus text-xs"></i>
                                </button>
                            </div>
                            <div class="space-y-1 mt-1 flex-grow">
                                <?php foreach(array_slice($cellTasks, 0, 2) as $task) : ?>
                                    <div onclick="openTaskDetail(<?= htmlspecialchars(json_encode($task), ENT_QUOTES, 'UTF-8') ?>)" 
                                         class="text-[11px] p-1 px-1.5 rounded border-l-2 truncate cursor-pointer transition bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 <?= $task['status'] === 'done' ? 'border-emerald-500' : ($task['status'] === 'in_progress' ? 'border-indigo-500' : 'border-amber-500') ?>">
                                        <span class="font-semibold truncate"><?= htmlspecialchars($task['title']) ?></span>
                                    </div>
                                <?php endforeach; ?>
                                <?php if(count($cellTasks) > 2) : ?>
                                    <span class="text-[10px] text-gray-400 font-medium block text-center">+<?= count($cellTasks) - 2 ?> lainnya</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php
                    }

                    // 2. CURRENT MONTH CELLS
                    for ($d = 1; $d <= $daysInMonth; $d++) {
                        $dateString = sprintf('%04d-%02d-%02d', $currYear, $currMonth, $d);
                        $isToday = ($dateString === $todayDate);
                        $cellTasks = $data['tasks_by_date'][$dateString] ?? [];
                        $taskCount = count($cellTasks);
                    ?>
                        <div class="min-h-[105px] md:min-h-[130px] p-2 bg-white dark:bg-slate-800 hover:bg-gray-50/60 dark:hover:bg-slate-700/20 transition group flex flex-col justify-between relative <?= $isToday ? 'bg-indigo-50/30 dark:bg-indigo-950/20' : '' ?>">
                            <!-- Date Header -->
                            <div class="flex items-center justify-between mb-1.5">
                                <?php if ($isToday) : ?>
                                    <span class="w-6 h-6 rounded-full bg-indigo-600 text-white font-bold text-xs flex items-center justify-center shadow-xs">
                                        <?= $d; ?>
                                    </span>
                                <?php else : ?>
                                    <span class="text-xs font-semibold text-gray-700 dark:text-gray-300 ml-1"><?= $d; ?></span>
                                <?php endif; ?>

                                <div class="flex items-center gap-1">
                                    <?php if ($taskCount > 0) : ?>
                                        <span class="text-[10px] font-semibold text-gray-400 dark:text-gray-500 mr-0.5"><?= $taskCount; ?></span>
                                    <?php endif; ?>
                                    <button onclick="openAddTaskModal('<?= $dateString ?>')" class="opacity-0 group-hover:opacity-100 p-1 text-gray-400 hover:text-indigo-600 hover:bg-gray-100 dark:hover:bg-slate-700 rounded transition" title="Tambah Tugas pada <?= $d . ' ' . $indoMonths[$currMonth] ?>">
                                        <i class="fa-solid fa-plus text-xs"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Tasks List in Cell -->
                            <div class="space-y-1 flex-grow">
                                <?php 
                                $displayLimit = 3;
                                $displayed = 0;
                                foreach($cellTasks as $task) :
                                    if ($displayed >= $displayLimit) break;
                                    $displayed++;

                                    // Status style
                                    $statusBorder = 'border-amber-500';
                                    $statusBg = 'bg-amber-50/70 dark:bg-amber-950/30 text-amber-900 dark:text-amber-200';
                                    if ($task['status'] === 'done') {
                                        $statusBorder = 'border-emerald-500';
                                        $statusBg = 'bg-emerald-50/70 dark:bg-emerald-950/30 text-emerald-900 dark:text-emerald-200';
                                    } elseif ($task['status'] === 'in_progress') {
                                        $statusBorder = 'border-indigo-500';
                                        $statusBg = 'bg-indigo-50/70 dark:bg-indigo-950/30 text-indigo-900 dark:text-indigo-200';
                                    }

                                    // Priority dot
                                    $prioDot = 'bg-emerald-500';
                                    if (($task['priority'] ?? '') === 'tinggi') $prioDot = 'bg-rose-500';
                                    elseif (($task['priority'] ?? '') === 'sedang') $prioDot = 'bg-amber-500';
                                ?>
                                    <div onclick="openTaskDetail(<?= htmlspecialchars(json_encode($task), ENT_QUOTES, 'UTF-8') ?>)"
                                         class="p-1 px-1.5 rounded border-l-2 <?= $statusBorder ?> <?= $statusBg ?> cursor-pointer hover:shadow-xs transition flex items-center justify-between gap-1 group/item">
                                        <div class="flex items-center gap-1.5 min-w-0 flex-grow">
                                            <span class="w-1.5 h-1.5 rounded-full <?= $prioDot ?> flex-shrink-0"></span>
                                            <span class="text-[11px] font-medium truncate leading-tight"><?= htmlspecialchars($task['title']) ?></span>
                                        </div>
                                        <?php if (!empty($task['progress'])) : ?>
                                            <span class="text-[9px] font-semibold opacity-70 flex-shrink-0"><?= $task['progress'] ?>%</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>

                                <!-- More tasks badge -->
                                <?php if ($taskCount > $displayLimit) : 
                                    $remaining = $taskCount - $displayLimit;
                                    $encodedAll = htmlspecialchars(json_encode($cellTasks), ENT_QUOTES, 'UTF-8');
                                    $fullDateLabel = $d . ' ' . $indoMonths[$currMonth] . ' ' . $currYear;
                                ?>
                                    <button onclick="openDayTasksModal('<?= $fullDateLabel ?>', '<?= $dateString ?>', <?= $encodedAll ?>)" 
                                            class="w-full text-center py-0.5 rounded text-[10px] font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/40 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 transition">
                                        +<?= $remaining ?> tugas lagi
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php
                    }

                    // 3. TRAILING CELLS (Next Month)
                    for ($d = 1; $d <= $trailingDays; $d++) {
                        $nextDateString = sprintf('%04d-%02d-%02d', $nextYear, $nextMonth, $d);
                        $cellTasks = $data['tasks_by_date'][$nextDateString] ?? [];
                    ?>
                        <div class="min-h-[105px] md:min-h-[125px] p-2 bg-gray-50/40 dark:bg-slate-900/30 opacity-50 hover:opacity-100 transition group flex flex-col justify-between">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold text-gray-400 dark:text-gray-600 ml-1"><?= $d; ?></span>
                                <button onclick="openAddTaskModal('<?= $nextDateString ?>')" class="opacity-0 group-hover:opacity-100 p-1 text-gray-400 hover:text-indigo-600 transition" title="Tambah Tugas">
                                    <i class="fa-solid fa-plus text-xs"></i>
                                </button>
                            </div>
                            <div class="space-y-1 mt-1 flex-grow">
                                <?php foreach(array_slice($cellTasks, 0, 2) as $task) : ?>
                                    <div onclick="openTaskDetail(<?= htmlspecialchars(json_encode($task), ENT_QUOTES, 'UTF-8') ?>)" 
                                         class="text-[11px] p-1 px-1.5 rounded border-l-2 truncate cursor-pointer transition bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 <?= $task['status'] === 'done' ? 'border-emerald-500' : ($task['status'] === 'in_progress' ? 'border-indigo-500' : 'border-amber-500') ?>">
                                        <span class="font-semibold truncate"><?= htmlspecialchars($task['title']) ?></span>
                                    </div>
                                <?php endforeach; ?>
                                <?php if(count($cellTasks) > 2) : ?>
                                    <span class="text-[10px] text-gray-400 font-medium block text-center">+<?= count($cellTasks) - 2 ?> lainnya</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- UPCOMING TASKS SIDEBAR CARD -->
    <div class="xl:col-span-4 2xl:col-span-3 bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700/80 shadow-2xs p-4 transition-colors xl:sticky xl:top-24 flex flex-col">
        <!-- Card Header -->
        <div class="flex items-center justify-between pb-3.5 border-b border-gray-100 dark:border-slate-700">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-gray-100 text-sm leading-tight">Tugas Mendatang</h3>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500">Batas waktu & jadwal terdekat</p>
                </div>
            </div>
            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300" id="upcomingCountBadge">
                <?= count($data['upcoming_tasks'] ?? []); ?>
            </span>
        </div>

        <!-- Filter Tabs / Pills -->
        <div class="flex items-center gap-1.5 py-3 border-b border-gray-100 dark:border-slate-700 overflow-x-auto text-xs">
            <button type="button" onclick="filterUpcomingTasks('all')" class="upcoming-tab-btn active px-2.5 py-1 rounded-lg bg-indigo-600 text-white font-semibold transition" data-filter="all">Semua</button>
            <button type="button" onclick="filterUpcomingTasks('today')" class="upcoming-tab-btn px-2.5 py-1 rounded-lg text-gray-600 dark:text-gray-400 font-semibold hover:bg-gray-100 dark:hover:bg-slate-700 transition" data-filter="today">Hari Ini</button>
            <button type="button" onclick="filterUpcomingTasks('week')" class="upcoming-tab-btn px-2.5 py-1 rounded-lg text-gray-600 dark:text-gray-400 font-semibold hover:bg-gray-100 dark:hover:bg-slate-700 transition" data-filter="week">7 Hari</button>
            <button type="button" onclick="filterUpcomingTasks('urgent')" class="upcoming-tab-btn px-2.5 py-1 rounded-lg text-gray-600 dark:text-gray-400 font-semibold hover:bg-gray-100 dark:hover:bg-slate-700 transition" data-filter="urgent">Prioritas</button>
        </div>

        <!-- Task List Items -->
        <div class="mt-3.5 space-y-2.5 overflow-y-auto max-h-[580px] pr-1" id="upcomingTaskList">
            <?php if (empty($data['upcoming_tasks'])) : ?>
                <div class="py-10 px-4 text-center">
                    <div class="w-10 h-10 mx-auto rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 flex items-center justify-center text-sm mb-2">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <h4 class="font-bold text-xs text-gray-800 dark:text-gray-200">Semua Tugas Tuntas!</h4>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5">Tidak ada tugas mendatang yang tertunda saat ini.</p>
                </div>
            <?php else : ?>
                <?php 
                $months = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                foreach ($data['upcoming_tasks'] as $task) : 
                    $dueTime = strtotime($task['due_date']);
                    $dateFmt = date('d', $dueTime) . ' ' . $months[(int)date('m', $dueTime)] . ' ' . date('Y', $dueTime);
                    $daysLeft = $task['days_left'];
                    $filterDays = ($daysLeft === 0) ? 'today' : (($daysLeft >= 0 && $daysLeft <= 7) ? 'week' : 'later');
                ?>
                <div class="upcoming-task-item group p-3 rounded-lg bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700/80 hover:border-indigo-400 dark:hover:border-indigo-500/50 hover:shadow-xs transition cursor-pointer"
                     data-days="<?= $daysLeft ?>"
                     data-filter-days="<?= $filterDays ?>"
                     data-priority="<?= $task['priority'] ?? 'biasa' ?>"
                     onclick="openTaskDetail(<?= htmlspecialchars(json_encode($task), ENT_QUOTES, 'UTF-8') ?>)">
                     
                    <!-- Top Info: Tag, Priority, Deadline Badge -->
                    <div class="flex items-center justify-between gap-2 mb-1.5">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300 uppercase">
                                <?= htmlspecialchars($task['tag'] ?: 'Tugas') ?>
                            </span>
                            <?php if (($task['priority'] ?? '') === 'tinggi') : ?>
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 border border-rose-200/60 dark:border-rose-800/60 uppercase flex items-center gap-1">
                                    <i class="fa-solid fa-fire text-[8px]"></i> Tinggi
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Days Left Badge -->
                        <?php if ($daysLeft < 0) : ?>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200/60 dark:bg-rose-950/40 dark:text-rose-300">
                                Terlambat <?= abs($daysLeft) ?>h
                            </span>
                        <?php elseif ($daysLeft == 0) : ?>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200/60 dark:bg-amber-950/40 dark:text-amber-300">
                                Hari Ini
                            </span>
                        <?php elseif ($daysLeft == 1) : ?>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200/60 dark:bg-blue-950/40 dark:text-blue-300">
                                Besok
                            </span>
                        <?php else : ?>
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-gray-50 text-gray-600 border border-gray-200 dark:bg-slate-800 dark:text-gray-400 dark:border-slate-700">
                                H-<?= $daysLeft ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Title & Project -->
                    <h4 class="font-bold text-xs text-gray-900 dark:text-gray-100 leading-snug group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-2 mb-1">
                        <?= htmlspecialchars($task['title']) ?>
                    </h4>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 font-medium flex items-center gap-1.5 mb-2">
                        <i class="fa-solid fa-folder text-[10px] text-indigo-500"></i>
                        <span class="truncate"><?= htmlspecialchars($task['project_name']) ?></span>
                    </p>

                    <!-- Footer: Due Date, Assignee Avatar, Progress -->
                    <div class="flex items-center justify-between pt-2 border-t border-gray-100 dark:border-slate-700/80 text-[11px]">
                        <div class="flex items-center gap-1 text-gray-400 dark:text-gray-500">
                            <i class="fa-regular fa-calendar text-[10px]"></i>
                            <span><?= $dateFmt ?></span>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <?php if (!empty($task['progress'])) : ?>
                                <span class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400">
                                    <?= $task['progress'] ?>%
                                </span>
                            <?php endif; ?>
                            <div class="w-5 h-5 rounded-full bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 flex items-center justify-center text-[9px] font-bold" title="<?= htmlspecialchars($task['assignee_name'] ?? 'TBA') ?>">
                                <?= strtoupper(substr($task['assignee_name'] ?? 'U', 0, 2)); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Quick Add Button at bottom of upcoming card -->
        <div class="pt-3.5 mt-3 border-t border-gray-100 dark:border-slate-700">
            <button onclick="openAddTaskModal()" class="w-full py-2 rounded-lg border border-gray-200 dark:border-slate-700 hover:border-indigo-400 dark:hover:border-indigo-500 text-indigo-600 dark:text-indigo-400 text-xs font-semibold flex items-center justify-center gap-1.5 hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Tambah Tugas Baru</span>
            </button>
        </div>
    </div>
</div>

<!-- ================= MODAL: DETAIL / QUICK PREVIEW TUGAS ================= -->
<div id="taskDetailModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-40 backdrop-blur-sm modal-overlay opacity-0 transition-opacity" onclick="toggleTaskDetailModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left shadow-2xl transform modal-content sm:my-8 sm:align-middle sm:max-w-lg sm:w-full transition-all border border-gray-100 dark:border-slate-700">
            <div class="p-6 md:p-7">
                <!-- Header -->
                <div class="flex items-start justify-between gap-4 mb-5">
                    <div class="flex items-center gap-2">
                        <span id="detailTaskTag" class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-gray-100 text-gray-700 dark:bg-slate-700 dark:text-gray-300"></span>
                        <span id="detailTaskPriority" class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border"></span>
                    </div>
                    <button type="button" onclick="toggleTaskDetailModal()" class="w-8 h-8 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700 flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <h3 id="detailTaskTitle" class="text-lg md:text-xl font-bold text-gray-900 dark:text-white leading-tight mb-1.5"></h3>
                <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 font-medium mb-5">
                    <i class="fa-solid fa-folder text-indigo-500 text-xs"></i>
                    <span id="detailTaskProject"></span>
                </div>

                <div class="space-y-3.5 bg-gray-50 dark:bg-slate-900/50 p-4 rounded-xl border border-gray-200 dark:border-slate-700/80 mb-6">
                    <!-- Status & Progress -->
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Status & Progres</span>
                        <div class="flex items-center gap-2">
                            <span id="detailTaskStatus" class="px-2.5 py-0.5 rounded-full text-xs font-semibold capitalize border"></span>
                            <span id="detailTaskProgress" class="text-xs font-bold text-indigo-600 dark:text-indigo-400"></span>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="w-full bg-gray-200 dark:bg-slate-700 h-1.5 rounded-full overflow-hidden">
                        <div id="detailTaskProgressBar" class="bg-indigo-600 h-full rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>

                    <!-- Due Date -->
                    <div class="flex items-center justify-between pt-2 border-t border-gray-200/60 dark:border-slate-800">
                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Tenggat Waktu</span>
                        <div class="flex items-center gap-2">
                            <i class="fa-regular fa-calendar text-gray-400 text-xs"></i>
                            <span id="detailTaskDueDate" class="text-xs font-semibold text-gray-800 dark:text-gray-200"></span>
                            <span id="detailTaskDaysLeft" class="text-[10px] font-bold px-2 py-0.5 rounded"></span>
                        </div>
                    </div>

                    <!-- Assignee -->
                    <div class="flex items-center justify-between pt-2 border-t border-gray-200/60 dark:border-slate-800">
                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Penanggung Jawab</span>
                        <div class="flex items-center gap-2" id="detailTaskAssignee">
                            <!-- Populated via JS -->
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-2.5">
                    <button type="button" onclick="toggleTaskDetailModal()" class="px-4 py-2 rounded-xl border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-300 font-semibold text-xs hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                        Tutup
                    </button>
                    <button type="button" id="detailBtnDelete" class="px-4 py-2 rounded-xl text-rose-600 hover:text-rose-700 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 font-semibold text-xs transition flex items-center gap-1.5">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                        <span>Hapus</span>
                    </button>
                    <button type="button" id="detailBtnEdit" class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-semibold text-xs hover:bg-indigo-700 transition flex items-center gap-1.5 shadow-sm shadow-indigo-200 dark:shadow-none">
                        <i class="fa-solid fa-pen text-xs"></i>
                        <span>Ubah Tugas</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= MODAL: ALL TASKS IN A DAY ================= -->
<div id="dayTasksModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-40 backdrop-blur-sm modal-overlay opacity-0 transition-opacity" onclick="toggleDayTasksModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left shadow-2xl transform modal-content sm:my-8 sm:align-middle sm:max-w-lg sm:w-full transition-all border border-gray-100 dark:border-slate-700">
            <div class="p-6 md:p-7">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 id="dayTasksModalTitle" class="text-lg font-bold text-gray-900 dark:text-white"></h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Daftar seluruh tugas pada tanggal ini</p>
                    </div>
                    <button type="button" onclick="toggleDayTasksModal()" class="w-8 h-8 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700 flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div id="dayTasksModalList" class="space-y-2 max-h-96 overflow-y-auto pr-1 mb-5">
                    <!-- Populated via JS -->
                </div>

                <div class="flex justify-end items-center gap-2.5 pt-3 border-t border-gray-100 dark:border-slate-700">
                    <button type="button" onclick="toggleDayTasksModal()" class="px-4 py-2 rounded-xl border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-300 font-semibold text-xs hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                        Tutup
                    </button>
                    <button type="button" id="dayTasksAddBtn" class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-semibold text-xs hover:bg-indigo-700 transition flex items-center gap-1.5 shadow-sm shadow-indigo-200 dark:shadow-none">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>Tambah Tugas</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= MODAL: TAMBAH / UBAH TUGAS ================= -->
<div id="taskModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm modal-overlay opacity-0 transition-opacity" aria-hidden="true" onclick="toggleTaskModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left shadow-2xl transform modal-content sm:my-8 sm:align-middle sm:max-w-xl sm:w-full transition-colors border border-gray-100 dark:border-slate-700">
            <form action="<?= BASEURL; ?>/task/add" method="post" id="taskForm">
                <input type="hidden" name="id" id="task_id">
                <input type="hidden" name="return_to" id="return_to" value="<?= htmlspecialchars($currentCalUrl) ?>">

                <div class="p-6 md:p-7">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-slate-700/80 mb-5">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-sm flex-shrink-0">
                                <i class="fa-solid fa-list-check"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 tracking-tight" id="taskModalTitle">Buat Tugas Baru</h3>
                                <p class="text-gray-400 dark:text-gray-500 text-xs">Atur rincian tugas untuk kalender dan tim Anda.</p>
                            </div>
                        </div>
                        <button type="button" onclick="toggleTaskModal()" class="w-8 h-8 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700 flex items-center justify-center transition">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <!-- Judul Tugas -->
                        <div>
                            <label for="title" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 ml-0.5">Judul Tugas</label>
                            <input type="text" name="title" id="title" required class="w-full bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs font-semibold text-gray-800 dark:text-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition h-[38px] placeholder-gray-400 dark:placeholder-gray-500" placeholder="Contoh: Pembuatan Modul Kalender">
                        </div>

                        <!-- Project & Assignee -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="project_id" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 ml-0.5">Pilih Project</label>
                                <div class="select-container" id="projectSelectContainer">
                                    <div class="select-trigger" data-placeholder="Pilih Project...">
                                        <span class="select-trigger-text text-gray-400 dark:text-gray-500">Pilih Project...</span>
                                        <i class="fa-solid fa-chevron-down select-trigger-icon"></i>
                                    </div>
                                    <div class="select-options">
                                        <!-- Search Box Header -->
                                        <div class="p-2 border-b border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 sticky top-0 z-20">
                                            <div class="relative">
                                                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                                                <input type="text" class="select-search-input w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg pl-8 pr-3 py-1.5 text-xs font-semibold text-gray-800 dark:text-gray-200 outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Cari project...">
                                            </div>
                                        </div>
                                        <div class="select-options-list">
                                            <?php foreach($data['projects'] as $p) : ?>
                                                <div class="select-option flex items-center justify-between gap-3" 
                                                     data-value="<?= $p['id']; ?>" 
                                                     data-name="<?= htmlspecialchars($p['name'], ENT_QUOTES); ?>"
                                                     data-search="<?= htmlspecialchars(strtolower($p['name'] . ' ' . ($p['leader_name'] ?? '')), ENT_QUOTES); ?>">
                                                    <div class="flex items-center gap-2.5 min-w-0">
                                                        <div class="w-6 h-6 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0 text-xs">
                                                            <i class="fa-solid fa-folder"></i>
                                                        </div>
                                                        <div class="flex flex-col min-w-0">
                                                            <span class="font-bold text-xs truncate leading-tight text-gray-800 dark:text-gray-200"><?= htmlspecialchars($p['name']); ?></span>
                                                            <span class="text-[10px] text-gray-400 dark:text-gray-500 truncate">
                                                                Lead: <?= htmlspecialchars($p['leader_name'] ?? '-'); ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <i class="fa-solid fa-check text-xs text-indigo-600 opacity-0 check-icon flex-shrink-0"></i>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="select-no-results py-4 text-center text-xs text-gray-400 font-medium" style="display: none;">Tidak ada project yang cocok</div>
                                    </div>
                                </div>
                                <input type="hidden" name="project_id" id="project_id_field" required>
                            </div>

                            <?php if($_SESSION['user']['role'] === 'admin') : ?>
                            <div>
                                <label for="assignee_id" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 ml-0.5">Penanggung Jawab</label>
                                <div class="select-container" id="assigneeSelectContainer">
                                    <div class="select-trigger" data-placeholder="Pilih Anggota...">
                                        <span class="select-trigger-text text-gray-400 dark:text-gray-500">Pilih Anggota...</span>
                                        <i class="fa-solid fa-chevron-down select-trigger-icon"></i>
                                    </div>
                                    <div class="select-options">
                                        <?php foreach($data['team'] as $m) : ?>
                                            <div class="select-option flex items-center gap-2" data-value="<?= $m['id']; ?>">
                                                <div class="w-5 h-5 rounded-full bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 flex items-center justify-center text-[9px] font-bold flex-shrink-0">
                                                    <?= strtoupper(substr($m['name'], 0, 2)); ?>
                                                </div>
                                                <span class="truncate"><?= htmlspecialchars($m['name']); ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <input type="hidden" name="assignee_id" id="assignee_id_field">
                            </div>
                            <?php else : ?>
                            <div class="flex flex-col justify-end">
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 ml-0.5">Penanggung Jawab</label>
                                <div class="bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs font-semibold text-gray-600 dark:text-gray-300 flex items-center gap-2 h-[38px]">
                                    <div class="w-5 h-5 rounded-full bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 flex items-center justify-center text-[9px] font-bold">
                                        <?= strtoupper(substr($_SESSION['user']['name'], 0, 2)); ?>
                                    </div>
                                    <span><?= htmlspecialchars($_SESSION['user']['name']); ?> (Anda)</span>
                                </div>
                                <input type="hidden" name="assignee_id" id="assignee_id_field" value="<?= $_SESSION['user']['id']; ?>">
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Tag & Priority -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="tag" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 ml-0.5">Label / Tag</label>
                                <input type="text" name="tag" id="tag" required class="w-full bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs font-semibold text-gray-800 dark:text-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition h-[38px] placeholder-gray-400 dark:placeholder-gray-500" placeholder="Contoh: UI/UX">
                            </div>
                            <div>
                                <label for="priority_field" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 ml-0.5">Prioritas</label>
                                <div class="select-container" id="prioritySelectContainer">
                                    <div class="select-trigger" data-placeholder="Pilih Prioritas...">
                                        <span class="select-trigger-text text-gray-400 dark:text-gray-500">Pilih Prioritas...</span>
                                        <i class="fa-solid fa-chevron-down select-trigger-icon"></i>
                                    </div>
                                    <div class="select-options">
                                        <div class="select-option flex items-center gap-2" data-value="tinggi">
                                            <span class="w-2 h-2 rounded-full bg-rose-500 flex-shrink-0"></span>
                                            <span>Tinggi</span>
                                        </div>
                                        <div class="select-option flex items-center gap-2" data-value="sedang">
                                            <span class="w-2 h-2 rounded-full bg-amber-500 flex-shrink-0"></span>
                                            <span>Sedang</span>
                                        </div>
                                        <div class="select-option flex items-center gap-2" data-value="biasa">
                                            <span class="w-2 h-2 rounded-full bg-blue-500 flex-shrink-0"></span>
                                            <span>Biasa</span>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="priority" id="priority_field" value="biasa">
                            </div>
                        </div>

                        <!-- Due Date -->
                        <div>
                            <label for="due_date" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 ml-0.5">Tenggat Waktu</label>
                            <input type="date" name="due_date" id="due_date" required class="w-full bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs font-semibold text-gray-800 dark:text-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition cursor-pointer h-[38px]" onclick="this.showPicker()">
                        </div>

                        <!-- Status & Progress -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="status_field" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 ml-0.5">Status Tugas</label>
                                <div class="select-container" id="statusSelectContainer">
                                    <div class="select-trigger" data-placeholder="Pilih Status...">
                                        <span class="select-trigger-text text-gray-400 dark:text-gray-500">Pilih Status...</span>
                                        <i class="fa-solid fa-chevron-down select-trigger-icon"></i>
                                    </div>
                                    <div class="select-options">
                                        <div class="select-option flex items-center gap-2" data-value="todo">
                                            <span class="w-2 h-2 rounded-full bg-amber-500 flex-shrink-0"></span>
                                            <span>To Do</span>
                                        </div>
                                        <div class="select-option flex items-center gap-2" data-value="in_progress">
                                            <span class="w-2 h-2 rounded-full bg-indigo-500 flex-shrink-0"></span>
                                            <span>In Progress</span>
                                        </div>
                                        <div class="select-option flex items-center gap-2" data-value="done">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 flex-shrink-0"></span>
                                            <span>Done</span>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="status" id="status_field" value="todo">
                            </div>
                            <div id="progress-container">
                                <label for="progress" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 ml-0.5 flex justify-between items-center">
                                    <span>Progress</span>
                                    <span id="progress-val-text" class="text-xs font-bold text-indigo-600 dark:text-indigo-400">0%</span>
                                </label>
                                <div class="h-[38px] flex items-center px-1">
                                    <input type="range" name="progress" id="progress" min="0" max="100" class="w-full h-1.5 bg-gray-100 dark:bg-slate-700 rounded-lg appearance-none cursor-pointer accent-indigo-600 transition hover:bg-gray-200 dark:hover:bg-slate-600" value="0">
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="tag_color" id="tag_color" value="bg-blue-50 text-blue-600">
                    </div>

                    <!-- Modal Actions Footer -->
                    <div class="flex items-center justify-end gap-2.5 mt-6 pt-4 border-t border-gray-100 dark:border-slate-700/80">
                        <button type="button" onclick="toggleTaskModal()" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-300 font-semibold text-xs hover:bg-gray-50 dark:hover:bg-slate-700 transition">Batal</button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-semibold text-xs hover:bg-indigo-700 transition shadow-sm shadow-indigo-200 dark:shadow-none flex items-center gap-1.5">
                            <i class="fa-solid fa-check text-xs"></i>
                            <span>Simpan Tugas</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // URL Helper for Calendar Filter
    function applyCalFilter(key, value) {
        const url = new URL(window.location.href);
        if (value && value !== 'all') {
            url.searchParams.set(key, value);
        } else {
            url.searchParams.delete(key);
        }
        window.location.href = url.toString();
    }

    // Modals
    const taskModal = document.getElementById('taskModal');
    const taskForm = document.getElementById('taskForm');
    const taskModalTitle = document.getElementById('taskModalTitle');
    const taskDetailModal = document.getElementById('taskDetailModal');
    const dayTasksModal = document.getElementById('dayTasksModal');

    function toggleTaskModal() {
        if (taskModal.classList.contains('hidden')) {
            taskModal.classList.remove('hidden');
            setTimeout(() => {
                taskModal.classList.add('modal-active');
                document.body.classList.add('overflow-hidden');
            }, 10);
        } else {
            taskModal.classList.remove('modal-active');
            setTimeout(() => {
                taskModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                taskForm.reset();
                document.getElementById('task_id').value = '';
            }, 300);
        }
    }

    function toggleTaskDetailModal() {
        if (taskDetailModal.classList.contains('hidden')) {
            taskDetailModal.classList.remove('hidden');
            setTimeout(() => {
                taskDetailModal.classList.add('modal-active');
                document.body.classList.add('overflow-hidden');
            }, 10);
        } else {
            taskDetailModal.classList.remove('modal-active');
            setTimeout(() => {
                taskDetailModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }, 300);
        }
    }

    function toggleDayTasksModal() {
        if (dayTasksModal.classList.contains('hidden')) {
            dayTasksModal.classList.remove('hidden');
            setTimeout(() => {
                dayTasksModal.classList.add('modal-active');
                document.body.classList.add('overflow-hidden');
            }, 10);
        } else {
            dayTasksModal.classList.remove('modal-active');
            setTimeout(() => {
                dayTasksModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }, 300);
        }
    }

    // Open Add Task Modal (optional prefilled due_date)
    function openAddTaskModal(presetDate = null) {
        taskModalTitle.innerText = 'Buat Tugas Baru';
        taskForm.setAttribute('action', '<?= BASEURL; ?>/task/add');
        taskForm.reset();
        document.getElementById('task_id').value = '';

        const activeProj = '<?= $activeProjectId ?>';
        document.getElementById('project_id_field').value = activeProj || '';
        
        if (document.getElementById('assigneeSelectContainer')) {
            document.getElementById('assignee_id_field').value = '';
            initCustomSelect('assigneeSelectContainer', 'assignee_id_field');
        }

        const dateInput = document.getElementById('due_date');
        if (presetDate) {
            dateInput.value = presetDate;
        } else {
            const today = new Date();
            const y = today.getFullYear();
            const m = String(today.getMonth() + 1).padStart(2, '0');
            const d = String(today.getDate()).padStart(2, '0');
            dateInput.value = `${y}-${m}-${d}`;
        }

        const statusField = document.getElementById('status_field');
        statusField.value = 'todo';

        const progressSlider = document.getElementById('progress');
        const progressText = document.getElementById('progress-val-text');
        progressSlider.value = 0;
        progressText.innerText = '0%';

        document.getElementById('priority_field').value = 'biasa';

        initCustomSelect('projectSelectContainer', 'project_id_field');
        initCustomSelect('statusSelectContainer', 'status_field');
        initCustomSelect('prioritySelectContainer', 'priority_field');

        updateProgressVisibility();
        toggleTaskModal();
    }

    // Open Edit Task Modal
    function openEditTaskModal(id) {
        taskModalTitle.innerText = 'Ubah Detail Tugas';
        taskForm.setAttribute('action', '<?= BASEURL; ?>/task/edit');

        fetch('<?= BASEURL; ?>/task/getEdit/' + id)
            .then(res => res.json())
            .then(data => {
                document.getElementById('task_id').value = data.id;
                document.getElementById('title').value = data.title;
                document.getElementById('project_id_field').value = data.project_id;
                if (document.getElementById('assignee_id_field')) {
                    document.getElementById('assignee_id_field').value = data.assignee_id;
                }
                document.getElementById('tag').value = data.tag;
                document.getElementById('due_date').value = data.due_date;
                document.getElementById('status_field').value = data.status;
                document.getElementById('priority_field').value = data.priority;

                const slider = document.getElementById('progress');
                slider.value = data.progress;
                document.getElementById('progress-val-text').innerText = data.progress + '%';

                document.getElementById('tag_color').value = data.tag_color || 'bg-blue-50 text-blue-600';

                initCustomSelect('projectSelectContainer', 'project_id_field');
                if (document.getElementById('assigneeSelectContainer')) {
                    initCustomSelect('assigneeSelectContainer', 'assignee_id_field');
                }
                initCustomSelect('statusSelectContainer', 'status_field');
                initCustomSelect('prioritySelectContainer', 'priority_field');

                updateProgressVisibility();
                toggleTaskModal();
            });
    }

    // Open Task Detail Preview Modal
    let currentDetailTask = null;
    function openTaskDetail(task) {
        currentDetailTask = task;

        document.getElementById('detailTaskTitle').innerText = task.title;
        document.getElementById('detailTaskProject').innerText = task.project_name || 'Tanpa Project';
        document.getElementById('detailTaskTag').innerText = task.tag || 'Tugas';
        
        // Priority
        const prioEl = document.getElementById('detailTaskPriority');
        prioEl.innerText = (task.priority || 'biasa').toUpperCase();
        if (task.priority === 'tinggi') {
            prioEl.className = 'px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-rose-50 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 border border-rose-200/60 dark:border-rose-800/60';
        } else if (task.priority === 'sedang') {
            prioEl.className = 'px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-200/60 dark:border-amber-800/60';
        } else {
            prioEl.className = 'px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/60';
        }

        // Status
        const statusEl = document.getElementById('detailTaskStatus');
        if (task.status === 'done') {
            statusEl.innerText = 'Selesai';
            statusEl.className = 'px-2.5 py-0.5 rounded-full text-xs font-semibold capitalize bg-emerald-50 text-emerald-700 border-emerald-200/60 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-850';
        } else if (task.status === 'in_progress') {
            statusEl.innerText = 'In Progress';
            statusEl.className = 'px-2.5 py-0.5 rounded-full text-xs font-semibold capitalize bg-indigo-50 text-indigo-700 border-indigo-200/60 dark:bg-indigo-950/50 dark:text-indigo-300 dark:border-indigo-850';
        } else {
            statusEl.innerText = 'To Do';
            statusEl.className = 'px-2.5 py-0.5 rounded-full text-xs font-semibold capitalize bg-amber-50 text-amber-700 border-amber-200/60 dark:bg-amber-950/50 dark:text-amber-300 dark:border-amber-850';
        }

        // Progress
        const prog = parseInt(task.progress || 0);
        document.getElementById('detailTaskProgress').innerText = prog + '%';
        document.getElementById('detailTaskProgressBar').style.width = prog + '%';

        // Due date & days remaining
        const dueDate = new Date(task.due_date);
        const today = new Date();
        today.setHours(0,0,0,0);
        dueDate.setHours(0,0,0,0);

        const diffTime = dueDate.getTime() - today.getTime();
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        const monthNames = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const formattedDate = dueDate.getDate() + ' ' + monthNames[dueDate.getMonth() + 1] + ' ' + dueDate.getFullYear();
        document.getElementById('detailTaskDueDate').innerText = formattedDate;

        const daysLeftEl = document.getElementById('detailTaskDaysLeft');
        if (diffDays < 0) {
            daysLeftEl.innerText = `Terlambat ${Math.abs(diffDays)} Hari`;
            daysLeftEl.className = 'text-[10px] font-bold px-2 py-0.5 rounded bg-rose-50 text-rose-700 border border-rose-200/60 dark:bg-rose-950/40 dark:text-rose-300';
        } else if (diffDays === 0) {
            daysLeftEl.innerText = 'Hari Ini';
            daysLeftEl.className = 'text-[10px] font-bold px-2 py-0.5 rounded bg-amber-50 text-amber-700 border border-amber-200/60 dark:bg-amber-950/40 dark:text-amber-300';
        } else if (diffDays === 1) {
            daysLeftEl.innerText = 'Besok (H-1)';
            daysLeftEl.className = 'text-[10px] font-bold px-2 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-200/60 dark:bg-blue-950/40 dark:text-blue-300';
        } else {
            daysLeftEl.innerText = `H-${diffDays}`;
            daysLeftEl.className = 'text-[10px] font-medium px-2 py-0.5 rounded bg-gray-100 text-gray-700 dark:bg-slate-700 dark:text-gray-300';
        }

        // Assignee
        const assigneeName = task.assignee_name || 'Belum Ditugaskan';
        const assigneeContainer = document.getElementById('detailTaskAssignee');
        assigneeContainer.innerHTML = `
            <div class="w-5 h-5 rounded-full bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 flex items-center justify-center text-[9px] font-bold">
                ${assigneeName.substring(0, 2).toUpperCase()}
            </div>
            <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">${assigneeName}</span>
        `;

        // Buttons
        document.getElementById('detailBtnEdit').onclick = () => {
            toggleTaskDetailModal();
            setTimeout(() => openEditTaskModal(task.id), 300);
        };

        const deleteUrl = `<?= BASEURL ?>/task/delete/${task.id}?return_to=${encodeURIComponent('<?= $currentCalUrl ?>')}`;
        document.getElementById('detailBtnDelete').onclick = () => {
            toggleTaskDetailModal();
            setTimeout(() => showDeleteModal(deleteUrl, task.title), 300);
        };

        toggleTaskDetailModal();
    }

    // Open Day Tasks Modal (for dates with > 3 tasks)
    function openDayTasksModal(dateTitle, dateString, tasks) {
        document.getElementById('dayTasksModalTitle').innerText = dateTitle;
        const listContainer = document.getElementById('dayTasksModalList');
        listContainer.innerHTML = '';

        tasks.forEach(t => {
            const isDone = t.status === 'done';
            const isInProg = t.status === 'in_progress';
            const borderCls = isDone ? 'border-emerald-500' : (isInProg ? 'border-indigo-500' : 'border-amber-500');
            const bgCls = isDone ? 'bg-emerald-50/40 dark:bg-emerald-950/20' : (isInProg ? 'bg-indigo-50/40 dark:bg-indigo-950/20' : 'bg-amber-50/40 dark:bg-amber-950/20');
            
            const item = document.createElement('div');
            item.className = `p-2.5 rounded-lg border-l-2 ${borderCls} ${bgCls} border border-gray-200 dark:border-slate-700/80 flex items-center justify-between gap-3 hover:shadow-xs transition cursor-pointer`;
            item.innerHTML = `
                <div class="min-w-0 flex-grow">
                    <div class="flex items-center gap-2 mb-0.5">
                        <span class="text-[9px] font-semibold uppercase tracking-wider px-1.5 py-0.5 rounded bg-white dark:bg-slate-800 text-gray-600 dark:text-gray-300">${t.tag || 'Tugas'}</span>
                        <span class="text-[10px] text-gray-400 truncate">${t.project_name || ''}</span>
                    </div>
                    <h5 class="text-xs font-bold text-gray-900 dark:text-white truncate">${t.title}</h5>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400">${t.progress || 0}%</span>
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                </div>
            `;
            item.onclick = () => {
                toggleDayTasksModal();
                setTimeout(() => openTaskDetail(t), 300);
            };
            listContainer.appendChild(item);
        });

        document.getElementById('dayTasksAddBtn').onclick = () => {
            toggleDayTasksModal();
            setTimeout(() => openAddTaskModal(dateString), 300);
        };

        toggleDayTasksModal();
    }

    // Progress visibility update
    function updateProgressVisibility() {
        const slider = document.getElementById('progress');
        const text = document.getElementById('progress-val-text');
        const statusField = document.getElementById('status_field');
        const container = document.getElementById('progress-container');
        if (!slider || !text) return;

        const val = parseInt(slider.value);
        text.innerText = val + '%';
        text.className = 'text-xs font-bold text-indigo-600 dark:text-indigo-400';

        if (statusField.value === 'done') {
            container.classList.add('hidden');
        } else {
            container.classList.remove('hidden');
        }
    }

    // Initialize on DOM load
    document.addEventListener('DOMContentLoaded', function() {
        // Teleport action content to topbar header area
        const actionArea = document.getElementById('header-action-area');
        const actionContent = document.getElementById('task-action-content');
        if (actionArea && actionContent) {
            actionArea.innerHTML = '';
            while (actionContent.firstChild) {
                actionArea.appendChild(actionContent.firstChild);
            }
        }

        // Custom Select in Modal
        if (typeof initCustomSelect === 'function') {
            initCustomSelect('projectSelectContainer', 'project_id_field');
            if (document.getElementById('assigneeSelectContainer')) {
                initCustomSelect('assigneeSelectContainer', 'assignee_id_field');
            }
            initCustomSelect('statusSelectContainer', 'status_field');
            initCustomSelect('prioritySelectContainer', 'priority_field');
        }

        // Progress Slider events
        const slider = document.getElementById('progress');
        const statusField = document.getElementById('status_field');
        if (slider && statusField) {
            slider.addEventListener('input', function() {
                const val = parseInt(this.value);
                if (val === 100) statusField.value = 'done';
                else if (val > 0) statusField.value = 'in_progress';
                else statusField.value = 'todo';

                if (typeof initCustomSelect === 'function') {
                    initCustomSelect('statusSelectContainer', 'status_field');
                }
                updateProgressVisibility();
            });
        }

        // Close on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (!taskDetailModal.classList.contains('hidden')) toggleTaskDetailModal();
                if (!dayTasksModal.classList.contains('hidden')) toggleDayTasksModal();
                if (!taskModal.classList.contains('hidden')) toggleTaskModal();
            }
        });
    });

    // Filter Upcoming Tasks in Sidebar Card
    function filterUpcomingTasks(filter) {
        document.querySelectorAll('.upcoming-tab-btn').forEach(btn => {
            if (btn.getAttribute('data-filter') === filter) {
                btn.className = 'upcoming-tab-btn active px-2.5 py-1 rounded-lg bg-indigo-600 text-white font-semibold transition';
            } else {
                btn.className = 'upcoming-tab-btn px-2.5 py-1 rounded-lg text-gray-600 dark:text-gray-400 font-semibold hover:bg-gray-100 dark:hover:bg-slate-700 transition';
            }
        });

        const items = document.querySelectorAll('.upcoming-task-item');
        let visibleCount = 0;
        items.forEach(item => {
            const days = parseInt(item.getAttribute('data-days'));
            const priority = item.getAttribute('data-priority');
            let show = false;

            if (filter === 'all') {
                show = true;
            } else if (filter === 'today') {
                show = (days === 0);
            } else if (filter === 'week') {
                show = (days >= 0 && days <= 7);
            } else if (filter === 'urgent') {
                show = (priority === 'tinggi' || days <= 1);
            }

            item.style.display = show ? 'block' : 'none';
            if (show) visibleCount++;
        });

        const badge = document.getElementById('upcomingCountBadge');
        if (badge) badge.innerText = visibleCount;
    }
</script>
