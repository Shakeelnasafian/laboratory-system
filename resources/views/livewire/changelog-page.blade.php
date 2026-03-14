<div class="space-y-6">
    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl space-y-3">
                <span class="inline-flex w-fit rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-blue-700">
                    Git Timeline
                </span>
                <div class="space-y-2">
                    <h2 class="text-2xl font-semibold tracking-tight text-slate-900 sm:text-3xl">Change log from the first commit to the current workspace</h2>
                    <p class="text-sm leading-6 text-slate-600 sm:text-base">
                        This page is organized from the actual repository history first, then the current uncommitted work in the local workspace.
                        Each milestone shows the commit reference or working-tree status and the features introduced at that stage.
                    </p>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                <p class="font-medium text-slate-900">Last updated</p>
                <p>{{ $lastUpdated }}</p>
            </div>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Committed milestones</p>
            <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $commitCount }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Workspace rollups</p>
            <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $workspaceChanges }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Current state</p>
            <p class="mt-2 text-3xl font-semibold text-slate-900">History + current diff</p>
        </div>
    </section>

    <section class="space-y-4">
        @foreach($entries as $index => $entry)
            <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <div class="flex flex-col gap-5 lg:flex-row lg:gap-8">
                    <div class="lg:w-56 lg:shrink-0">
                        <div class="flex items-start gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl text-sm font-semibold text-white {{ $entry['type'] === 'commit' ? 'bg-slate-900' : 'bg-blue-600' }}">
                                {{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">
                                    {{ $entry['type'] === 'commit' ? 'Commit' : 'Workspace' }}
                                </p>
                                <h3 class="text-lg font-semibold text-slate-900">{{ $entry['title'] }}</h3>
                                <div class="mt-2 flex flex-wrap items-center gap-2 text-xs">
                                    <span class="rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 font-semibold uppercase tracking-wide text-slate-600">
                                        {{ $entry['reference'] }}
                                    </span>
                                    <span class="text-slate-500">{{ $entry['date'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="min-w-0 flex-1 space-y-4">
                        <p class="max-w-3xl text-sm leading-6 text-slate-600 sm:text-base">{{ $entry['summary'] }}</p>

                        <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                            @foreach($entry['items'] as $item)
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm leading-6 text-slate-700">
                                    {{ $item }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </article>
        @endforeach
    </section>
</div>
