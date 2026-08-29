<?php

use App\Models\BlogCategory;
use App\Models\Post;

it('guards blog admin from guests', function () {
    $this->get('/panel/blog')->assertRedirect('/panel/giris');
});

it('creates a post with auto slug as draft', function () {
    $cat = BlogCategory::factory()->create();

    actingPanel()->post('/panel/blog', [
        'title' => 'Kaskoda Bilinmesi Gerekenler',
        'blog_category_id' => $cat->id,
        'excerpt' => 'Kısa özet.',
        'body' => '# Merhaba',
        'status' => 'taslak',
    ])->assertRedirect();

    $post = Post::first();
    expect($post->slug)->toBe('kaskoda-bilinmesi-gerekenler')
        ->and($post->status)->toBe('taslak')
        ->and($post->published_at)->toBeNull();
});

it('publishes a post so it appears on the public blog', function () {
    $post = Post::factory()->draft()->create(['title' => 'Yayına alınacak']);

    actingPanel()->put("/panel/blog/{$post->id}", [
        'title' => $post->title,
        'excerpt' => $post->excerpt,
        'body' => $post->body,
        'status' => 'yayinda',
    ])->assertRedirect();

    $post->refresh();
    expect($post->status)->toBe('yayinda')->and($post->published_at)->not->toBeNull();

    $this->get('/blog')->assertSee('Yayına alınacak');
});

it('lets staff manage blog categories', function () {
    actingPanel()->post('/panel/blog-kategorileri', ['name' => 'İpuçları'])->assertRedirect();

    expect(BlogCategory::where('name', 'İpuçları')->where('slug', 'ipuclari')->exists())->toBeTrue();
});
