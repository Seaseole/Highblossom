<?php

declare(strict_types=1);

namespace App\Domains\Content\Actions\Posts;

use App\Domains\Content\Models\Post;

final class CalculateReadingTime
{
    /**
     * Average reading speed in words per minute.
     */
    private const WORDS_PER_MINUTE = 200;

    public function execute(Post $post): int
    {
        $wordCount = $this->countWords($post);
        $minutes = (int) ceil($wordCount / self::WORDS_PER_MINUTE);

        return max(1, $minutes); // Minimum 1 minute
    }

    private function countWords(Post $post): int
    {
        $text = $post->title . ' ' . $post->excerpt;

        // Add words from all content blocks
        foreach ($post->contentBlocks as $block) {
            $content = $block->content;

            if (is_array($content)) {
                // Extract text from common content fields
                if (isset($content['content'])) {
                    $text .= ' ' . strip_tags($content['content']);
                }
                if (isset($content['heading'])) {
                    $text .= ' ' . $content['heading'];
                }
                if (isset($content['subheading'])) {
                    $text .= ' ' . $content['subheading'];
                }
                if (isset($content['quote'])) {
                    $text .= ' ' . strip_tags($content['quote']);
                }
            }
        }

        return str_word_count(strip_tags($text));
    }
}
