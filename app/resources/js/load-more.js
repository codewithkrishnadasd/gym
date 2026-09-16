/**
 * Marks a Livewire component while a "load more" request (LoadsMore) is in
 * flight. The list overlay (components/ui/list-loader.blade.php) hides
 * itself under that mark, so fetching the next set of rows never dims or
 * blocks the rows already shown — a filter change still does.
 */
document.addEventListener('livewire:init', () => {
    window.Livewire.hook('commit', ({ component, commit, respond }) => {
        const onlyLoadingMore = commit.calls.length > 0 && commit.calls.every((call) => call.method === 'loadMore');

        if (!onlyLoadingMore) {
            return;
        }

        component.el.setAttribute('data-loading-more', '');

        respond(() => component.el.removeAttribute('data-loading-more'));
    });
});
