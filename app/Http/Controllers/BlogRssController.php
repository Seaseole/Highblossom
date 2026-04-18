<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domains\Content\Models\Post;
use Illuminate\Http\Response;

final class BlogRssController extends Controller
{
    public function __invoke(): Response
    {
        $posts = Post::published()
            ->latest('published_at')
            ->limit(20)
            ->get();

        $xml = view('blog.rss', ['posts' => $posts])->render();

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}
