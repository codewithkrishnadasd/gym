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

/**
 * Copies text to the clipboard, returning whether it worked.
 *
 * `navigator.clipboard` only exists in a secure context — HTTPS or localhost.
 * Reached over plain HTTP (a LAN address, an IP, a domain before its
 * certificate is in place) the property is simply undefined, and calling it
 * throws. The old `document.execCommand` path still works there, so it is used
 * as the fallback rather than leaving the button dead.
 *
 * Never throws: callers use the boolean to offer manual copying instead.
 */
window.copyToClipboard = async function copyToClipboard(text) {
    if (typeof text !== 'string' || text === '') {
        return false;
    }

    if (window.isSecureContext && navigator.clipboard?.writeText) {
        try {
            await navigator.clipboard.writeText(text);

            return true;
        } catch {
            // Permission denied, or the document was not focused. Fall through
            // to the legacy path rather than giving up.
        }
    }

    const area = document.createElement('textarea');

    area.value = text;
    area.setAttribute('readonly', '');
    // Off-screen rather than hidden: a display:none element cannot be selected,
    // and scrolling the page under the user would be worse than either.
    area.style.cssText = 'position:fixed;top:0;left:-9999px;opacity:0';

    document.body.appendChild(area);
    area.select();
    area.setSelectionRange(0, area.value.length);

    let copied = false;

    try {
        copied = document.execCommand('copy');
    } catch {
        copied = false;
    }

    area.remove();

    return copied;
};

/**
 * Routes every `data-confirm` control through the styled confirmation dialog
 * instead of window.confirm().
 *
 * Intercepting in the capture phase is what makes this work with Livewire and
 * Alpine alike: both bind their handlers in the bubble phase, so stopping the
 * event here means neither has run yet. On confirmation the original click is
 * replayed with a bypass flag, and whatever would normally have happened —
 * a wire:click, a form submit, a link — happens then.
 *
 * Attributes:
 *   data-confirm         the question (required)
 *   data-confirm-title   heading, default "Are you sure?"
 *   data-confirm-action  confirm button label, default "Continue"
 *   data-confirm-tone    "danger" (default) or "accent"
 */
document.addEventListener(
    'click',
    (event) => {
        const trigger = event.target.closest?.('[data-confirm]');

        if (!trigger || trigger.dataset.confirmed === 'true') {
            return;
        }

        // Without a dialog on the page, swallowing the click would turn every
        // confirmable action into a silent no-op. Let it through instead.
        if (!document.querySelector('[data-confirm-dialog]')) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();

        window.dispatchEvent(
            new CustomEvent('confirm-request', {
                detail: {
                    message: trigger.dataset.confirm,
                    title: trigger.dataset.confirmTitle || 'Are you sure?',
                    action: trigger.dataset.confirmAction || 'Continue',
                    tone: trigger.dataset.confirmTone || 'danger',
                    accept() {
                        trigger.dataset.confirmed = 'true';
                        trigger.click();
                        delete trigger.dataset.confirmed;
                    },
                },
            }),
        );
    },
    true,
);

/**
 * Registers the service worker, which is the last thing Chrome requires before
 * it will offer to install the site as an app.
 *
 * The worker itself caches nothing — see BrandingController::serviceWorker().
 * Registration is skipped on an insecure origin, where the API does not exist,
 * and on the platform console, which has no manifest to install.
 */
if ('serviceWorker' in navigator && window.isSecureContext && document.querySelector('link[rel="manifest"]')) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js', { scope: '/' }).catch(() => {
            // An unavailable worker costs the install button and nothing else,
            // so a failure here must never surface to the operator.
        });
    });
}
