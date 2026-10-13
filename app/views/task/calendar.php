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
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-3 py-1">
        <div class="flex flex-wrap items-center gap-2.5">
            <!-- View Switcher (Papan / Kalender) -->
            <div class="flex items-center bg-gray-100 dark:bg-slate-800 p-1 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm">
                <a href="<?= BASEURL ?>/task<?= !empty($activeProjectId) ? '?project_id='.$activeProjectId : '' ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition" title="Tampilan Papan Kanban">
                    <i class="fa-solid fa-list-check text-xs"></i>
                    <span>Papan</span>
                </a>
                <a href="<?= $currentCalUrl ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-black bg-white dark:bg-slate-700 text-[#313491] dark:text-indigo-400 shadow-sm" title="Tampilan Kalender">
                    <i class="fa-solid fa-calendar-days text-xs"></i>
                    <span>Kalender</span>
                </a>
            </div>

            <!-- Project Filter Dropdown -->
            <div class="relative custom-dropdown" id="calProjectDropdown">
                <button onclick="toggleDropdown('calProjectDropdown')" class="flex items-center gap-2 bg-white dark:bg-slate-800 px-4 py-2.5 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm hover:bg-gray-50 dark:hover:bg-slate-700 transition border-s-4 border-s-indigo-500">
                    <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-tight">Project:</span>
                    <span class="text-sm font-black text-gray-900 dark:text-gray-100">
                        <?php 
                            $selectedProjName = 'Semua Project';
                            if (!empty($activeProjectId)) {
                                foreach($data['projects'] as $p) {
                                    if($p['id'] == $activeProjectId) $selectedProjName = $p['name'];
                                }
                            }
                            echo $selectedProjName;
                        ?>
                    </span>
                    <i class="fa-solid fa-chevron-down text-xs text-indigo-500 ml-1"></i>
                </button>
                <div class="dropdown-menu absolute top-full left-0 mt-2 w-64 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl shadow-2xl py-2 hidden z-[100] max-h-60 overflow-y-auto">
                    <button onclick="applyCalFilter('project_id', '')" class="w-full text-left px-4 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-slate-700 transition-colors <?= empty($activeProjectId) ? 'font-black text-indigo-700 dark:text-indigo-400 bg-indigo-50/50 dark:bg-slate-700/50' : 'font-semibold' ?>">Semua Project</button>
                    <?php foreach($data['projects'] as $p) : ?>
                        <button onclick="applyCalFilter('project_id', '<?= $p['id'] ?>')" class="w-full text-left px-4 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-slate-700 transition-colors <?= ($activeProjectId == $p['id']) ? 'font-black text-indigo-700 dark:text-indigo-400 bg-indigo-50/50 dark:bg-slate-700/50' : 'font-semibold' ?>"><?= htmlspecialchars($p['name']) ?></button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Status Filter Dropdown -->
            <div class="relative custom-dropdown" id="calStatusDropdown">
                <button onclick="toggleDropdown('calStatusDropdown')" class="flex items-center gap-2 bg-white dark:bg-slate-800 px-4 py-2.5 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                    <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-tight">Status:</span>
                    <span class="text-sm font-black text-gray-900 dark:text-gray-100 capitalize">
                        <?php 
                            if ($statusFilter === 'todo') echo 'To Do';
                            elseif ($statusFilter === 'in_progress') echo 'In Progress';
                            elseif ($statusFilter === 'done') echo 'Selesai';
                            else echo 'Semua';
                        ?>
                    </span>
                    <i class="fa-solid fa-chevron-down text-xs text-gray-400 ml-1"></i>
                </button>
                <div class="dropdown-menu absolute top-full left-0 mt-2 w-48 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl shadow-2xl py-2 hidden z-[100]">
                    <button onclick="applyCalFilter('status', 'all')" class="w-full text-left px-4 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-slate-700 transition-colors <?= ($statusFilter === 'all') ? 'font-black text-indigo-700 dark:text-indigo-400 bg-indigo-50/50 dark:bg-slate-700/50' : 'font-semibold' ?>">Semua Status</button>
                    <button onclick="applyCalFilter('status', 'todo')" class="w-full text-left px-4 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-slate-700 transition-colors <?= ($statusFilter === 'todo') ? 'font-black text-indigo-700 dark:text-indigo-400 bg-indigo-50/50 dark:bg-slate-700/50' : 'font-semibold' ?>">To Do</button>
                    <button onclick="applyCalFilter('status', 'in_progress')" class="w-full text-left px-4 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-slate-700 transition-colors <?= ($statusFilter === 'in_progress') ? 'font-black text-indigo-700 dark:text-indigo-400 bg-indigo-50/50 dark:bg-slate-700/50' : 'font-semibold' ?>">In Progress</button>
                    <button onclick="applyCalFilter('status', 'done')" class="w-full text-left px-4 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-slate-700 transition-colors <?= ($statusFilter === 'done') ? 'font-black text-indigo-700 dark:text-indigo-400 bg-indigo-50/50 dark:bg-slate-700/50' : 'font-semibold' ?>">Selesai</button>
                </div>
            </div>

            <!-- Search Input -->
            <div class="relative">
                <input type="text" id="calSearch" placeholder="Cari tugas..." value="<?= htmlspecialchars($searchQuery) ?>" 
                    class="bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-700 dark:text-gray-200 shadow-sm focus:ring-2 focus:ring-indigo-500 outline-none pr-10 w-44 sm:w-56"
                    onkeypress="if(event.key === 'Enter') applyCalFilter('search', this.value)">
                <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-gray-400 text-xs"></i>
                </div>
            </div>
            
            <?php if(!empty($activeProjectId) || (!empty($statusFilter) && $statusFilter !== 'all') || !empty($searchQuery)) : ?>
                <a href="<?= buildCalUrl($currMonth, $currYear) ?>" class="flex items-center gap-1.5 bg-red-50 dark:bg-red-950/40 px-3.5 py-2.5 rounded-xl border border-red-100 dark:border-red-900/50 shadow-sm hover:bg-red-100 dark:hover:bg-red-900/60 transition text-xs font-black text-red-600 dark:text-red-400 uppercase tracking-widest" title="Reset Filter">
                    <i class="fa-solid fa-xmark text-xs"></i>
                    <span>Reset</span>
                </a>
            <?php endif; ?>
        </div>

        <button onclick="openAddTaskModal()" class="w-full lg:w-auto bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 dark:shadow-none">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Tambah Task</span>
        </button>
    </div>
