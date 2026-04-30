<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'description', 'thumbnail_url', 'content', 'status', 'views'];
    protected $casts = ['views' => 'integer'];
    protected $appends = ['image'];

    protected static function booted()
    {
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
        static::updating(function ($post) {
            if ($post->isDirty('title')) {
                $post->slug = Str::slug($post->title);
            }

            if ($post->isDirty('content')) {
                $oldContent = (string) $post->getOriginal('content');
                $newContent = (string) $post->content;

                static::deleteStorageFiles(
                    array_diff(
                        static::extractStoragePathsFromContent($oldContent),
                        static::extractStoragePathsFromContent($newContent)
                    )
                );
            }

            if ($post->isDirty('thumbnail_url')) {
                $oldThumbnail = $post->getOriginal('thumbnail_url');

                if ($oldThumbnail && $oldThumbnail !== $post->thumbnail_url) {
                    Storage::disk('public')->delete($oldThumbnail);
                }
            }
        });

        static::deleting(function ($post) {
            if ($post->thumbnail_url) {
                Storage::disk('public')->delete($post->thumbnail_url);
            }

            static::deleteStorageFiles(
                static::extractStoragePathsFromContent((string) $post->content)
            );
        });
    }

    public function getImageAttribute(): ?string
    {
        if (!$this->thumbnail_url) {
            return null;
        }

        return asset('storage/' . ltrim($this->thumbnail_url, '/'));
    }

    public static function extractStoragePathsFromContent(string $content): array
    {
        if (trim($content) === '') {
            return [];
        }

        $paths = [];

        libxml_use_internal_errors(true);

        $dom = new \DOMDocument();
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        foreach ($dom->getElementsByTagName('img') as $image) {
            $src = trim((string) $image->getAttribute('src'));

            if ($src === '') {
                continue;
            }

            $path = parse_url($src, PHP_URL_PATH) ?: $src;
            $path = ltrim($path, '/');

            if (Str::startsWith($path, 'storage/posts/editor/')) {
                $paths[] = Str::after($path, 'storage/');
            }
        }

        libxml_clear_errors();

        return array_values(array_unique($paths));
    }

    protected static function deleteStorageFiles(array $paths): void
    {
        if (empty($paths)) {
            return;
        }

        Storage::disk('public')->delete($paths);
    }
}
