<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <!-- Stats Cards -->
    <div class="bg-white dark:bg-slate-800 p-4 sm:p-6 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm hover:shadow-md transition flex flex-col items-center sm:items-start text-center sm:text-left">
        <div class="flex items-center justify-between mb-3 sm:mb-4">
            <div class="bg-purple-100 dark:bg-purple-900/30 p-2.5 sm:p-3 rounded-2xl">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path></svg>
            </div>
        </div>
        <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm font-medium mb-1"><?= ($data['user_role'] ?? 'admin') === 'admin' ? 'Total Proyek' : 'Proyek Saya'; ?></p>
        <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100 tracking-tight transition-colors"><?= $data['stats']['projects']; ?></h3>
    </div>

    <div class="bg-white dark:bg-slate-800 p-4 sm:p-6 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm hover:shadow-md transition flex flex-col items-center sm:items-start text-center sm:text-left">
        <div class="flex items-center justify-between mb-3 sm:mb-4">
            <div class="bg-blue-100 dark:bg-blue-900/30 p-2.5 sm:p-3 rounded-2xl">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
        </div>
        <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm font-medium mb-1"><?= ($data['user_role'] ?? 'admin') === 'admin' ? 'Tugas Aktif' : 'Tugas Saya'; ?></p>
        <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100 tracking-tight transition-colors"><?= $data['stats']['tasks']; ?></h3>
    </div>

    <div class="bg-white dark:bg-slate-800 p-4 sm:p-6 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm hover:shadow-md transition flex flex-col items-center sm:items-start text-center sm:text-left">
        <div class="flex items-center justify-between mb-3 sm:mb-4">
            <div class="bg-indigo-100 dark:bg-indigo-900/30 p-2.5 sm:p-3 rounded-2xl">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm font-medium mb-1">Tugas Selesai</p>
        <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100 tracking-tight transition-colors"><?= number_format($data['stats']['tasks'] * 0.8, 0); ?></h3>
    </div>

    <?php if(($data['user_role'] ?? 'admin') === 'admin') : ?>
    <div class="bg-white dark:bg-slate-800 p-4 sm:p-6 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm hover:shadow-md transition flex flex-col items-center sm:items-start text-center sm:text-left">
        <div class="flex items-center justify-between mb-3 sm:mb-4">
            <div class="bg-gray-100 dark:bg-gray-700 p-2.5 sm:p-3 rounded-2xl">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>
        <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm font-medium mb-1">Anggota Tim</p>
        <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100 tracking-tight transition-colors"><?= $data['stats']['members']; ?></h3>
    </div>
    <?php else : ?>
    <div class="bg-white dark:bg-slate-800 p-4 sm:p-6 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm hover:shadow-md transition flex flex-col items-center sm:items-start text-center sm:text-left">
        <div class="flex items-center justify-between mb-3 sm:mb-4">
            <div class="bg-emerald-100 dark:bg-emerald-900/30 p-2.5 sm:p-3 rounded-2xl">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
        </div>
        <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm font-medium mb-1">Role Anda</p>
        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 capitalize tracking-tight transition-colors"><?= $_SESSION['user']['role']; ?></h3>
    </div>
    <?php endif; ?>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column: Project Progress -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Left Column: Project Progress -->
        <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm transition-colors">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100"><?= ($data['user_role'] ?? 'admin') === 'admin' ? 'Progres Proyek' : 'Proyek Saya'; ?></h2>
                <a href="<?= BASEURL; ?>/project" class="text-indigo-600 dark:text-indigo-400 text-sm font-bold hover:underline">Lihat Semua</a>
            </div>
            
            <div class="space-y-8">
                <?php if(empty($data['projects_progress'])) : ?>
                    <p class="text-gray-400 text-sm italic"><?= ($data['user_role'] ?? 'admin') === 'admin' ? 'Belum ada proyek aktif.' : 'Anda belum ditugaskan ke proyek manapun.'; ?></p>
                <?php endif; ?>
                <?php foreach(array_slice($data['projects_progress'], 0, 3) as $p) : 
                    $progress = (int)round($p['avg_progress']);
                    
                    // Dynamic Colors
                    $pColor = 'bg-red-500';
                    $borderCol = 'border-red-500';
                    $textCol = 'text-red-500';
                    
                    if ($progress >= 71) {
                        $pColor = 'bg-green-500';
                        $borderCol = 'border-green-500';
                        $textCol = 'text-green-500';
                    } elseif ($progress >= 41) {
                        $pColor = 'bg-yellow-400';
                        $borderCol = 'border-yellow-400';
                        $textCol = 'text-yellow-400';
                    }
                ?>
                <div class="group/proj">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-3">
                           <div class="w-2 h-2 rounded-full <?= $pColor ?>"></div>
                           <span class="font-bold text-gray-800 dark:text-gray-200 transition-colors group-hover/proj:text-indigo-600"><?= $p['name']; ?></span>
                        </div>
                        <span class="<?= $textCol ?> text-sm font-black"><?= $progress; ?>%</span>
                    </div>
                    
                    <div class="relative w-full bg-gray-50 dark:bg-slate-700 h-1.5 sm:h-2 rounded-full mb-4">
                        <div class="h-full rounded-full <?= $pColor ?> transition-all duration-700" style="width: <?= $progress; ?>%"></div>
                        <!-- Slider Look decoration -->
                        <div class="absolute top-1/2 -translate-y-1/2 w-3 sm:w-3.5 h-3 sm:h-3.5 bg-white dark:bg-slate-900 border-2 <?= $borderCol ?> rounded-full shadow-sm shadow-black/10 transition-all duration-700 pointer-events-none" style="left: calc(<?= $progress; ?>% - 6px)"></div>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-400 dark:text-gray-500">Project Lead: <span class="font-bold text-gray-600 dark:text-gray-300"><?= $p['leader_name'] ?? 'Belum ada'; ?></span></span>
                        <div class="flex -space-x-2">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($p['leader_name'] ?? 'User'); ?>&background=random" class="w-6 h-6 rounded-full border-2 border-white dark:border-slate-800 shadow-sm" title="<?= $p['leader_name'] ?? 'User'; ?>">
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Task Distribution Chart -->
        <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm transition-colors">
            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-8">Distribusi Tugas</h2>
            <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                <?php
                    $dist = ['todo' => 0, 'in_progress' => 0, 'done' => 0];
                    $total = 0;
                    foreach($data['task_distribution'] as $d) {
                        $dist[$d['status']] = $d['count'];
                        $total += $d['count'];
                    }
                    
                    $pArr = [
                        'todo' => $total > 0 ? round(($dist['todo'] / $total) * 100) : 0,
                        'in_progress' => $total > 0 ? round(($dist['in_progress'] / $total) * 100) : 0,
                        'done' => $total > 0 ? round(($dist['done'] / $total) * 100) : 0
                    ];

                    // For SVG donut: dasharray values
                    $offset1 = 100 - $pArr['done'];
                    $offset2 = $offset1 - $pArr['in_progress'];
                ?>
                <div class="relative w-40 h-40">
                    <svg class="w-full h-full" viewBox="0 0 36 36">
                        <!-- Background Circle (Todo - Grey) -->
                        <path class="text-gray-100 dark:text-gray-700 transition-colors" stroke-width="3" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        
                        <!-- Done Circle (Green) -->
                        <path class="text-green-500" stroke-width="3" stroke-dasharray="<?= $pArr['done']; ?>, 100" stroke-dashoffset="0" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        
                        <!-- In Progress Circle (Indigo) -->
                        <path class="text-indigo-600" stroke-width="3" stroke-dasharray="<?= $pArr['in_progress']; ?>, 100" stroke-dashoffset="-<?= $pArr['done']; ?>" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-2xl font-bold text-gray-900 dark:text-gray-100 transition-colors"><?= $total; ?></span>
                        <span class="text-[10px] text-gray-400 dark:text-gray-500 uppercase font-bold">Total</span>
                    </div>
                </div>
                <div class="space-y-4 w-full md:w-auto">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400 font-medium">Selesai (<?= $pArr['done']; ?>%)</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-indigo-600 rounded-full"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400 font-medium">Dalam Proses (<?= $pArr['in_progress']; ?>%)</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400 font-medium">Rencana (<?= $pArr['todo']; ?>%)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Recent Activity -->
    <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm flex flex-col transition-colors">
        <div class="flex justify-between items-center mb-10">
            <h2 class="text-xl font-black text-gray-900 dark:text-gray-100">Aktivitas Terbaru</h2>
            <a href="<?= BASEURL; ?>/activity" class="text-indigo-600 dark:text-indigo-400 text-sm font-bold hover:underline">Lihat Semua</a>
        </div>
        
        <?php
        function timeAgo($timestamp) {
            if (!$timestamp) return "-";
            $time = is_numeric($timestamp) ? $timestamp : strtotime($timestamp);
            $now = time();
            $diff = $now - $time;
            
            if ($diff < 60) return "BARU SAJA";
            if ($diff < 3600) return floor($diff / 60) . " MENIT YANG LALU";
            if ($diff < 86400) return floor($diff / 3600) . " JAM YANG LALU";
            if ($diff < 172800) return "KEMARIN";
            if ($diff < 604800) return floor($diff / 86400) . " HARI YANG LALU";
            return strtoupper(date('d M Y', $time));
        }
        ?>

        <div class="relative space-y-12 flex-grow before:absolute before:left-[19px] before:top-2 before:bottom-2 before:w-[2px] before:bg-indigo-50/50 dark:before:bg-slate-700/50 transition-colors">
            <?php if(empty($data['activities'])) : ?>
                <p class="text-gray-400 text-sm italic">Belum ada aktivitas baru.</p>
            <?php endif; ?>
            
            <?php foreach(array_slice($data['activities'], 0, 5) as $activity) : 
                // Icon & Color Logic based on action
                $iconColor = "bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400";
                $svgIcon = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>';
                
                $action = strtolower($activity['action']);
                if (strpos($action, 'menambah') !== false || strpos($action, 'membuat') !== false) {
                    $iconColor = "bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400";
                    $svgIcon = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>';
                    if (strpos($action, 'tugas') !== false) {
                        $svgIcon = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>';
                    }
                } elseif (strpos($action, 'perbarui') !== false || strpos($action, 'edit') !== false || strpos($action, 'ubah') !== false) {
                    $iconColor = "bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400";
                    $svgIcon = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>';
                } elseif (strpos($action, 'hapus') !== false) {
                    $iconColor = "bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400";
                    $svgIcon = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>';
                } elseif (strpos($action, 'anggota') !== false) {
                    $iconColor = "bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400";
                    $svgIcon = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>';
                }
            ?>
            <div class="relative pl-12">
                <!-- Timeline Icon -->
                <div class="absolute left-0 top-0 w-10 h-10 <?= $iconColor ?> rounded-full border-4 border-white dark:border-slate-800 flex items-center justify-center z-10 shadow-sm transition-colors">
                    <?= $svgIcon ?>
                </div>
                
                <div class="flex-grow min-w-0">
                   <p class="text-[14px] text-gray-700 dark:text-gray-300 leading-relaxed mb-0.5">
                       <span class="font-black text-gray-900 dark:text-gray-100"><?= ($activity['user_id'] == $_SESSION['user']['id'] ? 'Anda' : $activity['user_name']); ?></span> 
                       <span class="text-gray-500 dark:text-gray-400"><?= $activity['action']; ?></span> 
                       <span class="text-indigo-600 dark:text-indigo-400 font-bold"><?= $activity['target']; ?></span>
                   </p>
                   
                   <?php if(!empty($activity['info']) && $activity['info'] != $activity['target']) : ?>
                       <p class="text-[13px] text-gray-400 dark:text-gray-500 italic mb-2 leading-relaxed border-l-2 border-gray-100 dark:border-slate-700 pl-3">
                           "<?= $activity['info']; ?>"
                       </p>
                   <?php endif; ?>

                   <span class="text-[10px] font-black tracking-widest text-gray-400 dark:text-gray-600 opacity-80 uppercase">
                       <?= timeAgo($activity['created_at']); ?>
                   </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