</div>

<!-- MONTHLY STATS SUMMARY CARDS -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl border border-gray-100 dark:border-slate-700/80 shadow-sm flex items-center gap-3.5 transition-colors">
        <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 flex-shrink-0">
            <i class="fa-solid fa-calendar-days text-2xl"></i>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Total Tugas</p>
            <h4 class="text-xl font-black text-gray-900 dark:text-white mt-0.5"><?= $data['month_stats']['total']; ?></h4>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl border border-gray-100 dark:border-slate-700/80 shadow-sm flex items-center gap-3.5 transition-colors">
        <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 dark:text-amber-400 flex-shrink-0">
            <i class="fa-solid fa-clock text-2xl"></i>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">To Do</p>
            <h4 class="text-xl font-black text-amber-600 dark:text-amber-400 mt-0.5"><?= $data['month_stats']['todo']; ?></h4>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl border border-gray-100 dark:border-slate-700/80 shadow-sm flex items-center gap-3.5 transition-colors">
        <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 flex-shrink-0">
            <i class="fa-solid fa-bolt text-2xl"></i>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">In Progress</p>
            <h4 class="text-xl font-black text-blue-600 dark:text-blue-400 mt-0.5"><?= $data['month_stats']['in_progress']; ?></h4>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl border border-gray-100 dark:border-slate-700/80 shadow-sm flex items-center gap-3.5 transition-colors">
        <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 flex-shrink-0">
            <i class="fa-solid fa-circle-check text-2xl"></i>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Selesai</p>
            <h4 class="text-xl font-black text-emerald-600 dark:text-emerald-400 mt-0.5"><?= $data['month_stats']['done']; ?></h4>
        </div>
    </div>
</div>

