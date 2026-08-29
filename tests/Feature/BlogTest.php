<?php

use App\Models\BlogCategory;
use App\Models\Post;

it('lists only published posts, newest first', function () {
    $eski = Post::factory()->published()->create(['title' => 'Eski yazı', 'published_at' => now()->subDays(5)]);
    $yeni = Post::factory()->published()->create(['title' => 'Yeni yazı', 'published_at' => now()->subDay()]);
    Post::factory()->draft()->create(['title' => 'Taslak yazı']);

    $this->get('/blog')
        ->assertOk()
        ->assertSeeInOrder(['Yeni yazı', 'Eski yazı'])
        ->assertDontSee('Taslak yazı');
});

it('shows a published post by slug with SEO meta', function () {
    $post = Post::factory()->published()->create([
        'title' => 'Trafik sigortası rehberi',
        'body' => "## Başlık\n\nBu **kalın** bir metin.",
        'meta_description' => 'Trafik sigortası hakkında bilmeniz gerekenler.',
    ]);

    $this->get("/blog/{$post->slug}")
        ->assertOk()
        ->assertSee('Trafik sigortası rehberi')
        ->assertSee('<strong>kalın</strong>', false)
        ->assertSee('Trafik sigortası hakkında bilmeniz gerekenler.', false);
});

it('404s on a draft post or unknown slug', function () {
    $draft = Post::factory()->draft()->create();

    $this->get("/blog/{$draft->slug}")->assertNotFound();
    $this->get('/blog/olmayan-yazi')->assertNotFound();
});

it('filters posts by category slug', function () {
    $trafik = BlogCategory::factory()->create(['name' => 'Trafik', 'slug' => 'trafik']);
    $kasko = BlogCategory::factory()->create(['name' => 'Kasko', 'slug' => 'kasko']);
    Post::factory()->published()->for($trafik, 'category')->create(['title' => 'Trafik yazısı']);
    Post::factory()->published()->for($kasko, 'category')->create(['title' => 'Kasko yazısı']);

    $this->get('/blog?kategori=trafik')
        ->assertOk()
        ->assertSee('Trafik yazısı')
        ->assertDontSee('Kasko yazısı');
});

it('strips raw html from markdown body (no XSS)', function () {
    $post = Post::factory()->published()->create([
        'body' => "Merhaba <script>alert('xss')</script> dünya",
    ]);

    $this->get("/blog/{$post->slug}")
        ->assertOk()
        ->assertDontSee("<script>alert('xss')</script>", false);
});
