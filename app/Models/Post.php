<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_category_id',
        'user_id',
        'title',
        'slug',
        'excerpt',
        'body',
        'cover_path',
        'status',
        'published_at',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'yayinda')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /** Ham HTML strip'lenmiş, güvenli Markdown -> HTML. */
    public function renderedBody(): string
    {
        return Str::markdown($this->body ?? '', [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }

    public function metaTitle(): string
    {
        return $this->meta_title ?: $this->title;
    }

    public function metaDescription(): string
    {
        return $this->meta_description ?: Str::limit(strip_tags($this->excerpt ?: $this->body), 155);
    }

    public function coverUrl(): ?string
    {
        if (! $this->cover_path) {
            return null;
        }

        // public/ altındaki hazır görseller (img/...) doğrudan; panelden yüklenenler storage'dan.
        return str_starts_with($this->cover_path, 'img/')
            ? asset($this->cover_path)
            : asset('storage/'.$this->cover_path);
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'yazi';
        $slug = $base;
        $i = 2;

        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
