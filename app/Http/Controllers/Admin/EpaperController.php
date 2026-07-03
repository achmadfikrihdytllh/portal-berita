<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEpaperRequest;
use App\Models\Epaper;
use App\Services\EpaperService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EpaperController extends Controller
{
    public function __construct(private EpaperService $epaperService)
    {
    }

    public function index(): View
    {
        $epapers = Epaper::latest('edition_date')->paginate(15);

        return view('admin.epapers.index', compact('epapers'));
    }

    public function create(): View
    {
        return view('admin.epapers.create');
    }

    public function store(StoreEpaperRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['cover_image'] = $request->file('cover_image');
        $data['file_path'] = $request->file('file_path');

        $this->epaperService->create($data);

        return redirect()
            ->route('admin.epapers.index')
            ->with('success', 'E-koran berhasil ditambahkan.');
    }

    public function edit(Epaper $epaper): View
    {
        return view('admin.epapers.edit', compact('epaper'));
    }

    public function update(StoreEpaperRequest $request, Epaper $epaper): RedirectResponse
    {
        $data = $request->validated();
        $data['cover_image'] = $request->file('cover_image');
        $data['file_path'] = $request->file('file_path');

        $this->epaperService->update($epaper, $data);

        return redirect()
            ->route('admin.epapers.index')
            ->with('success', 'E-koran berhasil diperbarui.');
    }

    public function destroy(Epaper $epaper): RedirectResponse
    {
        $this->epaperService->delete($epaper);

        return redirect()
            ->route('admin.epapers.index')
            ->with('success', 'E-koran berhasil dihapus.');
    }
}