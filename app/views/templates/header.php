<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class'
        }
        // Initialize theme before page load
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="<?= BASEURL; ?>/img/favicon.png">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-active { background-color: #f3f4f6; color: #4f46e5; border-radius: 0.75rem; }
        
        /* Modal Animations */
        .modal-overlay {
            transition: opacity 0.3s ease-in-out;
        }
        .modal-content {
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            transform: scale(0.9) translateY(20px);
            opacity: 0;
        }
        .modal-active .modal-overlay {
            opacity: 1;
        }
        .modal-active .modal-content {
            transform: scale(1) translateY(0);
            opacity: 1;
        }

        /* Professional Custom Dropdown (Matching Image) */
        .select-container {
            position: relative;
            width: 100%;
        }
        .select-trigger {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            background-color: #fff;
            border: 1.5px solid #e5e7eb;
            padding: 1rem 1.25rem;
            border-radius: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
            user-select: none;
        }
        .select-trigger:hover {
            border-color: #d1d5db;
        }
        .select-trigger.active {
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }
        .select-trigger-text {
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
        }
        .select-trigger-icon {
            width: 1rem;
            height: 1rem;
            color: #9ca3af;
            transition: transform 0.2s ease;
        }
        .select-trigger.active .select-trigger-icon {
            transform: rotate(180deg);
            color: #4f46e5;
        }
        .select-options {
            position: absolute;
            top: calc(100% + 0.5rem);
            left: 0;
            right: 0;
            background-color: #fff;
            border: 1px solid #f3f4f6;
            border-radius: 1.25rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            z-index: 150;
            max-height: 15rem;
            overflow-y: auto;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .select-trigger.active + .select-options {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .select-option {
            padding: 0.875rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #4b5563;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .select-option:hover {
            background-color: #f9fafb;
            color: #111827;
        }
        .select-option.selected {
            background-color: #f5f3ff;
            color: #4f46e5;
            font-weight: 700;
        }
        .select-option.selected .check-icon {
            opacity: 1;
        }

        /* Custom Scrollbar */
        .select-options::-webkit-scrollbar {
            width: 6px;
        }
        .select-options::-webkit-scrollbar-track {
            background: transparent;
        }
        .select-options::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 10px;
        }
        .select-options::-webkit-scrollbar-thumb:hover {
            background: #d1d5db;
        }
        /* Premium Range Slider */
        input[type="range"]::-webkit-slider-thumb {
            appearance: none;
            width: 20px;
            height: 20px;
            background: #4f46e5;
            border: 4px solid #fff;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
            transition: all 0.2s ease;
        }
        input[type="range"]::-webkit-slider-thumb:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 15px rgba(79, 70, 229, 0.4);
        }
        input[type="range"]:active::-webkit-slider-thumb {
            transform: scale(0.95);
        }

        /* Dark Mode Specific Overrides for non-Tailwind elements */
        .dark body { background-color: #0f172a; }
        .dark .sidebar-active { background-color: #1e293b; color: #818cf8; }
        .dark .select-trigger { background-color: #0f172a; border-color: #334155; color: #e5e7eb; }
        .dark .select-trigger-text { color: #e5e7eb !important; }
        .dark .select-trigger-text.text-gray-400 { color: #64748b !important; } /* slate-500 placeholder */
        .dark .select-options { background-color: #0f172a; border-color: #334155; }
        .dark .select-option { color: #cbd5e1; } /* slate-300 */
        .dark .select-option:hover { background-color: #1e293b; color: #fff; }
        .dark .select-option.selected { background-color: #1e293b; color: #818cf8; border-left: 3px solid #6366f1; }
        .dark input[type="range"]::-webkit-slider-thumb { border-color: #1e293b; }
    </style>
    <script>
        function toggleDarkMode() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }

        // Global logic for professional dropdowns
        document.addEventListener('click', function (e) {
            // Close all dropdowns when clicking outside
            if (!e.target.closest('.select-container')) {
                document.querySelectorAll('.select-trigger').forEach(trigger => {
                    trigger.classList.remove('active');
                });
            }
        });

        function initCustomSelect(containerId, inputId, onSelect = null) {
            const container = document.getElementById(containerId);
            if (!container) return;
            
            const trigger = container.querySelector('.select-trigger');
            const options = Array.from(container.querySelectorAll('.select-option'));
            const hiddenInput = document.getElementById(inputId);
            const triggerText = container.querySelector('.select-trigger-text');
            
            if (!hiddenInput) {
                console.error('initCustomSelect: Hidden input not found for ID', inputId);
                return;
            }

            // Initialization block - only runs once per container
            if (!container.dataset.initialized) {
                trigger.onclick = (e) => {
                    e.stopPropagation();
                    // Close all other dropdown triggers
                    document.querySelectorAll('.select-trigger').forEach(t => {
                        if (t !== trigger) t.classList.remove('active');
                    });
                    trigger.classList.toggle('active');
                };

                options.forEach(opt => {
                    opt.onclick = (e) => {
                        e.stopPropagation();
                        const value = opt.getAttribute('data-value');
                        const text = opt.textContent.trim();
                        
                        hiddenInput.value = value;
                        triggerText.textContent = text;
                        triggerText.classList.remove('text-gray-400');
                        triggerText.classList.add('text-gray-900');
                        
                        options.forEach(o => o.classList.remove('selected'));
                        opt.classList.add('selected');
                        
                        trigger.classList.remove('active');

                        if (onSelect) onSelect(value, text);
                        
                        // Fire a change event on the hidden input
                        hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
                    };
                });
                container.dataset.initialized = "true";
            }
            
            // Sync UI state with hidden input value
            const currentVal = hiddenInput.value;
            const matchingOpt = options.find(o => o.getAttribute('data-value') === currentVal);
            
            if (matchingOpt) {
                triggerText.textContent = matchingOpt.textContent.trim();
                triggerText.classList.remove('text-gray-400');
                triggerText.classList.add('text-gray-900');
                options.forEach(o => o.classList.remove('selected'));
                matchingOpt.classList.add('selected');
            } else {
                // If no matching value, reset to placeholder
                const placeholder = trigger.getAttribute('data-placeholder') || 'Pilih...';
                triggerText.textContent = placeholder;
                triggerText.classList.add('text-gray-400');
                triggerText.classList.remove('text-gray-900');
                options.forEach(o => o.classList.remove('selected'));
            }
        }

        function initMultiSelect(containerId, inputId, onSelect = null) {
            const container = document.getElementById(containerId);
            if (!container || !container.querySelector('.select-trigger')) return;
            
            const trigger = container.querySelector('.select-trigger');
            const options = Array.from(container.querySelectorAll('.select-option'));
            const hiddenInput = document.getElementById(inputId);
            const triggerText = container.querySelector('.select-trigger-text');
            
            if (!hiddenInput) return;

            if (!container.dataset.multiInitialized) {
                trigger.onclick = (e) => {
                    e.stopPropagation();
                    document.querySelectorAll('.select-trigger').forEach(t => {
                        if (t !== trigger) t.classList.remove('active');
                    });
                    trigger.classList.toggle('active');
                };

                options.forEach(opt => {
                    opt.onclick = (e) => {
                        e.stopPropagation();
                        opt.classList.toggle('selected');
                        
                        const selectedOpts = options.filter(o => o.classList.contains('selected'));
                        const values = selectedOpts.map(o => o.getAttribute('data-value'));
                        const texts = selectedOpts.map(o => o.textContent.trim());
                        
                        hiddenInput.value = JSON.stringify(values);
                        
                        if (values.length > 0) {
                            triggerText.textContent = texts.length > 2 
                                ? texts.slice(0, 2).join(', ') + '... (+' + (texts.length - 2) + ')'
                                : texts.join(', ');
                            triggerText.classList.remove('text-gray-400');
                            triggerText.classList.add('text-gray-900');
                        } else {
                            const placeholder = trigger.getAttribute('data-placeholder') || 'Pilih...';
                            triggerText.textContent = placeholder;
                            triggerText.classList.add('text-gray-400');
                            triggerText.classList.remove('text-gray-900');
                        }

                        if (onSelect) onSelect(values);
                        hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
                    };
                });
                container.dataset.multiInitialized = "true";
            }

            // Sync UI state
            try {
                let currentVals = [];
                try {
                    currentVals = JSON.parse(hiddenInput.value || '[]');
                } catch(e) {
                    if (hiddenInput.value) currentVals = [hiddenInput.value];
                }

                options.forEach(opt => {
                    const val = opt.getAttribute('data-value');
                    if (currentVals.includes(val)) {
                        opt.classList.add('selected');
                    } else {
                        opt.classList.remove('selected');
                    }
                });
                
                const selectedOpts = options.filter(o => o.classList.contains('selected'));
                const texts = selectedOpts.map(o => o.textContent.trim());
                if (texts.length > 0) {
                    triggerText.textContent = texts.length > 2 
                        ? texts.slice(0, 2).join(', ') + '... (+' + (texts.length - 2) + ')'
                        : texts.join(', ');
                    triggerText.classList.remove('text-gray-400');
                    triggerText.classList.add('text-gray-900');
                } else {
                    const placeholder = trigger.getAttribute('data-placeholder') || 'Pilih...';
                    triggerText.textContent = placeholder;
                    triggerText.classList.add('text-gray-400');
                    triggerText.classList.remove('text-gray-900');
                }
            } catch(e) {
                console.error("MultiSelect Sync Error:", e);
            }
        }
    </script>
</head>
<body class="bg-[#f8fafc] flex h-screen overflow-hidden">
