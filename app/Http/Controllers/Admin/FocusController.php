<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFocusRequest;
use App\Models\Focus;
use App\Models\News;
use App\Services\FocusService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FocusController extends Controller
{
    public function __construct(private FocusService $focusService)
    {
    }

    public function index(): View
    {
        $focuses = Focus::withCount('news')->latest()->paginate(15);

        return view('admin.focuses.index', compact('focuses'));
    }

    public function create(): View
    {
        $newsList = News::published()->latest('published_at')->take(100)->get();

        return view('admin.focuses.create', compact('newsList'));
    }

    public function store(StoreFocusRequest $request): RedirectResponse
    {
        $this->focusService->create($request->validated());

        return redirect()
            ->route('admin.focuses.index')
            ->with('success', 'Fokus berhasil dibuat.');
    }

    public function edit(Focus $focus): View
    {
        $newsList = News::published()->latest('published_at')->take(100)->get();
        $focus->load('news');

        return view('admin.focuses.edit', compact('focus', 'newsList'));
    }

    public function update(StoreFocusRequest $request, Focus $focus): RedirectResponse
    {
        $this->focusService->update($focus, $request->validated());

        return redirect()
            ->route('admin.focuses.index')
            ->with('success', 'Fokus berhasil diperbarui.');
    }

    public function destroy(Focus $focus): RedirectResponse
    {
        $this->focusService->delete($focus);

        return redirect()
            ->route('admin.focuses.index')
            ->with('success', 'Fokus berhasil dihapus.');
    }
}