<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Saint;
use App\Models\Vihar;
use App\Models\ContentType;
use App\Models\Category;
use App\Models\Content;
use App\Models\MediaFolder;
use App\Models\MediaLibrary;
use App\Models\Album;
use App\Models\AlbumPhoto;
use App\Models\Event;
use App\Models\Panchang;
use App\Models\Festival;
use App\Models\Page;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\HomepageSection;
use App\Models\Setting;
use App\Models\ActivityLog;
use App\Models\DailyQuote;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // --- 1. SAINTS & GURU PARAMPARA ---
    public function saints()
    {
        $saints = Saint::orderBy('display_order')->get();
        return view('admin.saints', compact('saints'));
    }

    public function saintStore(Request $request)
    {
        $request->validate([
            'name_hi' => 'required|string',
            'title_hi' => 'required|string',
        ]);

        $id = $request->input('id');
        
        $data = [
            'parent_id' => $request->input('parent_id') ?: null,
            'name' => [
                'hi' => $request->input('name_hi'),
                'en' => $request->input('name_en') ?: $request->input('name_hi'),
                'sa' => $request->input('name_sa') ?: $request->input('name_hi'),
            ],
            'title' => [
                'hi' => $request->input('title_hi'),
                'en' => $request->input('title_en') ?: $request->input('title_hi'),
                'sa' => $request->input('title_sa') ?: $request->input('title_hi'),
            ],
            'guru_name' => [
                'hi' => $request->input('guru_name_hi'),
                'en' => $request->input('guru_name_en') ?: $request->input('guru_name_hi'),
            ],
            'introduction' => [
                'hi' => $request->input('introduction_hi'),
            ],
            'biography' => [
                'hi' => $request->input('biography_hi'),
            ],
            'address' => [
                'hi' => $request->input('address_hi'),
            ],
            'diksha_date' => $request->input('diksha_date') ?: null,
            'mobile' => $request->input('mobile'),
            'email' => $request->input('email'),
            'is_active' => $request->has('is_active'),
            'is_featured' => $request->has('is_featured'),
            'show_on_website' => $request->has('show_on_website'),
            'show_on_app' => $request->has('show_on_app'),
            'display_order' => (int) $request->input('display_order', 0),
        ];

        // Handle Photo Upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('saints', 'public');
            $data['photo'] = Storage::url($path);
        }

        $saint = Saint::updateOrCreate(['id' => $id], $data);
        
        ActivityLog::log('Saint', $id ? 'Update' : 'Create', $saint->id, 'साधु: ' . $saint->getLocalized('name'));

        return redirect()->back()->with('success', 'साधु जानकारी सफलतापूर्वक सहेज ली गई!');
    }

    public function saintDelete($id)
    {
        $saint = Saint::findOrFail($id);
        $name = $saint->getLocalized('name');
        $saint->delete();
        ActivityLog::log('Saint', 'Delete', $id, 'साधु मिटाया गया: ' . $name);
        return redirect()->back()->with('success', 'साधु रिकॉर्ड मिटा दिया गया!');
    }

    // --- 2. VIHAR MANAGEMENT ---
    public function vihars()
    {
        $vihars = Vihar::with('saint')->orderBy('start_date', 'desc')->get();
        $saints = Saint::active()->get();
        return view('admin.vihar', compact('vihars', 'saints'));
    }

    public function viharStore(Request $request)
    {
        $request->validate([
            'saint_id' => 'required|exists:saints,id',
            'location_title_hi' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'start_date' => 'required|date',
        ]);

        $id = $request->input('id');
        $data = [
            'saint_id' => $request->input('saint_id'),
            'location_title' => [
                'hi' => $request->input('location_title_hi'),
                'en' => $request->input('location_title_en') ?: $request->input('location_title_hi'),
            ],
            'address' => [
                'hi' => $request->input('address_hi'),
            ],
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'country' => $request->input('country', 'India'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'google_map_link' => $request->input('google_map_link'),
            'contact_person' => $request->input('contact_person'),
            'contact_number' => $request->input('contact_number'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date') ?: null,
            'type' => $request->input('type', 'current'),
            'is_active' => $request->has('is_active'),
        ];

        $vihar = Vihar::updateOrCreate(['id' => $id], $data);
        
        ActivityLog::log('Vihar', $id ? 'Update' : 'Create', $vihar->id, 'स्थान: ' . $vihar->getLocalized('location_title'));

        return redirect()->back()->with('success', 'विहार स्थान सफलतापूर्वक सहेजा गया!');
    }

    public function viharDelete($id)
    {
        $vihar = Vihar::findOrFail($id);
        $title = $vihar->getLocalized('location_title');
        $vihar->delete();
        ActivityLog::log('Vihar', 'Delete', $id, 'विहार मिटाया गया: ' . $title);
        return redirect()->back()->with('success', 'विहार रिकॉर्ड मिटा दिया गया!');
    }

    // --- 3. DYNAMIC CONTENT TYPES ---
    public function contentTypes()
    {
        $types = ContentType::orderBy('display_order')->get();
        return view('admin.content_types', compact('types'));
    }

    public function contentTypeStore(Request $request)
    {
        $request->validate([
            'name_hi' => 'required|string',
            'slug' => 'required|string|unique:content_types,slug,' . $request->input('id'),
        ]);

        $id = $request->input('id');
        $data = [
            'name' => [
                'hi' => $request->input('name_hi'),
                'en' => $request->input('name_en') ?: $request->input('name_hi'),
            ],
            'slug' => Str::slug($request->input('slug')),
            'icon' => $request->input('icon', 'file-text'),
            'display_order' => (int) $request->input('display_order', 0),
        ];

        $type = ContentType::updateOrCreate(['id' => $id], $data);
        
        ActivityLog::log('ContentType', $id ? 'Update' : 'Create', $type->id, 'प्रकार: ' . $type->slug);

        return redirect()->back()->with('success', 'कंटेंट प्रकार सहेजा गया!');
    }

    public function contentTypeDelete($id)
    {
        $type = ContentType::findOrFail($id);
        $slug = $type->slug;
        $type->delete();
        ActivityLog::log('ContentType', 'Delete', $id, 'मिटाया गया: ' . $slug);
        return redirect()->back()->with('success', 'कंटेंट प्रकार मिटा दिया गया!');
    }

    // --- 4. DYNAMIC CATEGORY SYSTEM ---
    public function categories()
    {
        $categories = Category::with('contentType', 'parent')->orderBy('display_order')->get();
        $types = ContentType::active()->get();
        return view('admin.categories', compact('categories', 'types'));
    }

    public function categoryStore(Request $request)
    {
        $request->validate([
            'content_type_id' => 'required|exists:content_types,id',
            'name_hi' => 'required|string',
            'slug' => 'required|string',
        ]);

        $id = $request->input('id');
        $data = [
            'content_type_id' => $request->input('content_type_id'),
            'parent_id' => $request->input('parent_id') ?: null,
            'name' => [
                'hi' => $request->input('name_hi'),
                'en' => $request->input('name_en') ?: $request->input('name_hi'),
            ],
            'slug' => Str::slug($request->input('slug')),
            'display_order' => (int) $request->input('display_order', 0),
        ];

        $category = Category::updateOrCreate(['id' => $id], $data);
        
        ActivityLog::log('Category', $id ? 'Update' : 'Create', $category->id, 'श्रेणी: ' . $category->slug);

        return redirect()->back()->with('success', 'श्रेणी सफलतापूर्वक सहेज ली गई!');
    }

    public function categoryDelete($id)
    {
        $category = Category::findOrFail($id);
        $slug = $category->slug;
        $category->delete();
        ActivityLog::log('Category', 'Delete', $id, 'मिटाया गया: ' . $slug);
        return redirect()->back()->with('success', 'श्रेणी मिटा दी गई!');
    }

    // --- 5. CONTENT EDITOR ---
    public function contents()
    {
        $contents = Content::with('contentType', 'category')->orderBy('publish_date', 'desc')->get();
        return view('admin.contents.index', compact('contents'));
    }

    public function contentsCreate()
    {
        $types = ContentType::active()->get();
        $categories = Category::active()->get();
        $media = MediaLibrary::latest()->get();
        return view('admin.contents.create', compact('types', 'categories', 'media'));
    }

    public function contentsEdit($id)
    {
        $content = Content::findOrFail($id);
        $types = ContentType::active()->get();
        $categories = Category::active()->get();
        $media = MediaLibrary::latest()->get();
        return view('admin.contents.create', compact('content', 'types', 'categories', 'media'));
    }

    public function contentsStore(Request $request)
    {
        $request->validate([
            'title_hi' => 'required|string',
            'content_type_id' => 'required|exists:content_types,id',
            'publish_date' => 'required|date',
        ]);

        $id = $request->input('id');
        
        // Parse Videos (YouTube and Self-Hosted)
        $videosInput = $request->input('videos', []);
        $videos = [];
        if (is_array($videosInput)) {
            foreach ($videosInput as $v) {
                if (is_array($v) && !empty($v['url'])) {
                    $type = !empty($v['type']) ? $v['type'] : (preg_match('/(?:youtube\.com|youtu\.be)/i', $v['url']) ? 'youtube' : 'self_hosted');
                    $videos[] = [
                        'type' => $type,
                        'url' => trim($v['url']),
                        'title' => trim($v['title'] ?? ''),
                    ];
                } elseif (is_string($v) && trim($v) !== '') {
                    $url = trim($v);
                    $type = preg_match('/(?:youtube\.com|youtu\.be)/i', $url) ? 'youtube' : 'self_hosted';
                    $videos[] = [
                        'type' => $type,
                        'url' => $url,
                        'title' => '',
                    ];
                }
            }
        } elseif (is_string($videosInput)) {
            $lines = array_filter(explode("\n", str_replace("\r", "", $videosInput)));
            foreach ($lines as $line) {
                $url = trim($line);
                if ($url) {
                    $type = preg_match('/(?:youtube\.com|youtu\.be)/i', $url) ? 'youtube' : 'self_hosted';
                    $videos[] = [
                        'type' => $type,
                        'url' => $url,
                        'title' => '',
                    ];
                }
            }
        }

        // Parse Audios (Multiple Audios)
        $audiosInput = $request->input('audios', []);
        $audios = [];
        if (is_array($audiosInput)) {
            foreach ($audiosInput as $a) {
                if (is_array($a) && !empty($a['url'])) {
                    $audios[] = [
                        'url' => trim($a['url']),
                        'title' => trim($a['title'] ?? ''),
                    ];
                } elseif (is_string($a) && trim($a) !== '') {
                    $audios[] = [
                        'url' => trim($a),
                        'title' => '',
                    ];
                }
            }
        } elseif (is_string($audiosInput)) {
            $lines = array_filter(explode("\n", str_replace("\r", "", $audiosInput)));
            foreach ($lines as $line) {
                $url = trim($line);
                if ($url) {
                    $audios[] = [
                        'url' => $url,
                        'title' => '',
                    ];
                }
            }
        }

        // Parse PDFs (Multiple PDF Books / Documents)
        $pdfsInput = $request->input('pdfs', []);
        $pdfs = [];
        if (is_array($pdfsInput)) {
            foreach ($pdfsInput as $p) {
                if (is_array($p) && !empty($p['url'])) {
                    $pdfs[] = [
                        'url' => trim($p['url']),
                        'title' => trim($p['title'] ?? ''),
                    ];
                } elseif (is_string($p) && trim($p) !== '') {
                    $pdfs[] = [
                        'url' => trim($p),
                        'title' => '',
                    ];
                }
            }
        } elseif (is_string($pdfsInput)) {
            $lines = array_filter(explode("\n", str_replace("\r", "", $pdfsInput)));
            foreach ($lines as $line) {
                $url = trim($line);
                if ($url) {
                    $pdfs[] = [
                        'url' => $url,
                        'title' => '',
                    ];
                }
            }
        }

        // Parse Media Gallery (Multiple Images)
        $galleryInput = $request->input('media_gallery', []);
        $mediaGallery = [];
        if (is_array($galleryInput)) {
            foreach ($galleryInput as $g) {
                if (is_array($g) && !empty($g['url'])) {
                    $mediaGallery[] = [
                        'url' => trim($g['url']),
                        'caption' => trim($g['caption'] ?? ''),
                    ];
                } elseif (is_string($g) && trim($g) !== '') {
                    $mediaGallery[] = [
                        'url' => trim($g),
                        'caption' => '',
                    ];
                }
            }
        }

        // Parse Tags
        $tags = array_filter(explode(",", $request->input('tags', '')));

        $data = [
            'content_type_id' => $request->input('content_type_id'),
            'category_id' => $request->input('category_id') ?: null,
            'title' => [
                'hi' => $request->input('title_hi'),
                'en' => $request->input('title_en') ?: $request->input('title_hi'),
            ],
            'slug' => $request->input('slug') ? Str::slug($request->input('slug')) : Str::slug($request->input('title_hi')),
            'author' => [
                'hi' => $request->input('author_hi'),
                'en' => $request->input('author_en') ?: $request->input('author_hi'),
            ],
            'publish_date' => $request->input('publish_date'),
            'short_description' => [
                'hi' => $request->input('short_description_hi'),
            ],
            'full_description' => [
                'hi' => $request->input('full_description_hi'),
            ],
            'featured_image' => $request->input('featured_image'),
            'media_gallery' => array_values($mediaGallery),
            'videos' => array_values($videos),
            'audios' => array_values($audios),
            'pdfs' => array_values($pdfs),
            'tags' => array_map('trim', array_values($tags)),
            'status' => $request->input('status', 'draft'),
            'is_active' => $request->has('is_active'),
            'is_featured' => $request->has('is_featured'),
            'show_on_website' => $request->has('show_on_website'),
            'show_on_app' => $request->has('show_on_app'),
            'display_order' => (int) $request->input('display_order', 0),
            'meta_title' => [
                'hi' => $request->input('meta_title_hi'),
            ],
            'meta_description' => [
                'hi' => $request->input('meta_description_hi'),
            ],
        ];

        $content = Content::updateOrCreate(['id' => $id], $data);
        
        ActivityLog::log('Content', $id ? 'Update' : 'Create', $content->id, 'शीर्षक: ' . $content->getLocalized('title'));

        return redirect('/admin/contents')->with('success', 'कंटेंट सफलतापूर्वक सहेज लिया गया!');
    }

    public function contentsDelete($id)
    {
        $content = Content::findOrFail($id);
        $title = $content->getLocalized('title');
        $content->delete();
        ActivityLog::log('Content', 'Delete', $id, 'मिटाया गया: ' . $title);
        return redirect()->back()->with('success', 'कंटेंट रिकॉर्ड मिटा दिया गया!');
    }

    // --- 6. MEDIA LIBRARY ---
    public function media()
    {
        $folders = MediaFolder::with('children')->whereNull('parent_id')->get();
        $files = MediaLibrary::latest()->get();
        return view('admin.media', compact('folders', 'files'));
    }

    public function mediaUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:20480', // Max 20MB
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $mime = $file->getMimeType();
        $size = $file->getSize();

        // Determine File Type Group
        $type = 'document';
        if (str_starts_with($mime, 'image/')) $type = 'image';
        elseif (str_starts_with($mime, 'video/')) $type = 'video';
        elseif (str_starts_with($mime, 'audio/')) $type = 'audio';
        elseif ($extension === 'pdf') $type = 'pdf';

        // Save File physically
        $path = $file->store('media', 'public');

        $media = MediaLibrary::create([
            'folder_id' => $request->input('folder_id') ?: null,
            'name' => pathinfo($originalName, PATHINFO_FILENAME),
            'file_name' => $originalName,
            'file_path' => Storage::url($path),
            'mime_type' => $mime,
            'file_size' => $size,
            'file_type' => $type,
            'created_by' => auth()->id()
        ]);

        ActivityLog::log('MediaLibrary', 'Upload', $media->id, 'फ़ाइल: ' . $originalName);

        return response()->json([
            'success' => true,
            'url' => $media->file_path,
            'id' => $media->id
        ]);
    }

    public function mediaFolderStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $folder = MediaFolder::create([
            'name' => $request->input('name'),
            'parent_id' => $request->input('parent_id') ?: null,
            'created_by' => auth()->id()
        ]);

        ActivityLog::log('MediaFolder', 'Create', $folder->id, 'फ़ोल्डर: ' . $folder->name);

        return redirect()->back()->with('success', 'फ़ोल्डर बना दिया गया!');
    }

    public function mediaDelete($id)
    {
        $media = MediaLibrary::findOrFail($id);
        $name = $media->name;
        $media->delete();
        ActivityLog::log('MediaLibrary', 'Delete', $id, 'फ़ाइल मिटाई गई: ' . $name);
        return redirect()->back()->with('success', 'मीडिया फ़ाइल मिटा दी गई!');
    }

    // --- 7. ALBUMS & PHOTO GALLERY ---
    public function albums()
    {
        $albums = Album::with('photos')->orderBy('display_order')->get();
        return view('admin.albums', compact('albums'));
    }

    public function albumStore(Request $request)
    {
        $request->validate([
            'name_hi' => 'required|string',
        ]);

        $id = $request->input('id');
        $data = [
            'name' => [
                'hi' => $request->input('name_hi'),
                'en' => $request->input('name_en') ?: $request->input('name_hi'),
            ],
            'cover_image' => $request->input('cover_image'),
            'category' => [
                'hi' => $request->input('category_hi'),
            ],
            'description' => [
                'hi' => $request->input('description_hi'),
            ],
            'is_active' => $request->has('is_active'),
        ];

        $album = Album::updateOrCreate(['id' => $id], $data);

        ActivityLog::log('Album', $id ? 'Update' : 'Create', $album->id, 'एल्बम: ' . $album->getLocalized('name'));

        return redirect()->back()->with('success', 'एल्बम सहेज लिया गया!');
    }

    public function albumDelete($id)
    {
        $album = Album::findOrFail($id);
        $name = $album->getLocalized('name');
        $album->delete();
        ActivityLog::log('Album', 'Delete', $id, 'मिटाया गया: ' . $name);
        return redirect()->back()->with('success', 'एल्बम मिटा दिया गया!');
    }

    public function albumPhotoUpload(Request $request)
    {
        $request->validate([
            'album_id' => 'required|exists:albums,id',
            'file' => 'required|image|max:10240'
        ]);

        $file = $request->file('file');
        $path = $file->store('gallery', 'public');

        $photo = AlbumPhoto::create([
            'album_id' => $request->input('album_id'),
            'photo_path' => Storage::url($path),
            'caption' => [
                'hi' => $request->input('caption_hi') ?: $file->getClientOriginalName(),
            ],
            'is_active' => true,
            'created_by' => auth()->id()
        ]);

        ActivityLog::log('AlbumPhoto', 'Upload', $photo->id, 'एल्बम आईडी: ' . $photo->album_id);

        return redirect()->back()->with('success', 'चित्र अपलोड कर दिया गया!');
    }

    // --- 8. EVENTS ---
    public function events()
    {
        $events = Event::orderBy('start_date', 'desc')->get();
        return view('admin.events', compact('events'));
    }

    public function eventStore(Request $request)
    {
        $request->validate([
            'event_name_hi' => 'required|string',
            'venue_hi' => 'required|string',
            'start_date' => 'required|date',
        ]);

        $id = $request->input('id');
        $data = [
            'event_name' => [
                'hi' => $request->input('event_name_hi'),
                'en' => $request->input('event_name_en') ?: $request->input('event_name_hi'),
            ],
            'venue' => [
                'hi' => $request->input('venue_hi'),
            ],
            'description' => [
                'hi' => $request->input('description_hi'),
            ],
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date') ?: null,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('events', 'public');
            $data['banner'] = Storage::url($path);
        }

        $event = Event::updateOrCreate(['id' => $id], $data);

        ActivityLog::log('Event', $id ? 'Update' : 'Create', $event->id, 'कार्यक्रम: ' . $event->getLocalized('event_name'));

        return redirect()->back()->with('success', 'मांगलिक कार्यक्रम सहेजा गया!');
    }

    public function eventDelete($id)
    {
        $event = Event::findOrFail($id);
        $name = $event->getLocalized('event_name');
        $event->delete();
        ActivityLog::log('Event', 'Delete', $id, 'मिटाया गया: ' . $name);
        return redirect()->back()->with('success', 'कार्यक्रम मिटा दिया गया!');
    }

    // --- 9. PANCHANG ---
    public function panchang()
    {
        $panchangs = Panchang::orderBy('date', 'desc')->limit(30)->get();
        return view('admin.panchang', compact('panchangs'));
    }

    public function panchangStore(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'tithi' => 'required|string',
            'paksha' => 'required|string',
            'maas' => 'required|string',
        ]);

        $panchang = Panchang::updateOrCreate(
            ['date' => $request->input('date')],
            [
                'tithi' => $request->input('tithi'),
                'paksha' => $request->input('paksha'),
                'maas' => $request->input('maas'),
                'nakshatra' => $request->input('nakshatra'),
                'sunrise' => $request->input('sunrise'),
                'sunset' => $request->input('sunset'),
                'notes' => $request->input('notes'),
                'is_active' => true,
            ]
        );

        ActivityLog::log('Panchang', 'Update', $panchang->id, 'दिनांक: ' . $panchang->date->toDateString());

        return redirect()->back()->with('success', 'पंचांग डेटा सफलतापूर्वक सहेज लिया गया!');
    }

    // --- 10. FESTIVAL CALENDAR ---
    public function festivals()
    {
        $festivals = Festival::orderBy('festival_date', 'desc')->get();
        return view('admin.festivals', compact('festivals'));
    }

    public function festivalStore(Request $request)
    {
        $request->validate([
            'festival_name_hi' => 'required|string',
            'festival_date' => 'required|date',
        ]);

        $id = $request->input('id');
        $data = [
            'festival_name' => [
                'hi' => $request->input('festival_name_hi'),
                'en' => $request->input('festival_name_en') ?: $request->input('festival_name_hi'),
            ],
            'festival_date' => $request->input('festival_date'),
            'description' => [
                'hi' => $request->input('description_hi'),
            ],
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('festivals', 'public');
            $data['image'] = Storage::url($path);
        }

        $festival = Festival::updateOrCreate(['id' => $id], $data);

        ActivityLog::log('Festival', $id ? 'Update' : 'Create', $festival->id, 'त्योहार: ' . $festival->getLocalized('festival_name'));

        return redirect()->back()->with('success', 'त्योहार सहेजा गया!');
    }

    public function festivalDelete($id)
    {
        $festival = Festival::findOrFail($id);
        $name = $festival->getLocalized('festival_name');
        $festival->delete();
        ActivityLog::log('Festival', 'Delete', $id, 'मिटाया गया: ' . $name);
        return redirect()->back()->with('success', 'त्योहार रिकॉर्ड मिटा दिया गया!');
    }

    // --- 10B. DAILY QUOTES & SUVICHAR ARCHIVE ---
    public function quotes(Request $request)
    {
        $query = DailyQuote::query();
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function($sub) use ($q) {
                $sub->where('quote_text->hi', 'like', "%{$q}%")
                    ->orWhere('author->hi', 'like', "%{$q}%")
                    ->orWhere('category', 'like', "%{$q}%");
            });
        }
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }
        $quotes = $query->orderBy('quote_date', 'desc')->paginate(20)->withQueryString();
        $categories = DailyQuote::distinct()->pluck('category')->filter()->values();
        return view('admin.quotes', compact('quotes', 'categories'));
    }

    public function quoteStore(Request $request)
    {
        $request->validate([
            'quote_text_hi' => 'required|string',
            'quote_date' => 'required|date',
        ]);

        $id = $request->input('id');
        $data = [
            'quote_date' => $request->input('quote_date'),
            'category' => $request->input('category') ?: 'अहिंसा',
            'title' => [
                'hi' => $request->input('title_hi') ?: 'आज का आध्यात्मिक संदेश',
                'en' => $request->input('title_en') ?: 'Daily Spiritual Quote',
            ],
            'quote_text' => [
                'hi' => $request->input('quote_text_hi'),
                'en' => $request->input('quote_text_en') ?: $request->input('quote_text_hi'),
            ],
            'author' => [
                'hi' => $request->input('author_hi') ?: 'भगवान महावीर स्वामी',
                'en' => $request->input('author_en') ?: $request->input('author_hi'),
            ],
            'image' => $request->input('image') ?: '/images/jain/muni_vidyasagar.jpg',
            'is_active' => $request->has('is_active'),
            'is_featured' => $request->has('is_featured'),
        ];

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('quotes', 'public');
            $data['image'] = Storage::url($path);
        }

        if ($data['is_featured']) {
            DailyQuote::where('is_featured', true)->update(['is_featured' => false]);
            // Sync with Homepage Setting
            Setting::set('spiritual_message_quote', $data['quote_text']['hi'], 'text', 'quotation');
            Setting::set('spiritual_message_author', $data['author']['hi'], 'text', 'quotation');
            Setting::set('daily_quote_title', $data['title']['hi'], 'text', 'quotation');
            Setting::set('daily_quote_image', $data['image'], 'text', 'quotation');
        }

        $quote = DailyQuote::updateOrCreate(['id' => $id], $data);

        ActivityLog::log('DailyQuote', $id ? 'Update' : 'Create', $quote->id, 'सुविचार: ' . $quote->getLocalized('quote_text'));

        return redirect()->back()->with('success', 'दैनिक सुविचार सफलतापूर्वक सहेज लिया गया!');
    }

    public function quoteDelete($id)
    {
        $quote = DailyQuote::findOrFail($id);
        $quote->delete();
        ActivityLog::log('DailyQuote', 'Delete', $id, 'सुविचार मिटाया गया।');
        return redirect()->back()->with('success', 'दैनिक सुविचार मिटा दिया गया!');
    }

    public function quoteToggleFeatured($id)
    {
        $quote = DailyQuote::findOrFail($id);
        DailyQuote::where('is_featured', true)->update(['is_featured' => false]);
        $quote->is_featured = true;
        $quote->save();

        // Sync with Homepage Setting
        Setting::set('spiritual_message_quote', $quote->getLocalized('quote_text'), 'text', 'quotation');
        Setting::set('spiritual_message_author', $quote->getLocalized('author'), 'text', 'quotation');
        Setting::set('daily_quote_title', $quote->getLocalized('title') ?: 'आज का आध्यात्मिक संदेश', 'text', 'quotation');
        Setting::set('daily_quote_image', $quote->image, 'text', 'quotation');

        ActivityLog::log('DailyQuote', 'Feature', $id, 'सुविचार को आज का मुख्य संदेश बनाया गया।');

        return redirect()->back()->with('success', 'सुविचार को आज के मुख्य संदेश के रूप में सक्रिय किया गया!');
    }

    // --- 11. DYNAMIC PAGES ---
    public function pages()
    {
        $pages = Page::orderBy('display_order')->get();
        return view('admin.pages', compact('pages'));
    }

    public function pageStore(Request $request)
    {
        $request->validate([
            'title_hi' => 'required|string',
            'slug' => 'required|string|unique:pages,slug,' . $request->input('id'),
        ]);

        $id = $request->input('id');
        $data = [
            'title' => [
                'hi' => $request->input('title_hi'),
                'en' => $request->input('title_en') ?: $request->input('title_hi'),
            ],
            'slug' => Str::slug($request->input('slug')),
            'content' => [
                'hi' => $request->input('content_hi'),
            ],
            'is_active' => $request->has('is_active'),
            'meta_title' => [
                'hi' => $request->input('meta_title_hi'),
            ],
            'meta_description' => [
                'hi' => $request->input('meta_description_hi'),
            ],
        ];

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('pages', 'public');
            $data['featured_image'] = Storage::url($path);
        }

        $page = Page::updateOrCreate(['id' => $id], $data);

        ActivityLog::log('Page', $id ? 'Update' : 'Create', $page->id, 'पेज: ' . $page->slug);

        return redirect()->back()->with('success', 'पेज सफलतापूर्वक सहेज लिया गया!');
    }

    public function pageDelete($id)
    {
        $page = Page::findOrFail($id);
        $slug = $page->slug;
        $page->delete();
        ActivityLog::log('Page', 'Delete', $id, 'मिटाया गया: ' . $slug);
        return redirect()->back()->with('success', 'पेज मिटा दिया गया!');
    }

    // --- 12. MENU BUILDER (WordPress-Style) ---
    public function menus(Request $request)
    {
        $menus = Menu::orderBy('id', 'asc')->get();
        $menuId = $request->query('menu');
        
        $currentMenu = null;
        if ($request->query('action') === 'new') {
            $currentMenu = null;
        } elseif ($menuId) {
            $currentMenu = Menu::with(['allItems' => function($q) {
                $q->orderBy('display_order');
            }])->find($menuId);
        } else {
            $first = $menus->first();
            $currentMenu = $first ? Menu::with(['allItems' => function($q) {
                $q->orderBy('display_order');
            }])->find($first->id) : null;
        }

        $pages = Page::where('is_active', true)->orderBy('id', 'desc')->get();
        $categories = Category::where('is_active', true)->get();
        $saints = Saint::where('is_active', true)->orderBy('display_order')->get();

        // Predefined Jain portal modules & routes
        $contentTypes = [
            ['title' => 'मुख्य पृष्ठ (Home)', 'url' => '/', 'type' => 'home', 'icon' => 'fa-solid fa-house'],
            ['title' => 'दैनिक सुविचार (Daily Quotes)', 'url' => '/suvichar', 'type' => 'quote', 'icon' => 'fa-solid fa-quote-left'],
            ['title' => 'पूज्य संत संघ (Saints)', 'url' => '/sants', 'type' => 'saint', 'icon' => 'fa-solid fa-user-tie'],
            ['title' => 'अमृत प्रवचन (Pravachans)', 'url' => '/pravachans', 'type' => 'pravachan', 'icon' => 'fa-solid fa-microphone'],
            ['title' => 'ज्ञान भण्डार / ग्रन्थ (Granths)', 'url' => '/granths', 'type' => 'granth', 'icon' => 'fa-solid fa-book-open'],
            ['title' => 'भजन एवं स्तुति (Bhajans)', 'url' => '/bhajans', 'type' => 'bhajan', 'icon' => 'fa-solid fa-music'],
            ['title' => 'धर्म समाचार (News)', 'url' => '/news', 'type' => 'news', 'icon' => 'fa-solid fa-newspaper'],
            ['title' => 'जैन पंचांग (Panchang)', 'url' => '/panchang', 'type' => 'panchang', 'icon' => 'fa-solid fa-calendar-days'],
            ['title' => 'पर्व एवं त्योहार (Festivals)', 'url' => '/festivals', 'type' => 'festival', 'icon' => 'fa-solid fa-award'],
            ['title' => 'चित्र दीर्घा (Photos)', 'url' => '/gallery/photos', 'type' => 'gallery', 'icon' => 'fa-solid fa-images'],
            ['title' => 'वीडियो दीर्घा (Videos)', 'url' => '/gallery/videos', 'type' => 'gallery', 'icon' => 'fa-solid fa-video'],
            ['title' => 'PDF ग्रन्थ व पत्रिका (PDFs)', 'url' => '/gallery/pdfs', 'type' => 'gallery', 'icon' => 'fa-solid fa-file-pdf'],
            ['title' => 'आगामी कार्यक्रम (Events)', 'url' => '/events', 'type' => 'event', 'icon' => 'fa-solid fa-clock'],
            ['title' => 'संघ व लाइव विहार (Sangh & Vihar)', 'url' => '/sangh-vihar', 'type' => 'vihar', 'icon' => 'fa-solid fa-location-dot'],
            ['title' => 'संपर्क केंद्र (Contact)', 'url' => '/contact', 'type' => 'contact', 'icon' => 'fa-solid fa-envelope'],
        ];

        return view('admin.menus', compact('menus', 'currentMenu', 'pages', 'categories', 'saints', 'contentTypes'));
    }

    public function menuStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $location = $request->input('location');
        if ($location === 'header') {
            Menu::where('location', 'header')->update(['location' => null]);
        }

        $menu = Menu::create([
            'name' => $request->input('name'),
            'location' => $location,
            'is_active' => true
        ]);

        ActivityLog::log('Menu', 'Create', $menu->id, 'मेनू: ' . $menu->name);

        return redirect('/admin/menus?menu=' . $menu->id)->with('success', 'नया मेनू बनाया गया!');
    }

    public function menuSaveStructure(Request $request)
    {
        $request->validate([
            'menu_name' => 'required|string|max:255',
        ]);

        $menuId = $request->input('menu_id');
        $locations = $request->input('locations', []);
        $primaryLocation = !empty($locations) ? $locations[0] : $request->input('location', null);

        if (in_array('header', $locations) || $primaryLocation === 'header') {
            Menu::where('location', 'header')->where('id', '!=', $menuId)->update(['location' => null]);
            $primaryLocation = 'header';
        }

        if ($menuId) {
            $menu = Menu::findOrFail($menuId);
            $menu->update([
                'name' => $request->input('menu_name'),
                'location' => $primaryLocation,
                'is_active' => true,
            ]);
        } else {
            $menu = Menu::create([
                'name' => $request->input('menu_name'),
                'location' => $primaryLocation,
                'is_active' => true,
            ]);
            $menuId = $menu->id;
        }

        $itemsData = $request->input('items_data');
        if ($itemsData) {
            $itemsArray = is_string($itemsData) ? json_decode($itemsData, true) : $itemsData;
            
            if (is_array($itemsArray)) {
                MenuItem::where('menu_id', $menu->id)->delete();

                $idMap = [];

                foreach ($itemsArray as $order => $item) {
                    $clientId = $item['client_id'] ?? ($item['id'] ?? (string)($order + 1));
                    $parentClientId = $item['parent_client_id'] ?? null;
                    $parentId = ($parentClientId && isset($idMap[$parentClientId])) ? $idMap[$parentClientId] : null;

                    $labelHi = $item['label_hi'] ?? ($item['label']['hi'] ?? ($item['title'] ?? 'लिंक'));
                    $labelEn = $item['label_en'] ?? ($item['label']['en'] ?? $labelHi);

                    $itemUrl = $item['url'] ?? '#';
                    $type = (str_starts_with($itemUrl, 'http://') || str_starts_with($itemUrl, 'https://')) ? 'external' : 'internal';

                    $newItem = MenuItem::create([
                        'menu_id' => $menu->id,
                        'parent_id' => $parentId,
                        'label' => [
                            'hi' => $labelHi,
                            'en' => $labelEn,
                        ],
                        'url' => $itemUrl,
                        'target' => $item['target'] ?? '_self',
                        'icon' => $item['icon'] ?? null,
                        'classes' => $item['classes'] ?? null,
                        'type' => $type,
                        'display_order' => $order + 1,
                    ]);

                    if ($clientId) {
                        $idMap[$clientId] = $newItem->id;
                    }
                }
            }
        }

        ActivityLog::log('Menu', 'SaveStructure', $menu->id, 'मेनू संरचना सहेजी गई: ' . $menu->name);

        return redirect('/admin/menus?menu=' . $menu->id)->with('success', 'मेनू "' . $menu->name . '" सफलतापूर्वक सहेज लिया गया!');
    }

    public function menuDelete($id)
    {
        $menu = Menu::findOrFail($id);
        $name = $menu->name;
        MenuItem::where('menu_id', $menu->id)->delete();
        $menu->delete();
        ActivityLog::log('Menu', 'Delete', $id, 'मेनू मिटाया गया: ' . $name);
        return redirect('/admin/menus')->with('success', 'मेनू "' . $name . '" मिटा दिया गया!');
    }

    public function menuItemStore(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'label_hi' => 'required|string',
            'url' => 'required|string',
        ]);

        $item = MenuItem::create([
            'menu_id' => $request->input('menu_id'),
            'parent_id' => $request->input('parent_id') ?: null,
            'label' => [
                'hi' => $request->input('label_hi'),
                'en' => $request->input('label_en') ?: $request->input('label_hi'),
            ],
            'url' => $request->input('url'),
            'type' => $request->input('type', 'internal'),
            'display_order' => (int) $request->input('display_order', 0)
        ]);

        ActivityLog::log('MenuItem', 'Create', $item->id, 'मेनू लिंक: ' . $item->url);

        return redirect()->back()->with('success', 'मेनू में कड़ी जोड़ दी गई!');
    }

    // --- 13. HOMEPAGE BUILDER ---
    public function homepage()
    {
        $sections = HomepageSection::orderBy('display_order')->get();
        return view('admin.homepage', compact('sections'));
    }

    public function homepageSort(Request $request)
    {
        $order = $request->input('order'); // Array of IDs
        if (is_array($order)) {
            foreach ($order as $position => $id) {
                HomepageSection::where('id', $id)->update(['display_order' => $position + 1]);
            }
        }
        ActivityLog::log('HomepageBuilder', 'Reorder', null, 'होमपेज अनुभागों का क्रम बदला गया।');
        return response()->json(['success' => true]);
    }

    public function homepageToggle(Request $request)
    {
        $id = $request->input('id');
        $field = $request->input('field'); // is_active / show_on_website / show_on_app
        
        $section = HomepageSection::findOrFail($id);
        $section->$field = !$section->$field;
        $section->save();

        ActivityLog::log('HomepageBuilder', 'Toggle', $id, 'होमपेज अनुभाग: ' . $section->section_key . ' फील्ड: ' . $field);

        return response()->json(['success' => true]);
    }

    // --- 13B. SANGH INFORMATION & LIVE VIHAR STATUS ---
    public function sanghVihar()
    {
        $settings = Setting::where('group', 'vihar')
            ->orWhere('key', 'marquee_text')
            ->orWhere('key', 'like', 'vihar_%')
            ->pluck('value', 'key');
        return view('admin.sangh_vihar', compact('settings'));
    }

    public function sanghViharStore(Request $request)
    {
        $inputs = $request->except(['_token', 'vihar_poster_image_file']);

        if ($request->hasFile('vihar_poster_image_file')) {
            $path = $request->file('vihar_poster_image_file')->store('vihar', 'public');
            $inputs['vihar_poster_image'] = Storage::url($path);
        }

        $inputs['vihar_status_active'] = $request->has('vihar_status_active') ? '1' : '0';

        foreach ($inputs as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            $rawValue = (is_array($value)) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value;
            $type = is_array($value) ? 'json' : 'text';

            if ($setting) {
                if ($setting->type === 'json' && is_array($value)) {
                    $setting->value = json_encode($value, JSON_UNESCAPED_UNICODE);
                } else {
                    $setting->value = $rawValue;
                }
                $setting->save();
            } else {
                Setting::create([
                    'key' => $key,
                    'value' => $rawValue,
                    'type' => $type,
                    'group' => 'vihar',
                    'updated_by' => auth()->id()
                ]);
            }
        }

        ActivityLog::log('SanghVihar', 'Update', null, 'संघ जानकारी एवं लाइव विहार स्थिति अद्यतित की गई।');

        return redirect()->back()->with('success', 'संघ जानकारी एवं लाइव विहार स्थिति सफलतापूर्वक सहेज ली गई!');
    }

    // --- 14. WEBSITE SETTINGS ---
    public function settings()
    {
        $groups = Setting::orderBy('group')->get()->groupBy('group');
        return view('admin.settings', compact('groups'));
    }

    public function settingsStore(Request $request)
    {
        $inputs = $request->except(['_token', 'daily_quote_image_file', 'logo_file', 'favicon_file', 'vihar_poster_image_file']);
        
        // Handle Daily Quotation Image Upload
        if ($request->hasFile('daily_quote_image_file')) {
            $path = $request->file('daily_quote_image_file')->store('quotes', 'public');
            $inputs['daily_quote_image'] = Storage::url($path);
        }

        // Handle Vihar Poster Image Upload
        if ($request->hasFile('vihar_poster_image_file')) {
            $path = $request->file('vihar_poster_image_file')->store('vihar', 'public');
            $inputs['vihar_poster_image'] = Storage::url($path);
        }

        // Handle Logo Upload
        if ($request->hasFile('logo_file')) {
            $path = $request->file('logo_file')->store('branding', 'public');
            $inputs['logo'] = Storage::url($path);
        }

        // Handle Favicon Upload
        if ($request->hasFile('favicon_file')) {
            $path = $request->file('favicon_file')->store('branding', 'public');
            $inputs['favicon'] = Storage::url($path);
        }
        
        foreach ($inputs as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            $rawValue = (is_array($value)) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value;
            $type = is_array($value) ? 'json' : 'text';
            $group = 'general';
            if (str_starts_with($key, 'contact_')) $group = 'contact';
            elseif (str_starts_with($key, 'social_')) $group = 'social';
            elseif (str_starts_with($key, 'smtp_')) $group = 'smtp';
            elseif (str_starts_with($key, 'daily_quote_') || str_starts_with($key, 'spiritual_message_')) $group = 'quotation';
            elseif (str_starts_with($key, 'vihar_') || $key === 'marquee_text') $group = 'vihar';
            elseif ($key === 'offices') $group = 'offices';

            if ($setting) {
                if ($setting->type === 'json' && is_array($value)) {
                    $setting->value = json_encode($value, JSON_UNESCAPED_UNICODE);
                } else {
                    $setting->value = $rawValue;
                }
                $setting->save();
            } else {
                Setting::create([
                    'key' => $key,
                    'value' => $rawValue,
                    'type' => $type,
                    'group' => $group,
                    'updated_by' => auth()->id()
                ]);
            }
        }

        ActivityLog::log('Settings', 'Update', null, 'वेबसाइट एवं दैनिक सुविचार सेटिंग्स अद्यतित की गईं।');

        return redirect()->back()->with('success', 'वेबसाइट एवं दैनिक सुविचार सेटिंग्स सफलतापूर्वक सहेजी गईं!');
    }

    // --- 15. ACTIVITY LOGS ---
    public function logs()
    {
        $logs = ActivityLog::with('user')->latest('id')->paginate(30);
        return view('admin.logs', compact('logs'));
    }
}
