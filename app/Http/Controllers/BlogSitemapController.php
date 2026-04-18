<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domains\Content\Models\Category;
use App\Domains\Content\Models\Post;
use App\Domains\Content\Models\Tag;
use Illuminate\Http\Response;

final class BlogSitemapController extends Controller
{
    public function __invoke(): Response
    {
        $posts = Post::published()
            ->select('slug', 'updated_at', 'published_at')
            ->latest('published_at')
            ->get();

        $categories = Category::forPosts()
            ->select('slug', 'updated_at')
            ->get();

        $tags = Tag::select('slug', 'updated_at')
            ->whereHas('posts', fn ($q) => $q->published())
            ->get();

        $xml = view('blog.sitemap', [
            'posts' => $posts,
            'categories' => $categories,
            'tags' => $tags,
        ])->render();

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}
