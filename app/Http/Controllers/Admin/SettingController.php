<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAppearanceRequest;
use App\Models\Setting;
use App\Services\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(private SettingService $settingService)
    {
    }

    /**
     * Halaman pengaturan tampilan: warna header/footer/tombol + logo + favicon.
     */
    public function appearance(): View
    {
        $general = Setting::group('general');
        $appearance = Setting::group('appearance');

        return view('admin.settings.appearance', compact('general', 'appearance'));
    }

    public function updateAppearance(UpdateAppearanceRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // File upload diambil terpisah karena tidak lolos $request->validated() sebagai UploadedFile murni
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo');
        }
        if ($request->hasFile('favicon')) {
            $data['favicon'] = $request->file('favicon');
        }

        $this->settingService->saveAppearance($data);

        return redirect()
            ->route('admin.settings.appearance')
            ->with('success', 'Pengaturan tampilan berhasil disimpan.');
    }
}
