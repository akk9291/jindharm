<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Saint;
use App\Models\Vihar;
use App\Models\ContentType;
use App\Models\Category;
use App\Models\Content;
use App\Models\Event;
use App\Models\Album;
use App\Models\Panchang;
use App\Models\Festival;
use App\Models\Page;
use App\Models\Setting;
use App\Models\HomepageSection;
use Illuminate\Support\Facades\App;

class ApiController extends Controller
{
    /**
     * Set locale from request.
     */
    protected function setLocale(Request $request): void
    {
        $lang = $request->query('lang', 'hi');
        if (in_array($lang, ['hi', 'en', 'sa'])) {
            App::setLocale($lang);
        }
    }

    /**
     * /api/home
     */
    public function home(Request $request): JsonResponse
    {
        $this->setLocale($request);

        $sections = HomepageSection::active()->get();
        $responseData = [];

        foreach ($sections as $section) {
            $key = $section->section_key;
            $data = [];

            switch ($key) {
                case 'hero_slider':
                    $data = $section->settings['slides'] ?? [];
                    break;
                case 'current_vihar':
                    $data = Vihar::current()->with('saint')->get()->map(fn($v) => [
                        'id' => $v->id,
                        'saint_name' => $v->saint->getLocalized('name'),
                        'location' => $v->getLocalized('location_title'),
                        'city' => $v->city,
                        'state' => $v->state,
                        'lat' => $v->latitude,
                        'lng' => $v->longitude,
                        'map_link' => $v->google_map_link
                    ]);
                    break;
                case 'saints':
                    $data = Saint::active()->featured()->get()->map(fn($s) => [
                        'id' => $s->id,
                        'name' => $s->getLocalized('name'),
                        'title' => $s->getLocalized('title'),
                        'photo' => $s->photo
                    ]);
                    break;
                case 'latest_news':
                    $newsType = ContentType::where('slug', 'news')->first();
                    if ($newsType) {
                        $data = Content::published()->where('content_type_id', $newsType->id)
                            ->latest('publish_date')->limit(5)->get()->map(fn($c) => [
                                'title' => $c->getLocalized('title'),
                                'slug' => $c->slug,
                                'publish_date' => $c->publish_date->toDateString(),
                                'image' => $c->featured_image
                            ]);
                    }
                    break;
                case 'panchang':
                    $data = Panchang::forDate(now()->toDateString());
                    break;
                case 'festivals':
                    $data = Festival::active()->where('festival_date', '>=', now()->toDateString())
                        ->orderBy('festival_date')->limit(3)->get()->map(fn($f) => [
                            'name' => $f->getLocalized('festival_name'),
                            'date' => $f->festival_date->toDateString(),
                            'image' => $f->image
                        ]);
                    break;
                case 'events':
                    $data = Event::active()->where('start_date', '>=', now())
                        ->orderBy('start_date')->limit(3)->get()->map(fn($e) => [
                            'name' => $e->getLocalized('event_name'),
                            'start_date' => $e->start_date->toIso8601String(),
                            'venue' => $e->getLocalized('venue'),
                            'banner' => $e->banner
                        ]);
                    break;
            }

            $responseData[$key] = [
                'label' => $section->getLocalized('label'),
                'data' => $data
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $responseData
        ]);
    }

    /**
     * /api/saints
     */
    public function saints(Request $request): JsonResponse
    {
        $this->setLocale($request);

        // Fetch top level saints (Guru Parampara root nodes)
        $saints = Saint::active()->whereNull('parent_id')->orderBy('display_order')->get();

        $formatSaint = function ($saint) use (&$formatSaint) {
            return [
                'id' => $saint->id,
                'name' => $saint->getLocalized('name'),
                'title' => $saint->getLocalized('title'),
                'photo' => $saint->photo,
                'introduction' => $saint->getLocalized('introduction'),
                'guru_name' => $saint->getLocalized('guru_name'),
                'diksha_date' => $saint->diksha_date ? $saint->diksha_date->toDateString() : null,
                'mobile' => $saint->mobile,
                'email' => $saint->email,
                'address' => $saint->getLocalized('address'),
                'disciples' => $saint->children->map(fn($child) => $formatSaint($child))
            ];
        };

        $tree = $saints->map(fn($s) => $formatSaint($s));

        return response()->json([
            'success' => true,
            'data' => $tree
        ]);
    }