<!-- CALENDAR & UPCOMING TASKS CONTAINER -->
<div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
    <!-- MAIN CALENDAR CONTAINER -->
    <div class="xl:col-span-8 2xl:col-span-9 bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden transition-colors">
    <!-- CALENDAR TOP BAR / CONTROLS -->
    <div class="p-5 md:p-6 border-b border-gray-100 dark:border-slate-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-gradient-to-r from-white via-indigo-50/30 to-white dark:from-slate-800 dark:via-slate-800/80 dark:to-slate-800">
        <!-- Month Navigation -->
        <div class="flex items-center gap-3">
            <a href="<?= buildCalUrl($prevMonth, $prevYear, $activeProjectId, $statusFilter, $searchQuery) ?>" 
               class="p-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition hover:scale-105 active:scale-95" title="Bulan Sebelumnya">
                <i class="fa-solid fa-chevron-left text-sm"></i>
            </a>
            
            <div class="flex items-baseline gap-2">
                <h2 class="text-xl md:text-2xl font-black text-gray-900 dark:text-white tracking-tight">
                    <?= $indoMonths[$currMonth]; ?> <span class="text-indigo-600 dark:text-indigo-400"><?= $currYear; ?></span>
                </h2>
            </div>

            <a href="<?= buildCalUrl($nextMonth, $nextYear, $activeProjectId, $statusFilter, $searchQuery) ?>" 
               class="p-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition hover:scale-105 active:scale-95" title="Bulan Selanjutnya">
                <i class="fa-solid fa-chevron-right text-sm"></i>
            </a>

            <!-- Jump to Today -->
            <a href="<?= buildCalUrl((int)date('n'), (int)date('Y'), $activeProjectId, $statusFilter, $searchQuery) ?>" 
               class="ml-2 px-3 py-1.5 text-xs font-black uppercase tracking-wider rounded-xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 border border-indigo-100 dark:border-indigo-800 transition">
                Hari Ini
            </a>
        </div>

        <!-- Jump Selectors & Legends -->
        <div class="flex flex-wrap items-center gap-3">
            <!-- Quick Month Jump -->
            <select onchange="applyCalFilter('month', this.value)" class="bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs font-bold text-gray-700 dark:text-gray-300 outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                <?php foreach($indoMonths as $mNum => $mName) : ?>
                    <option value="<?= $mNum; ?>" <?= ($mNum == $currMonth) ? 'selected' : ''; ?>><?= $mName; ?></option>
                <?php endforeach; ?>
            </select>

            <!-- Quick Year Jump -->
            <select onchange="applyCalFilter('year', this.value)" class="bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs font-bold text-gray-700 dark:text-gray-300 outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                <?php for($y = (int)date('Y') - 2; $y <= (int)date('Y') + 3; $y++) : ?>
                    <option value="<?= $y; ?>" <?= ($y == $currYear) ? 'selected' : ''; ?>><?= $y; ?></option>
                <?php endfor; ?>
            </select>

            <!-- Status Legends -->
            <div class="hidden sm:flex items-center gap-3 pl-2 border-l border-gray-200 dark:border-slate-700">
                <span class="flex items-center gap-1.5 text-[11px] font-bold text-gray-500 dark:text-gray-400">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> To Do
                </span>
                <span class="flex items-center gap-1.5 text-[11px] font-bold text-gray-500 dark:text-gray-400">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span> In Progress
                </span>
                <span class="flex items-center gap-1.5 text-[11px] font-bold text-gray-500 dark:text-gray-400">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Selesai
                </span>
            </div>
        </div>
    </div>

    <!-- CALENDAR GRID -->
    <div class="overflow-x-auto">
        <div class="min-w-[760px]">
            <!-- Day of Week Header -->
            <div class="grid grid-cols-7 border-b border-gray-100 dark:border-slate-700 bg-gray-50/70 dark:bg-slate-900/60 text-center">
                <?php foreach($indoDays as $idx => $dayName) : ?>
                    <div class="py-3 px-2 text-xs font-black uppercase tracking-wider <?= ($idx >= 5) ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' ?>">
                        <?= $dayName; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Date Cells Grid -->
            <div class="grid grid-cols-7 auto-rows-fr divide-x divide-y divide-gray-100 dark:divide-slate-700/80">
                <?php
                // 1. LEADING CELLS (Previous Month)
                for ($d = $prevStartDay; $d <= $daysInPrevMonth; $d++) {
                    $prevDateString = sprintf('%04d-%02d-%02d', $prevYear, $prevMonth, $d);
                    $cellTasks = $data['tasks_by_date'][$prevDateString] ?? [];
                ?>
                    <div class="min-h-[110px] md:min-h-[130px] p-2 bg-gray-50/40 dark:bg-slate-900/30 opacity-60 hover:opacity-100 transition group flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-400 dark:text-gray-600 ml-1"><?= $d; ?></span>
                            <button onclick="openAddTaskModal('<?= $prevDateString ?>')" class="opacity-0 group-hover:opacity-100 p-1 text-gray-400 hover:text-indigo-600 transition" title="Tambah Tugas">
                                <i class="fa-solid fa-plus text-xs"></i>
                            </button>
                        </div>
                        <div class="space-y-1 mt-1 flex-grow">
                            <?php foreach(array_slice($cellTasks, 0, 2) as $task) : ?>
                                <div onclick="openTaskDetail(<?= htmlspecialchars(json_encode($task), ENT_QUOTES, 'UTF-8') ?>)" 
                                     class="text-[11px] p-1.5 rounded-lg border-l-2 truncate cursor-pointer transition shadow-2xs hover:scale-[1.02] bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 <?= $task['status'] === 'done' ? 'border-emerald-500' : ($task['status'] === 'in_progress' ? 'border-indigo-500' : 'border-amber-500') ?>">
                                    <span class="font-bold truncate"><?= htmlspecialchars($task['title']) ?></span>
                                </div>
                            <?php endforeach; ?>
                            <?php if(count($cellTasks) > 2) : ?>
                                <span class="text-[10px] text-gray-400 font-bold block text-center">+<?= count($cellTasks) - 2 ?> lainnya</span>
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
                    <div class="min-h-[110px] md:min-h-[135px] p-2 bg-white dark:bg-slate-800 hover:bg-indigo-50/20 dark:hover:bg-slate-700/30 transition group flex flex-col justify-between relative <?= $isToday ? 'bg-indigo-50/30 dark:bg-indigo-950/20' : '' ?>">
                        <!-- Date Header -->
                        <div class="flex items-center justify-between mb-1.5">
                            <?php if ($isToday) : ?>
                                <span class="w-6 h-6 rounded-full bg-indigo-600 text-white font-black text-xs flex items-center justify-center shadow-md shadow-indigo-300 dark:shadow-none ring-2 ring-indigo-300 dark:ring-indigo-800">
                                    <?= $d; ?>
                                </span>
                            <?php else : ?>
                                <span class="text-xs font-black text-gray-700 dark:text-gray-300 ml-1"><?= $d; ?></span>
                            <?php endif; ?>

                            <div class="flex items-center gap-1">
                                <?php if ($taskCount > 0) : ?>
                                    <span class="text-[10px] font-black text-gray-400 dark:text-gray-500 mr-0.5"><?= $taskCount; ?></span>
                                <?php endif; ?>
                                <button onclick="openAddTaskModal('<?= $dateString ?>')" class="opacity-0 group-hover:opacity-100 p-1 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-slate-700 rounded-lg transition" title="Tambah Tugas pada <?= $d . ' ' . $indoMonths[$currMonth] ?>">
                                    <i class="fa-solid fa-plus text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Tasks List in Cell -->
                        <div class="space-y-1.5 flex-grow">
                            <?php 
                            $displayLimit = 3;
                            $displayed = 0;
                            foreach($cellTasks as $task) :
                                if ($displayed >= $displayLimit) break;
                                $displayed++;

                                // Status style
                                $statusBorder = 'border-amber-500';
                                $statusBg = 'bg-amber-50/80 dark:bg-amber-950/30 text-amber-900 dark:text-amber-200';
                                if ($task['status'] === 'done') {
                                    $statusBorder = 'border-emerald-500';
                                    $statusBg = 'bg-emerald-50/80 dark:bg-emerald-950/30 text-emerald-900 dark:text-emerald-200';
                                } elseif ($task['status'] === 'in_progress') {
                                    $statusBorder = 'border-indigo-500';
                                    $statusBg = 'bg-indigo-50/80 dark:bg-indigo-950/30 text-indigo-900 dark:text-indigo-200';
                                }

                                // Priority dot
                                $prioDot = 'bg-emerald-400';
                                if (($task['priority'] ?? '') === 'tinggi') $prioDot = 'bg-red-500';
                                elseif (($task['priority'] ?? '') === 'sedang') $prioDot = 'bg-orange-400';
                            ?>
                                <div onclick="openTaskDetail(<?= htmlspecialchars(json_encode($task), ENT_QUOTES, 'UTF-8') ?>)"
                                     class="p-1.5 rounded-lg border-l-[3px] <?= $statusBorder ?> <?= $statusBg ?> cursor-pointer hover:shadow-md transition hover:scale-[1.01] flex items-center justify-between gap-1 group/item">
                                    <div class="flex items-center gap-1.5 min-w-0 flex-grow">
                                        <span class="w-1.5 h-1.5 rounded-full <?= $prioDot ?> flex-shrink-0"></span>
                                        <span class="text-[11px] font-bold truncate leading-tight"><?= htmlspecialchars($task['title']) ?></span>
                                    </div>
                                    <?php if (!empty($task['progress'])) : ?>
                                        <span class="text-[9px] font-black opacity-70 flex-shrink-0"><?= $task['progress'] ?>%</span>
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
                                        class="w-full text-center py-1 rounded-lg text-[10px] font-black text-indigo-600 dark:text-indigo-400 bg-indigo-50/70 dark:bg-indigo-950/40 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 transition">
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
                    <div class="min-h-[110px] md:min-h-[130px] p-2 bg-gray-50/40 dark:bg-slate-900/30 opacity-60 hover:opacity-100 transition group flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-400 dark:text-gray-600 ml-1"><?= $d; ?></span>
                            <button onclick="openAddTaskModal('<?= $nextDateString ?>')" class="opacity-0 group-hover:opacity-100 p-1 text-gray-400 hover:text-indigo-600 transition" title="Tambah Tugas">
                                <i class="fa-solid fa-plus text-xs"></i>
                            </button>
                        </div>
                        <div class="space-y-1 mt-1 flex-grow">
                            <?php foreach(array_slice($cellTasks, 0, 2) as $task) : ?>
                                <div onclick="openTaskDetail(<?= htmlspecialchars(json_encode($task), ENT_QUOTES, 'UTF-8') ?>)" 
                                     class="text-[11px] p-1.5 rounded-lg border-l-2 truncate cursor-pointer transition shadow-2xs hover:scale-[1.02] bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 <?= $task['status'] === 'done' ? 'border-emerald-500' : ($task['status'] === 'in_progress' ? 'border-indigo-500' : 'border-amber-500') ?>">
                                    <span class="font-bold truncate"><?= htmlspecialchars($task['title']) ?></span>
                                </div>
                            <?php endforeach; ?>
                            <?php if(count($cellTasks) > 2) : ?>
                                <span class="text-[10px] text-gray-400 font-bold block text-center">+<?= count($cellTasks) - 2 ?> lainnya</span>
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
    <div class="xl:col-span-4 2xl:col-span-3 bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm p-5 md:p-6 transition-colors xl:sticky xl:top-24 flex flex-col">
        <!-- Card Header -->
        <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-slate-700">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-base shadow-sm">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <h3 class="font-black text-gray-900 dark:text-gray-100 text-base leading-tight">Tugas Mendatang</h3>
                    <p class="text-[11px] font-semibold text-gray-400 dark:text-gray-500">Batas waktu & jadwal terdekat</p>
                </div>
            </div>
            <span class="px-2.5 py-1 rounded-full text-xs font-black bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-800" id="upcomingCountBadge">
                <?= count($data['upcoming_tasks'] ?? []); ?>
            </span>
        </div>

        <!-- Filter Tabs / Pills -->
        <div class="flex items-center gap-1.5 py-3 border-b border-gray-100 dark:border-slate-700 overflow-x-auto text-[11px] font-bold">
            <button type="button" onclick="filterUpcomingTasks('all')" class="upcoming-tab-btn active px-3 py-1.5 rounded-xl bg-indigo-600 text-white shadow-sm transition" data-filter="all">Semua</button>
            <button type="button" onclick="filterUpcomingTasks('today')" class="upcoming-tab-btn px-3 py-1.5 rounded-xl text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 transition" data-filter="today">Hari Ini</button>
            <button type="button" onclick="filterUpcomingTasks('week')" class="upcoming-tab-btn px-3 py-1.5 rounded-xl text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 transition" data-filter="week">7 Hari</button>
            <button type="button" onclick="filterUpcomingTasks('urgent')" class="upcoming-tab-btn px-3 py-1.5 rounded-xl text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 transition" data-filter="urgent">Prioritas</button>
        </div>

        <!-- Task List Items -->
        <div class="mt-4 space-y-3 overflow-y-auto max-h-[600px] pr-1" id="upcomingTaskList">
            <?php if (empty($data['upcoming_tasks'])) : ?>
                <div class="py-12 px-4 text-center">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-500 flex items-center justify-center text-2xl mb-3">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <h4 class="font-extrabold text-sm text-gray-800 dark:text-gray-200">Semua Tugas Tuntas!</h4>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Tidak ada tugas mendatang yang tertunda saat ini.</p>
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
                <div class="upcoming-task-item group p-3.5 rounded-2xl bg-gray-50/80 dark:bg-slate-900/40 border border-gray-100 dark:border-slate-700/70 hover:border-indigo-300 dark:hover:border-indigo-500/50 hover:bg-white dark:hover:bg-slate-800/80 hover:shadow-md transition-all duration-200 cursor-pointer"
                     data-days="<?= $daysLeft ?>"
                     data-filter-days="<?= $filterDays ?>"
                     data-priority="<?= $task['priority'] ?? 'biasa' ?>"
                     onclick="openTaskDetail(<?= htmlspecialchars(json_encode($task), ENT_QUOTES, 'UTF-8') ?>)">
                     
                    <!-- Top Info: Tag, Priority, Deadline Badge -->
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-white dark:bg-slate-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-slate-700 uppercase">
                                <?= htmlspecialchars($task['tag'] ?: 'Tugas') ?>
                            </span>
                            <?php if (($task['priority'] ?? '') === 'tinggi') : ?>
                                <span class="px-1.5 py-0.5 rounded-md text-[9px] font-black bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800 uppercase flex items-center gap-1">
                                    <i class="fa-solid fa-fire text-[9px]"></i> Tinggi
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Days Left Badge -->
                        <?php if ($daysLeft < 0) : ?>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-black bg-rose-100 dark:bg-rose-950/50 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                                Terlambat <?= abs($daysLeft) ?>h
                            </span>
                        <?php elseif ($daysLeft == 0) : ?>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-black bg-amber-100 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800 animate-pulse">
                                Hari Ini
                            </span>
                        <?php elseif ($daysLeft == 1) : ?>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-black bg-blue-100 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                Besok
                            </span>
                        <?php else : ?>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-800">
                                H-<?= $daysLeft ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Title & Project -->
                    <h4 class="font-extrabold text-[13px] text-gray-900 dark:text-gray-100 leading-snug group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-2 mb-1">
                        <?= htmlspecialchars($task['title']) ?>
                    </h4>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 font-medium flex items-center gap-1.5 mb-2.5">
                        <i class="fa-solid fa-folder text-[10px] text-indigo-400"></i>
                        <span class="truncate"><?= htmlspecialchars($task['project_name']) ?></span>
                    </p>

                    <!-- Footer: Due Date, Assignee Avatar, Progress -->
                    <div class="flex items-center justify-between pt-2 border-t border-gray-100 dark:border-slate-800/80 text-[11px]">
                        <div class="flex items-center gap-1.5 text-gray-400 dark:text-gray-500 font-semibold">
                            <i class="fa-regular fa-calendar text-[10px]"></i>
                            <span><?= $dateFmt ?></span>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <?php if (!empty($task['progress'])) : ?>
                                <span class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/50 px-1.5 py-0.5 rounded">
                                    <?= $task['progress'] ?>%
                                </span>
                            <?php endif; ?>
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($task['assignee_name'] ?? 'User') ?>&background=random" 
                                 class="w-5 h-5 rounded-full border border-white dark:border-slate-700 shadow-xs" 
                                 title="<?= htmlspecialchars($task['assignee_name'] ?? 'TBA') ?>">
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Quick Add Button at bottom of upcoming card -->
        <div class="pt-4 mt-3 border-t border-gray-100 dark:border-slate-700">
            <button onclick="openAddTaskModal()" class="w-full py-2.5 rounded-xl border border-dashed border-indigo-200 dark:border-slate-700 hover:border-indigo-400 dark:hover:border-indigo-500 text-indigo-600 dark:text-indigo-400 text-xs font-bold flex items-center justify-center gap-2 hover:bg-indigo-50/50 dark:hover:bg-slate-700/40 transition">
                <i class="fa-solid fa-plus text-[10px]"></i>
                Tambah Tugas Baru
            </button>
        </div>
    </div>
</div>

<!-- ================= MODAL: DETAIL / QUICK PREVIEW TUGAS ================= -->
<div id="taskDetailModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm modal-overlay opacity-0 transition-opacity duration-300" onclick="toggleTaskDetailModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-3xl text-left shadow-2xl transform modal-content sm:my-8 sm:align-middle sm:max-w-lg sm:w-full transition-all">
            <div class="p-6 md:p-8">
                <!-- Header -->
                <div class="flex items-start justify-between gap-4 mb-6">
                    <div class="flex items-center gap-2">
                        <span id="detailTaskTag" class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800"></span>
                        <span id="detailTaskPriority" class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider border"></span>
                    </div>
                    <button type="button" onclick="toggleTaskDetailModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <h3 id="detailTaskTitle" class="text-xl md:text-2xl font-black text-gray-900 dark:text-white leading-tight mb-2"></h3>
                <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 font-medium mb-6">
                    <i class="fa-solid fa-folder text-indigo-500 text-sm"></i>
                    <span id="detailTaskProject"></span>
                </div>

                <div class="space-y-4 bg-gray-50 dark:bg-slate-900/50 p-5 rounded-2xl border border-gray-100 dark:border-slate-700/80 mb-6">
                    <!-- Status & Progress -->
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status & Progres</span>
                        <div class="flex items-center gap-2">
                            <span id="detailTaskStatus" class="px-2.5 py-1 rounded-lg text-xs font-black capitalize"></span>
                            <span id="detailTaskProgress" class="text-xs font-black text-indigo-600 dark:text-indigo-400"></span>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="w-full bg-gray-200 dark:bg-slate-700 h-2 rounded-full overflow-hidden">
                        <div id="detailTaskProgressBar" class="bg-indigo-600 h-full rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>

                    <!-- Due Date -->
                    <div class="flex items-center justify-between pt-2 border-t border-gray-200/60 dark:border-slate-800">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tenggat Waktu</span>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-calendar text-gray-400 text-xs"></i>
                            <span id="detailTaskDueDate" class="text-xs font-bold text-gray-800 dark:text-gray-200"></span>
                            <span id="detailTaskDaysLeft" class="text-[10px] font-black px-2 py-0.5 rounded-md"></span>
                        </div>
                    </div>

                    <!-- Assignee -->
                    <div class="flex items-center justify-between pt-2 border-t border-gray-200/60 dark:border-slate-800">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Penanggung Jawab</span>
                        <div class="flex items-center gap-2" id="detailTaskAssignee">
                            <!-- Populated via JS -->
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-3">
                    <button type="button" id="detailBtnEdit" class="flex-1 py-3 px-4 rounded-xl bg-indigo-600 text-white font-black text-xs uppercase tracking-wider hover:bg-indigo-700 transition flex items-center justify-center gap-2 shadow-lg shadow-indigo-100 dark:shadow-none">
                        <i class="fa-solid fa-pen text-xs"></i>
                        <span>Ubah Tugas</span>
                    </button>
                    <button type="button" id="detailBtnDelete" class="py-3 px-4 rounded-xl bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 font-black text-xs uppercase tracking-wider hover:bg-red-100 dark:hover:bg-red-900/60 border border-red-200 dark:border-red-900/50 transition flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                        <span>Hapus</span>
                    </button>
                    <button type="button" onclick="toggleTaskDetailModal()" class="py-3 px-4 rounded-xl bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300 font-bold text-xs uppercase tracking-wider hover:bg-gray-200 dark:hover:bg-slate-600 transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= MODAL: ALL TASKS IN A DAY ================= -->
<div id="dayTasksModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm modal-overlay opacity-0 transition-opacity duration-300" onclick="toggleDayTasksModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-3xl text-left shadow-2xl transform modal-content sm:my-8 sm:align-middle sm:max-w-lg sm:w-full transition-all">
            <div class="p-6 md:p-8">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 id="dayTasksModalTitle" class="text-xl font-black text-gray-900 dark:text-white"></h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500 font-semibold mt-0.5">Daftar seluruh tugas pada tanggal ini</p>
                    </div>
                    <button type="button" onclick="toggleDayTasksModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <div id="dayTasksModalList" class="space-y-2.5 max-h-96 overflow-y-auto pr-1 mb-6">
                    <!-- Populated via JS -->
                </div>

                <div class="flex justify-between items-center gap-3">
                    <button type="button" id="dayTasksAddBtn" class="flex-1 py-3 px-4 rounded-xl bg-indigo-600 text-white font-bold text-xs uppercase tracking-wider hover:bg-indigo-700 transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>Tambah Tugas</span>
                    </button>
                    <button type="button" onclick="toggleDayTasksModal()" class="py-3 px-6 rounded-xl bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300 font-bold text-xs uppercase tracking-wider hover:bg-gray-200 dark:hover:bg-slate-600 transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= MODAL: TAMBAH / UBAH TUGAS ================= -->
<div id="taskModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm modal-overlay opacity-0 transition-opacity duration-300" aria-hidden="true" onclick="toggleTaskModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-3xl text-left shadow-2xl transform modal-content sm:my-8 sm:align-middle sm:max-w-xl sm:w-full transition-colors">
            <form action="<?= BASEURL; ?>/task/add" method="post" id="taskForm">
                <input type="hidden" name="id" id="task_id">
                <input type="hidden" name="return_to" id="return_to" value="<?= htmlspecialchars($currentCalUrl) ?>">

                <div class="p-8 md:p-10">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h3 class="text-2xl font-black text-gray-900 dark:text-gray-100 tracking-tight transition-colors" id="taskModalTitle">Buat Tugas Baru</h3>
                            <p class="text-gray-400 dark:text-gray-500 text-sm font-bold mt-1 transition-colors">Atur rincian tugas untuk ditampilkan pada kalender.</p>
                        </div>
                        <button type="button" onclick="toggleTaskModal()" class="text-gray-300 dark:text-gray-600 hover:text-gray-500 dark:hover:text-gray-400 transition">
                            <i class="fa-solid fa-xmark text-2xl"></i>
                        </button>
                    </div>

                    <div class="space-y-6">
                        <!-- Judul Tugas -->
                        <div>
                            <label for="title" class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1">Judul Tugas</label>
                            <input type="text" name="title" id="title" required class="w-full bg-white dark:bg-slate-900 border-[1.5px] border-gray-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold text-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition" placeholder="Contoh: Pembuatan Modul Kalender">
                        </div>

                        <!-- Project & Assignee -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="project_id" class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1">Pilih Project</label>
                                <div class="select-container" id="projectSelectContainer">
                                    <div class="select-trigger" data-placeholder="Pilih Project...">
                                        <span class="select-trigger-text text-gray-400 dark:text-gray-500">Pilih Project...</span>
                                        <i class="fa-solid fa-chevron-down select-trigger-icon flex items-center justify-center text-xs"></i>
                                    </div>
                                    <div class="select-options">
                                        <!-- Search Box Header -->
                                        <div class="p-2.5 border-b border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 sticky top-0 z-20">
                                            <div class="relative">
                                                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                                                <input type="text" class="select-search-input w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl pl-8 pr-3 py-2 text-xs font-bold text-gray-800 dark:text-gray-200 outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="Cari nama project atau lead...">
                                            </div>
                                        </div>
                                        <div class="select-options-list">
                                            <?php foreach($data['projects'] as $p) : ?>
                                                <div class="select-option flex items-center justify-between gap-3" 
                                                     data-value="<?= $p['id']; ?>" 
                                                     data-name="<?= htmlspecialchars($p['name'], ENT_QUOTES); ?>"
                                                     data-search="<?= htmlspecialchars(strtolower($p['name'] . ' ' . ($p['leader_name'] ?? '')), ENT_QUOTES); ?>">
                                                    <div class="flex items-center gap-3 min-w-0">
                                                        <div class="w-8 h-8 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0">
                                                            <i class="fa-solid fa-folder text-xs"></i>
                                                        </div>
                                                        <div class="flex flex-col min-w-0">
                                                            <span class="font-bold text-xs truncate leading-tight text-gray-800 dark:text-gray-200"><?= htmlspecialchars($p['name']); ?></span>
                                                            <span class="text-[10px] text-gray-400 dark:text-gray-500 flex items-center gap-1 mt-0.5 truncate">
                                                                <i class="fa-solid fa-user-tie text-[9px] text-indigo-500"></i>
                                                                <span>Lead: <strong class="text-gray-600 dark:text-gray-300 font-semibold"><?= htmlspecialchars($p['leader_name'] ?? 'Belum ditentukan'); ?></strong></span>
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
                                <label for="assignee_id" class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1">Penanggung Jawab</label>
                                <div class="select-container" id="assigneeSelectContainer">
                                    <div class="select-trigger" data-placeholder="Pilih Anggota...">
                                        <span class="select-trigger-text text-gray-400 dark:text-gray-500">Pilih Anggota...</span>
                                        <i class="fa-solid fa-chevron-down select-trigger-icon flex items-center justify-center text-xs"></i>
                                    </div>
                                    <div class="select-options">
                                        <?php foreach($data['team'] as $m) : ?>
                                            <div class="select-option" data-value="<?= $m['id']; ?>"><?= htmlspecialchars($m['name']); ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <input type="hidden" name="assignee_id" id="assignee_id_field">
                            </div>
                            <?php else : ?>
                            <div class="flex flex-col justify-end">
                                <label class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1">Penanggung Jawab</label>
                                <div class="bg-white dark:bg-slate-900 border-[1.5px] border-gray-200 dark:border-slate-700 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-500 dark:text-gray-400 flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user']['name']); ?>&background=random" class="w-6 h-6 rounded-full border border-gray-100 dark:border-slate-700">
                                    <span><?= htmlspecialchars($_SESSION['user']['name']); ?> (Anda)</span>
                                </div>
                                <input type="hidden" name="assignee_id" id="assignee_id_field" value="<?= $_SESSION['user']['id']; ?>">
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Tag & Priority -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="tag" class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1">Label / Tag</label>
                                <input type="text" name="tag" id="tag" required class="w-full bg-white dark:bg-slate-900 border-[1.5px] border-gray-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold text-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition" placeholder="Contoh: UI/UX">
                            </div>
                            <div>
                                <label for="priority_field" class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1">Prioritas</label>
                                <div class="select-container" id="prioritySelectContainer">
                                    <div class="select-trigger" data-placeholder="Pilih Prioritas...">
                                        <span class="select-trigger-text text-gray-400 dark:text-gray-500">Pilih Prioritas...</span>
                                        <i class="fa-solid fa-chevron-down select-trigger-icon flex items-center justify-center text-xs"></i>
                                    </div>
                                    <div class="select-options">
                                        <div class="select-option" data-value="tinggi">Tinggi</div>
                                        <div class="select-option" data-value="sedang">Sedang</div>
                                        <div class="select-option" data-value="biasa">Biasa</div>
                                    </div>
                                </div>
                                <input type="hidden" name="priority" id="priority_field" value="biasa">
                            </div>
                        </div>

                        <!-- Due Date -->
                        <div>
                            <label for="due_date" class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1">Tenggat Waktu</label>
                            <input type="date" name="due_date" id="due_date" required class="w-full bg-white dark:bg-slate-900 border-[1.5px] border-gray-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold text-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition cursor-pointer" onclick="this.showPicker()">
                        </div>

                        <!-- Status & Progress -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="status_field" class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1">Status Tugas</label>
                                <div class="select-container" id="statusSelectContainer">
                                    <div class="select-trigger" data-placeholder="Pilih Status...">
                                        <span class="select-trigger-text text-gray-400 dark:text-gray-500">Pilih Status...</span>
                                        <i class="fa-solid fa-chevron-down select-trigger-icon flex items-center justify-center text-xs"></i>
                                    </div>
                                    <div class="select-options">
                                        <div class="select-option" data-value="todo">To Do</div>
                                        <div class="select-option" data-value="in_progress">In Progress</div>
                                        <div class="select-option" data-value="done">Done</div>
                                    </div>
                                </div>
                                <input type="hidden" name="status" id="status_field" value="todo">
                            </div>
                            <div id="progress-container">
                                <label for="progress" class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1 flex justify-between">
                                    Progress (%)
                                    <span id="progress-val-text" class="text-indigo-600 dark:text-indigo-400 font-black">0%</span>
                                </label>
                                <div class="relative py-2 px-1">
                                    <input type="range" name="progress" id="progress" min="0" max="100" class="w-full h-2 bg-gray-100 dark:bg-slate-700 rounded-lg appearance-none cursor-pointer accent-indigo-600 transition-all hover:bg-gray-200 dark:hover:bg-slate-600" value="0">
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="tag_color" id="tag_color" value="bg-blue-50 text-blue-600">
                    </div>

                    <div class="flex gap-4 mt-10">
                        <button type="button" onclick="toggleTaskModal()" class="flex-1 px-8 py-4 rounded-2xl bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300 font-black text-sm uppercase tracking-widest hover:bg-gray-200 dark:hover:bg-slate-600 transition">Batal</button>
                        <button type="submit" class="flex-1 px-8 py-4 rounded-2xl bg-indigo-600 text-white font-black text-sm uppercase tracking-widest hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 dark:shadow-none">Simpan Tugas</button>
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
            // default to today or first day of current calendar month
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
            prioEl.className = 'px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-400 border border-red-200 dark:border-red-800';
        } else if (task.priority === 'sedang') {
            prioEl.className = 'px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-orange-50 text-orange-600 dark:bg-orange-950/40 dark:text-orange-400 border border-orange-200 dark:border-orange-800';
        } else {
            prioEl.className = 'px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800';
        }

        // Status
        const statusEl = document.getElementById('detailTaskStatus');
        if (task.status === 'done') {
            statusEl.innerText = 'Selesai';
            statusEl.className = 'px-2.5 py-1 rounded-lg text-xs font-black capitalize bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300';
        } else if (task.status === 'in_progress') {
            statusEl.innerText = 'In Progress';
            statusEl.className = 'px-2.5 py-1 rounded-lg text-xs font-black capitalize bg-indigo-50 text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-300';
        } else {
            statusEl.innerText = 'To Do';
            statusEl.className = 'px-2.5 py-1 rounded-lg text-xs font-black capitalize bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300';
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
            daysLeftEl.className = 'text-[10px] font-black px-2 py-0.5 rounded-md bg-red-500 text-white shadow-xs';
        } else if (diffDays === 0) {
            daysLeftEl.innerText = 'Hari Ini';
            daysLeftEl.className = 'text-[10px] font-black px-2 py-0.5 rounded-md bg-orange-500 text-white shadow-xs';
        } else if (diffDays === 1) {
            daysLeftEl.innerText = 'Besok (H-1)';
            daysLeftEl.className = 'text-[10px] font-black px-2 py-0.5 rounded-md bg-amber-500 text-white shadow-xs';
        } else {
            daysLeftEl.innerText = `H-${diffDays}`;
            daysLeftEl.className = 'text-[10px] font-black px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400';
        }

        // Assignee
        const assigneeName = task.assignee_name || 'Belum Ditugaskan';
        const assigneeContainer = document.getElementById('detailTaskAssignee');
        assigneeContainer.innerHTML = `
            <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(assigneeName)}&background=random" class="w-6 h-6 rounded-full border border-gray-100 dark:border-slate-700">
            <span class="text-xs font-bold text-gray-800 dark:text-gray-200">${assigneeName}</span>
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
            const bgCls = isDone ? 'bg-emerald-50/50 dark:bg-emerald-950/20' : (isInProg ? 'bg-indigo-50/50 dark:bg-indigo-950/20' : 'bg-amber-50/50 dark:bg-amber-950/20');
            
            const item = document.createElement('div');
            item.className = `p-3 rounded-2xl border-l-4 ${borderCls} ${bgCls} border border-gray-100 dark:border-slate-700/80 flex items-center justify-between gap-3 hover:shadow-md transition cursor-pointer`;
            item.innerHTML = `
                <div class="min-w-0 flex-grow">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded bg-white/60 dark:bg-slate-800/80 text-gray-600 dark:text-gray-300">${t.tag || 'Tugas'}</span>
                        <span class="text-[10px] font-bold text-gray-400 truncate">${t.project_name || ''}</span>
                    </div>
                    <h5 class="text-sm font-bold text-gray-900 dark:text-white truncate">${t.title}</h5>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <span class="text-xs font-black text-indigo-600 dark:text-indigo-400">${t.progress || 0}%</span>
                    <button class="p-1.5 text-gray-400 hover:text-indigo-600 transition" title="Lihat Detail">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </button>
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
        text.className = 'font-black';
        if (val >= 71) text.classList.add('text-green-500');
        else if (val >= 41) text.classList.add('text-yellow-400');
        else text.classList.add('text-red-500');

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
            const inner = actionContent.querySelector('div');
            inner.classList.remove('hidden');
            actionArea.appendChild(inner);
        }

        // Custom Select in Modal
        initCustomSelect('projectSelectContainer', 'project_id_field');
        if (document.getElementById('assigneeSelectContainer')) {
            initCustomSelect('assigneeSelectContainer', 'assignee_id_field');
        }
        initCustomSelect('statusSelectContainer', 'status_field');
        initCustomSelect('prioritySelectContainer', 'priority_field');

        // Progress Slider events
        const slider = document.getElementById('progress');
        const statusField = document.getElementById('status_field');
        if (slider && statusField) {
            slider.addEventListener('input', function() {
                const val = parseInt(this.value);
                if (val === 100) statusField.value = 'done';
                else if (val > 0) statusField.value = 'in_progress';
                else statusField.value = 'todo';

                initCustomSelect('statusSelectContainer', 'status_field');
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
                btn.className = 'upcoming-tab-btn active px-3 py-1.5 rounded-xl bg-indigo-600 text-white shadow-sm transition';
            } else {
                btn.className = 'upcoming-tab-btn px-3 py-1.5 rounded-xl text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 transition';
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
