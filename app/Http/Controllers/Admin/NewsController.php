<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreNewsRequest;
use App\Http\Requests\Admin\UpdateNewsRequest;
use App\Models\Category;
use App\Models\News;
use App\Models\Tag;
use App\Services\NewsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function __construct(private NewsService $newsService)
    {
    }

    public function index(Request $request): View
    {
        $query = News::with(['user', 'category'])->latest();

        // Author cuma lihat berita miliknya sendiri
        if ($request->user()->role === 'author') {
            $query->where('user_id', $request->user()->id);
        }

        if ($search = $request->query('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $news = $query->paginate(15)->withQueryString();

        return view('admin.news.index', compact('news'));
    }

    public function create(): View
    {
        $categories = Category::active()->orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('admin.news.create', compact('categories', 'tags'));
    }

        public function store(StoreNewsRequest $request): RedirectResponse
        {
            $this->newsService->create($request->validated(), $request->user()->id);

            return redirect()
                ->route('admin.news.index')
                ->with('success', 'Berita berhasil dibuat.');
        }

    public function edit(News $news): View
    {
        $this->authorize('update', $news);

        $categories = Category::active()->orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        $news->load('tags');

        return view('admin.news.edit', compact('news', 'categories', 'tags'));
    }

    public function update(UpdateNewsRequest $request, News $news): RedirectResponse
    {
        $this->newsService->update($news, $request->validated());

        return redirect()
            ->route('admin.news.edit', $news)
            ->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(News $news): RedirectResponse
    {
        $this->authorize('delete', $news);

        $this->newsService->delete($news);

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Berita berhasil dihapus.');
    }
}
