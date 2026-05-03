/**
 * Frontrow Backend — app.js
 * All interactive behaviour in one file. No inline scripts in Blade templates.
 */
document.addEventListener('DOMContentLoaded', function () {

    /* ──────────────────────────────────────────────────────────────────
       1. SIDEBAR TOGGLE
    ────────────────────────────────────────────────────────────────── */
    const sidebar = document.getElementById('frSidebar');
    const toggle  = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('frOverlay');

    if (sidebar && toggle && overlay) {
        const isMobile = () => window.innerWidth < 992;

        // Restore desktop preference
        if (!isMobile() && localStorage.getItem('fr_sidebar') === 'collapsed') {
            sidebar.classList.add('collapsed');
        }

        toggle.addEventListener('click', () => {
            if (isMobile()) {
                sidebar.classList.toggle('mobile-open');
                overlay.classList.toggle('active');
            } else {
                sidebar.classList.toggle('collapsed');
                localStorage.setItem(
                    'fr_sidebar',
                    sidebar.classList.contains('collapsed') ? 'collapsed' : 'expanded'
                );
            }
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('active');
        });

        window.addEventListener('resize', () => {
            if (!isMobile()) {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
            }
        });
    }

    /* ──────────────────────────────────────────────────────────────────
       2. SETTINGS TABS
    ────────────────────────────────────────────────────────────────── */
    document.querySelectorAll('.settings-tab-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const tab = this.dataset.tab;
            document.querySelectorAll('.settings-tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.settings-panel').forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            const panel = document.getElementById('tab-' + tab);
            if (panel) panel.classList.add('active');
        });
    });

    /* ──────────────────────────────────────────────────────────────────
       3. COPY TO CLIPBOARD
       Usage: <button class="copy-btn" data-copy="text to copy">
    ────────────────────────────────────────────────────────────────── */
    document.querySelectorAll('.copy-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const text = this.dataset.copy;
            if (!text || !navigator.clipboard) return;

            navigator.clipboard.writeText(text).then(() => {
                const original = this.innerHTML;
                this.classList.add('copied');
                this.innerHTML = '<i class="fas fa-check"></i>';
                setTimeout(() => {
                    this.classList.remove('copied');
                    this.innerHTML = original;
                }, 1800);
            });
        });
    });

    /* ──────────────────────────────────────────────────────────────────
       4. CONTEXT JSON TOGGLE
       Usage: <button class="context-toggle" data-target="#ctx-ID">
              <div class="context-json" id="ctx-ID">...</div>
    ────────────────────────────────────────────────────────────────── */
    document.querySelectorAll('.context-toggle').forEach(btn => {
        btn.addEventListener('click', function () {
            const targetId = this.dataset.target;
            const json     = targetId
                ? document.querySelector(targetId)
                : this.nextElementSibling;

            if (!json) return;
            const open = json.classList.toggle('open');

            // Update chevron direction
            const icon = this.querySelector('i');
            if (icon) {
                icon.className = open ? 'fas fa-chevron-up' : 'fas fa-chevron-down';
            }
        });
    });

    /* ──────────────────────────────────────────────────────────────────
       5. LIVE TABLE FILTER
       Usage: <input data-filter-table="#tableId" data-filter-cols="1,2,3">
       Filters visible rows in a table as the user types.
       Columns are 0-indexed.
    ────────────────────────────────────────────────────────────────── */
    document.querySelectorAll('[data-filter-table]').forEach(input => {
        const tableId = input.dataset.filterTable;
        const cols    = (input.dataset.filterCols || '').split(',').map(Number).filter(n => !isNaN(n));
        const table   = document.querySelector(tableId);
        if (!table) return;

        let debounceTimer;

        input.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const query = this.value.trim().toLowerCase();
                const rows  = table.querySelectorAll('tbody tr:not(.context-row)');

                rows.forEach(row => {
                    if (!query) {
                        row.classList.remove('fr-hidden');
                        // Show associated context row if open
                        const next = row.nextElementSibling;
                        if (next && next.classList.contains('context-row')) {
                            next.classList.remove('fr-hidden');
                        }
                        return;
                    }

                    const cells  = row.querySelectorAll('td');
                    const search = cols.length
                        ? cols.map(i => cells[i]?.textContent || '').join(' ')
                        : row.textContent;

                    const match = search.toLowerCase().includes(query);
                    row.classList.toggle('fr-hidden', !match);

                    // Keep context rows in sync
                    const next = row.nextElementSibling;
                    if (next && next.classList.contains('context-row')) {
                        next.classList.toggle('fr-hidden', !match);
                    }
                });

                // Update "no results" state
                const visible = table.querySelectorAll('tbody tr:not(.context-row):not(.fr-hidden)');
                let emptyRow  = table.querySelector('.fr-empty-row');
                if (visible.length === 0) {
                    if (!emptyRow) {
                        emptyRow = document.createElement('tr');
                        emptyRow.className = 'fr-empty-row';
                        const colCount = table.querySelectorAll('thead th').length;
                        emptyRow.innerHTML = `<td colspan="${colCount}" style="text-align:center;padding:2.5rem;color:var(--text-muted);">No events match your filter.</td>`;
                        table.querySelector('tbody').appendChild(emptyRow);
                    }
                } else {
                    if (emptyRow) emptyRow.remove();
                }
            }, 180); // 180ms debounce
        });
    });

    /* ──────────────────────────────────────────────────────────────────
       6. SELECT AUTO-SUBMIT (filter dropdowns)
       Usage: <select data-auto-submit> inside a <form>
    ────────────────────────────────────────────────────────────────── */
    document.querySelectorAll('select[data-auto-submit]').forEach(sel => {
        sel.addEventListener('change', function () {
            const form = this.closest('form');
            if (form) form.submit();
        });
    });

    /* ──────────────────────────────────────────────────────────────────
       7. AUTH HEALTH CHART (security dashboard)
       Reads data from data-* attributes on the canvas element.
    ────────────────────────────────────────────────────────────────── */
    const chartCanvas = document.getElementById('authHealthChart');
    if (chartCanvas && typeof Chart !== 'undefined') {
        let chartData;
        try {
            chartData = JSON.parse(chartCanvas.dataset.chartJson);
        } catch (e) {
            console.error('authHealthChart: invalid JSON in data-chart-json', e);
        }

        if (chartData) {
            new Chart(chartCanvas, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: 'Successful',
                            data: chartData.successful,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16,185,129,.08)',
                            borderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            fill: true,
                            tension: .4,
                        },
                        {
                            label: 'Failed',
                            data: chartData.failed,
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239,68,68,.08)',
                            borderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            fill: true,
                            tension: .4,
                        },
                        {
                            label: 'Violations',
                            data: chartData.violations,
                            borderColor: '#f59e0b',
                            backgroundColor: 'rgba(245,158,11,.05)',
                            borderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            fill: true,
                            tension: .4,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleColor: '#94a3b8',
                            bodyColor: '#e2e8f0',
                            borderColor: '#1e293b',
                            borderWidth: 1,
                            padding: 10,
                            cornerRadius: 6,
                        },
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11, family: 'DM Sans' }, color: '#94a3b8' },
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: { font: { size: 11, family: 'DM Sans' }, color: '#94a3b8', stepSize: 1 },
                        },
                    },
                },
            });
        }
    }

}); // end DOMContentLoaded