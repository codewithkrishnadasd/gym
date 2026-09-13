import Chart from 'chart.js/auto';

/**
 * Charts are declared entirely in Blade: a <canvas data-chart="{...}"> holds
 * its own spec, and this module renders it. That keeps the server as the
 * single source of chart data and avoids a separate JSON API layer.
 */

const CHARTS = new Map();

/** Resolves the current theme's design tokens so charts match the UI. */
function tokens() {
    const style = getComputedStyle(document.documentElement);
    const read = (name) => style.getPropertyValue(name).trim();

    return {
        ink: read('--c-ink'),
        inkSoft: read('--c-ink-soft'),
        inkMuted: read('--c-ink-muted'),
        hairline: read('--c-hairline'),
        surface: read('--c-surface'),
        accent: read('--c-accent'),
        positive: read('--c-positive'),
        caution: read('--c-caution'),
        critical: read('--c-critical'),
        info: read('--c-info'),
    };
}

/** Converts a #rrggbb token into rgba() so fills can be translucent. */
function alpha(hex, amount) {
    const value = hex.replace('#', '');

    if (value.length !== 6) {
        return hex;
    }

    const [r, g, b] = [0, 2, 4].map((i) => parseInt(value.slice(i, i + 2), 16));

    return `rgba(${r}, ${g}, ${b}, ${amount})`;
}

function paletteFor(name, theme) {
    return theme[name] ?? theme.accent;
}

function buildConfig(spec, theme) {
    const isBar = spec.type === 'bar';
    const isCircular = spec.type === 'doughnut' || spec.type === 'pie';

    const datasets = (spec.datasets ?? []).map((dataset) => {
        const colours = Array.isArray(dataset.color)
            ? dataset.color.map((name) => paletteFor(name, theme))
            : paletteFor(dataset.color, theme);

        if (isCircular) {
            return {
                label: dataset.label,
                data: dataset.data,
                backgroundColor: colours,
                borderColor: theme.surface,
                borderWidth: 2,
                hoverOffset: 6,
            };
        }

        return {
            label: dataset.label,
            data: dataset.data,
            borderColor: colours,
            backgroundColor: isBar ? alpha(colours, 0.85) : alpha(colours, 0.14),
            borderWidth: 2,
            borderRadius: isBar ? 4 : 0,
            maxBarThickness: 36,
            fill: !isBar && dataset.fill !== false,
            tension: 0.35,
            pointRadius: 0,
            pointHoverRadius: 4,
            pointBackgroundColor: colours,
        };
    });

    const format = (value) => {
        if (spec.valueFormat === 'currency') {
            return `${spec.currencySymbol ?? ''}${Number(value).toLocaleString()}`;
        }

        if (spec.valueFormat === 'percent') {
            return `${Number(value).toLocaleString()}%`;
        }

        return Number(value).toLocaleString();
    };

    return {
        type: spec.type ?? 'line',
        data: { labels: spec.labels ?? [], datasets },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    display: spec.legend ?? datasets.length > 1,
                    position: isCircular ? 'right' : 'top',
                    align: 'end',
                    labels: {
                        boxWidth: 8,
                        boxHeight: 8,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        color: theme.inkSoft,
                        font: { size: 11 },
                    },
                },
                tooltip: {
                    backgroundColor: theme.ink,
                    titleColor: theme.surface,
                    bodyColor: theme.surface,
                    padding: 10,
                    cornerRadius: 6,
                    displayColors: true,
                    boxWidth: 8,
                    boxHeight: 8,
                    usePointStyle: true,
                    callbacks: {
                        label: (context) => {
                            const value = isCircular ? context.parsed : context.parsed.y;

                            return ` ${context.dataset.label ?? context.label}: ${format(value)}`;
                        },
                    },
                },
            },
            scales: isCircular
                ? {}
                : {
                      x: {
                          grid: { display: false },
                          border: { color: theme.hairline },
                          ticks: { color: theme.inkMuted, font: { size: 11 }, maxRotation: 0, autoSkipPadding: 16 },
                          stacked: spec.stacked ?? false,
                      },
                      y: {
                          beginAtZero: true,
                          grid: { color: theme.hairline },
                          border: { display: false },
                          ticks: {
                              color: theme.inkMuted,
                              font: { size: 11 },
                              maxTicksLimit: 5,
                              callback: (value) => format(value),
                          },
                          stacked: spec.stacked ?? false,
                      },
                  },
        },
    };
}

function render(canvas) {
    const raw = canvas.dataset.chart;

    if (!raw) {
        return;
    }

    let spec;

    try {
        spec = JSON.parse(raw);
    } catch {
        return;
    }

    CHARTS.get(canvas)?.destroy();
    CHARTS.set(canvas, new Chart(canvas, buildConfig(spec, tokens())));
}

function renderAll(root = document) {
    root.querySelectorAll('canvas[data-chart]').forEach(render);
}

function destroyDetached() {
    CHARTS.forEach((chart, canvas) => {
        if (!canvas.isConnected) {
            chart.destroy();
            CHARTS.delete(canvas);
        }
    });
}

document.addEventListener('DOMContentLoaded', () => renderAll());

// Livewire replaces DOM in place, so charts are re-rendered after each update
// and any canvas that disappeared has its Chart instance released.
document.addEventListener('livewire:navigated', () => renderAll());
document.addEventListener('livewire:update', () => {
    destroyDetached();
    renderAll();
});

// Repaint every chart when the user flips the light/dark toggle.
new MutationObserver(() => renderAll()).observe(document.documentElement, {
    attributes: true,
    attributeFilter: ['data-theme'],
});