    /**
     * /api/vihar
     */
    public function vihar(Request $request): JsonResponse
    {
        $this->setLocale($request);

        $vihars = Vihar::active()->with('saint')->orderBy('start_date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $vihars->map(fn($v) => [
                'id' => $v->id,
                'saint' => [
                    'id' => $v->saint->id,
                    'name' => $v->saint->getLocalized('name'),
                    'photo' => $v->saint->photo
                ],
                'location_title' => $v->getLocalized('location_title'),
                'address' => $v->getLocalized('address'),
                'city' => $v->city,
                'state' => $v->state,
                'latitude' => $v->latitude,
                'longitude' => $v->longitude,
                'google_map_link' => $v->google_map_link,
                'contact_person' => $v->contact_person,
                'contact_number' => $v->contact_number,
                'start_date' => $v->start_date->toDateString(),
                'end_date' => $v->end_date ? $v->end_date->toDateString() : null,
                'type' => $v->type
            ])
        ]);
    }

    /**
     * /api/content
     */
    public function content(Request $request): JsonResponse
    {
        $this->setLocale($request);

        $typeSlug = $request->query('type');
        $catSlug = $request->query('category');

        $query = Content::published()->with(['contentType', 'category']);

        if ($typeSlug) {
            $query->whereHas('contentType', fn($q) => $q->where('slug', $typeSlug));
        }

        if ($catSlug) {
            $query->whereHas('category', fn($q) => $q->where('slug', $catSlug));
        }

        $contents = $query->orderBy('display_order')->orderBy('publish_date', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $contents->map(fn($c) => [
                'title' => $c->getLocalized('title'),
                'slug' => $c->slug,
                'author' => $c->getLocalized('author'),
                'publish_date' => $c->publish_date->toDateString(),
                'short_description' => $c->getLocalized('short_description'),
                'featured_image' => $c->featured_image,
                'content_type' => $c->contentType->slug,
                'category' => $c->category ? $c->category->slug : null
            ]),
            'pagination' => [
                'total' => $contents->total(),
                'current_page' => $contents->currentPage(),
                'last_page' => $contents->lastPage(),
                'per_page' => $contents->perPage()
            ]
        ]);
    }

    /**
     * /api/content-details/{slug}
     */
    public function contentDetails(Request $request, string $slug): JsonResponse
    {
        $this->setLocale($request);

        $content = Content::published()->with(['contentType', 'category'])->where('slug', $slug)->first();

        if (!$content) {
            return response()->json([
                'success' => false,
                'message' => 'Content not found'
            ], 404);
        }

        // Fetch related contents
        $related = Content::published()
            ->where('content_type_id', $content->content_type_id)
            ->where('id', '!=', $content->id)
            ->limit(4)
            ->get()
            ->map(fn($r) => [
                'title' => $r->getLocalized('title'),
                'slug' => $r->slug,
                'featured_image' => $r->featured_image,
                'publish_date' => $r->publish_date->toDateString()
            ]);

        return response()->json([
            'success' => true,
            'data' => [
                'title' => $content->getLocalized('title'),
                'slug' => $content->slug,
                'author' => $content->getLocalized('author'),
                'publish_date' => $content->publish_date->toDateString(),
                'short_description' => $content->getLocalized('short_description'),
                'full_description' => $content->getLocalized('full_description'),
                'featured_image' => $content->featured_image,
                'media_gallery' => $content->media_gallery,
                'videos' => $content->videos,
                'audios' => $content->audios,
                'pdfs' => $content->pdfs,
                'tags' => $content->tags,
                'meta_title' => $content->getLocalized('meta_title'),
                'meta_description' => $content->getLocalized('meta_description'),
                'meta_keywords' => $content->getLocalized('meta_keywords'),
                'related' => $related
            ]
        ]);
    }

    /**
     * /api/events
     */
    public function events(Request $request): JsonResponse
    {
        $this->setLocale($request);

        $events = Event::active()->orderBy('start_date', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $events->map(fn($e) => [
                'id' => $e->id,
                'name' => $e->getLocalized('event_name'),
                'banner' => $e->banner,
                'description' => $e->getLocalized('description'),
                'venue' => $e->getLocalized('venue'),
                'start_date' => $e->start_date->toIso8601String(),
                'end_date' => $e->end_date ? $e->end_date->toIso8601String() : null
            ])
        ]);
    }

    /**
     * /api/gallery
     */
    public function gallery(Request $request): JsonResponse
    {
        $this->setLocale($request);

        $albums = Album::where('is_active', true)->with(['photos' => fn($q) => $q->where('is_active', true)])->orderBy('display_order')->get();

        return response()->json([
            'success' => true,
            'data' => $albums->map(fn($a) => [
                'id' => $a->id,
                'name' => $a->getLocalized('name'),
                'cover_image' => $a->cover_image,
                'category' => $a->getLocalized('category'),
                'description' => $a->getLocalized('description'),
                'photos' => $a->photos->map(fn($p) => [
                    'id' => $p->id,
                    'url' => $p->photo_path,
                    'caption' => $p->getLocalized('caption')
                ])
            ])
        ]);
    }

    /**
     * /api/videos
     */
    public function videos(Request $request): JsonResponse
    {
        $this->setLocale($request);

        // Fetch all video links configured across contents or homepage builder
        $contents = Content::published()->whereNotNull('videos')->latest('publish_date')->limit(20)->get();
        $videoList = [];

        foreach ($contents as $content) {
            foreach ($content->videos as $videoUrl) {
                $videoList[] = [
                    'title' => $content->getLocalized('title'),
                    'youtube_url' => $videoUrl,
                    'publish_date' => $content->publish_date->toDateString()
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => $videoList
        ]);
    }

    /**
     * /api/panchang
     */
    public function panchang(Request $request): JsonResponse
    {
        $this->setLocale($request);

        $date = $request->query('date', now()->toDateString());
        $panchang = Panchang::forDate($date);

        if (!$panchang) {
            return response()->json([
                'success' => false,
                'message' => 'Panchang details not available for this date'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $panchang
        ]);
    }

    /**
     * /api/festivals
     */
    public function festivals(Request $request): JsonResponse
    {
        $this->setLocale($request);

        $festivals = Festival::active()->orderBy('festival_date', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $festivals->map(fn($f) => [
                'id' => $f->id,
                'name' => $f->getLocalized('festival_name'),
                'date' => $f->festival_date->toDateString(),
                'description' => $f->getLocalized('description'),
                'image' => $f->image
            ])
        ]);
    }

    /**
     * /api/pages
     */
    public function pages(Request $request): JsonResponse
    {
        $this->setLocale($request);

        $slug = $request->query('slug');

        if ($slug) {
            $page = Page::active()->where('slug', $slug)->first();
            if (!$page) {
                return response()->json([
                    'success' => false,
                    'message' => 'Page not found'
                ], 404);
            }
            return response()->json([
                'success' => true,
                'data' => [
                    'title' => $page->getLocalized('title'),
                    'slug' => $page->slug,
                    'content' => $page->getLocalized('content'),
                    'featured_image' => $page->featured_image
                ]
            ]);
        }

        $pages = Page::active()->orderBy('display_order')->get();
        return response()->json([
            'success' => true,
            'data' => $pages->map(fn($p) => [
                'title' => $p->getLocalized('title'),
                'slug' => $p->slug,
                'featured_image' => $p->featured_image
            ])
        ]);
    }

    /**
     * /api/settings
     */
    public function settings(Request $request): JsonResponse
    {
        $this->setLocale($request);

        $settings = [
            'website_name' => Setting::get('website_name'),
            'logo' => Setting::get('logo'),
            'favicon' => Setting::get('favicon'),
            'contact' => [
                'mobile' => Setting::get('contact_mobile'),
                'email' => Setting::get('contact_email'),
                'whatsapp' => Setting::get('contact_whatsapp')
            ],
            'social' => [
                'facebook' => Setting::get('social_facebook'),
                'instagram' => Setting::get('social_instagram'),
                'youtube' => Setting::get('social_youtube'),
                'telegram' => Setting::get('social_telegram')
            ],
            'offices' => Setting::get('offices', [])
        ];

        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }
}
