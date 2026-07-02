<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\StoreCommentRequest;
use App\Models\Comment;
use App\Models\News;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    /**
     * Listing semua berita publik (dengan filter tag opsional).
     */
    public function index(Request $request): View
    {
        $query = News::published()->with(['category', 'user']);

        if ($tag = $request->query('tag')) {
            $query->whereHas('tags', fn ($q) => $q->where('slug', $tag));
        }

        $news = $query->latest('published_at')->paginate(12)->withQueryString();

        return view('front.news.index', compact('news'));
    }

    /**
     * Halaman detail satu berita. Route model binding pakai slug
     * (lihat News::getRouteKeyName()).
     */
    public function show(News $news): View
    {
        abort_unless(
            $news->status === 'published' && $news->published_at?->isPast(),
            404
        );

        $news->incrementViews();

        $news->load([
            'category', 'user', 'tags',
            'approvedComments' => fn ($q) => $q->rootOnly()->with('replies.user')->latest(),
        ]);

        $related = News::published()
            ->where('category_id', $news->category_id)
            ->where('id', '!=', $news->id)
            ->latest('published_at')
            ->take(4)
            ->get();

        return view('front.news.show', compact('news', 'related'));
    }

    public function storeComment(StoreCommentRequest $request, News $news): RedirectResponse
    {
        Comment::create([
            'news_id'   => $news->id,
            'user_id'   => $request->user()?->id,
            'parent_id' => $request->validated('parent_id'),
            'name'      => $request->user()?->name ?? $request->validated('name'),
            'email'     => $request->user()?->email ?? $request->validated('email'),
            'content'   => $request->validated('content'),
            // Guest comment butuh moderasi, komentar user login bisa auto-approve
            'status'    => $request->user() ? 'approved' : 'pending',
        ]);

        return back()->with('success', 'Komentar berhasil dikirim'
            . ($request->user() ? '.' : ', menunggu moderasi admin.'));
    }
}
