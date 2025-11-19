<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendCampaignJob;
use App\Models\Campaign;
use App\Models\CampaignRecipient;
use App\Models\NewsletterSubscriber;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        // Publicadas primero por fecha, luego las sin fecha
        $items = Post::orderByRaw('published_at IS NULL')   // false(0)=publicadas primero
            ->orderByDesc('published_at')
            ->latest('id')
            ->paginate(12);

        return view('admin.posts.index', compact('items'));
    }

    public function create()
    {
        $post = new Post([
            'published_at' => now(),
        ]);

        return view('admin.posts.form', compact('post'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'title'        => ['required', 'max:180'],
            'slug'         => ['nullable', 'max:180', 'unique:posts,slug'],
            'excerpt'      => ['nullable', 'string'],
            'body'         => ['nullable', 'string'],
            'cover'        => ['nullable', 'image'],
            'published_at' => ['nullable', 'date'],
        ]);

        // Slug auto si viene vacío
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);

        // Portada
        if ($r->hasFile('cover')) {
            $path = $r->file('cover')->store('posts', 'public'); // posts/xxx.jpg
            $data['cover_path'] = "/storage/{$path}";
        }

        $post = Post::create($data);

        /** ------- ENVÍO OPCIONAL DE BOLETÍN ------- */
        if ($r->boolean('send_to_newsletter')) {
            $title = $post->title;
            $url   = route('noticias.show', $post->slug);
            $cover = $post->cover_path ?: '/placeholder.webp';

            $html = view('emails.templates.content-publish', [
                'title'   => $title,
                'excerpt' => $post->excerpt,
                'url'     => $url,
                'cover'   => $cover,
            ])->render();

            $c = Campaign::create([
                'name'        => 'Auto · ' . $title,
                'subject'     => 'Nuevo: ' . $title,
                'preheader'   => 'Conoce la novedad de APME',
                'html'        => $html,
                'status'      => 'sending',
                'scheduled_at' => now(),
                'sent_count'  => 0,
                'error_count' => 0,
            ]);

            // Solo suscriptores confirmados
            $subs = NewsletterSubscriber::where('status', 'subscribed')->get(['id', 'email']);

            foreach ($subs as $s) {
                CampaignRecipient::updateOrCreate(
                    ['campaign_id' => $c->id, 'subscriber_id' => $s->id],
                    ['email' => $s->email, 'status' => 'queued', 'error' => null, 'sent_at' => null]
                );
            }

            dispatch(new SendCampaignJob($c->id));
        }
        /** ------------------------------------------ */

        return redirect()->route('admin.posts.edit', $post)->with('ok', 'Creado');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.form', compact('post'));
    }

    public function update(Request $r, Post $post)
    {
        $data = $r->validate([
            'title'        => ['required', 'max:180'],
            'slug'         => ['nullable', 'max:180', Rule::unique('posts', 'slug')->ignore($post->id)],
            'excerpt'      => ['nullable', 'string'],
            'body'         => ['nullable', 'string'],
            'cover'        => ['nullable', 'image'],
            'remove_cover' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        // Si te olvidas de enviar slug, lo regeneramos (no obligatorio, pero útil)
        $data['slug'] = $data['slug'] ?: $post->slug ?: Str::slug($data['title']);

        // Quitar portada si lo pides
        if ($r->boolean('remove_cover') && $post->cover_path) {
            $this->deleteCoverFile($post->cover_path);
            $data['cover_path'] = null;
        }

        // Reemplazar portada
        if ($r->hasFile('cover')) {
            // borrar anterior si existe
            if ($post->cover_path) {
                $this->deleteCoverFile($post->cover_path);
            }
            $path = $r->file('cover')->store('posts', 'public');
            $data['cover_path'] = "/storage/{$path}";
        }

        $post->update($data);

        return back()->with('ok', 'Guardado');
    }

    public function destroy(Post $post)
    {
        if ($post->cover_path) {
            $this->deleteCoverFile($post->cover_path);
        }
        $post->delete();
        return back()->with('ok', 'Eliminado');
    }

    private function deleteCoverFile(string $publicPath): void
    {
        // Convierte "/storage/archivo" -> "archivo" dentro del disk "public"
        $relative = ltrim(str_replace('/storage/', '', $publicPath), '/');
        if ($relative && Storage::disk('public')->exists($relative)) {
            Storage::disk('public')->delete($relative);
        }
    }
}
