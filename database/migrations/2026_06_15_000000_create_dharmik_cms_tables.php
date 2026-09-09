<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Activity Logs
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('module');
            $table->string('action');
            $table->unsignedBigInteger('record_id')->nullable();
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        // 2. Media Folders
        Schema::create('media_folders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('parent_id')->nullable()->constrained('media_folders')->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // 3. Media Library
        Schema::create('media_library', function (Blueprint $table) {
            $table->id();
            $table->foreignId('folder_id')->nullable()->constrained('media_folders')->onDelete('set null');
            $table->string('name');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('mime_type');
            $table->bigInteger('file_size');
            $table->enum('file_type', ['image', 'video', 'audio', 'pdf', 'document']);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // 4. Saints
        Schema::create('saints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('saints')->onDelete('set null'); // Guru Parampara
            $table->json('name'); // Hindi, English, Sanskrit
            $table->json('title'); // Hindi, English, Sanskrit
            $table->string('photo')->nullable();
            $table->json('introduction')->nullable();
            $table->json('biography')->nullable();
            $table->json('guru_name')->nullable();
            $table->date('diksha_date')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->json('address')->nullable();
            
            // Global Audit Columns
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('show_on_website')->default(true)->index();
            $table->boolean('show_on_app')->default(true)->index();
            $table->integer('display_order')->default(0)->index();
            
            // SEO Fields
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();
            
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });

        // 5. Vihars
        Schema::create('vihars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('saint_id')->constrained('saints')->onDelete('cascade');
            $table->json('location_title');
            $table->json('address')->nullable();
            $table->string('city')->index();
            $table->string('state')->index();
            $table->string('country')->default('India');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('google_map_link')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_number')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('type', ['current', 'upcoming', 'previous'])->default('current')->index();

            // Global Audit Columns
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('show_on_website')->default(true)->index();
            $table->boolean('show_on_app')->default(true)->index();
            $table->integer('display_order')->default(0)->index();

            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });

        // 6. Content Types
        Schema::create('content_types', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();

            // Global Audit Columns
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('show_on_website')->default(true)->index();
            $table->boolean('show_on_app')->default(true)->index();
            $table->integer('display_order')->default(0)->index();

            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });

        // 7. Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_type_id')->constrained('content_types')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->json('name');
            $table->string('slug')->index();
            $table->json('description')->nullable();

            // Global Audit Columns
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('show_on_website')->default(true)->index();
            $table->boolean('show_on_app')->default(true)->index();
            $table->integer('display_order')->default(0)->index();

            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['content_type_id', 'slug']);
        });

        // 8. Contents
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_type_id')->constrained('content_types')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->foreignId('sub_category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->json('title');
            $table->string('slug')->unique();
            $table->json('author')->nullable();
            $table->date('publish_date')->index();
            $table->json('short_description')->nullable();
            $table->json('full_description')->nullable();
            $table->string('featured_image')->nullable();
            $table->json('media_gallery')->nullable(); // Gallery media IDs
            $table->json('videos')->nullable(); // YouTube URLs
            $table->json('audios')->nullable(); // Audio paths
            $table->json('pdfs')->nullable(); // PDF paths
            $table->json('tags')->nullable(); // JSON Array of tags
            $table->string('status')->default('draft')->index(); // draft, published

            // Global Audit Columns
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('show_on_website')->default(true)->index();
            $table->boolean('show_on_app')->default(true)->index();
            $table->integer('display_order')->default(0)->index();

            // SEO
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });

        // 9. Albums
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('cover_image')->nullable();
            $table->json('category')->nullable();
            $table->json('description')->nullable();

            // Global Audit Columns
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('show_on_website')->default(true)->index();
            $table->boolean('show_on_app')->default(true)->index();
            $table->integer('display_order')->default(0)->index();

            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });

        // 10. Album Photos
        Schema::create('album_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_id')->constrained('albums')->onDelete('cascade');
            $table->string('photo_path');
            $table->json('caption')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->integer('display_order')->default(0)->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // 11. Events
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->json('event_name');
            $table->string('banner')->nullable();
            $table->json('description')->nullable();
            $table->json('venue');
            $table->dateTime('start_date')->index();
            $table->dateTime('end_date')->nullable();

            // Global Audit Columns
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('show_on_website')->default(true)->index();
            $table->boolean('show_on_app')->default(true)->index();
            $table->integer('display_order')->default(0)->index();

            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });

        // 12. Panchangs
        Schema::create('panchangs', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique()->index();
            $table->string('tithi'); // e.g., एकादशी
            $table->string('paksha'); // e.g., शुक्ल पक्ष
            $table->string('maas'); // e.g., आषाढ़
            $table->string('nakshatra')->nullable();
            $table->string('sunrise')->nullable();
            $table->string('sunset')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // 13. Festivals
        Schema::create('festivals', function (Blueprint $table) {
            $table->id();
            $table->json('festival_name');
            $table->date('festival_date')->index();
            $table->json('description')->nullable();
            $table->string('image')->nullable();

            // Global Audit Columns
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('show_on_website')->default(true)->index();
            $table->boolean('show_on_app')->default(true)->index();
            $table->integer('display_order')->default(0)->index();

            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });

        // 14. Pages
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->string('slug')->unique();
            $table->json('content');
            $table->string('featured_image')->nullable();

            // Global Audit Columns
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('show_on_website')->default(true)->index();
            $table->boolean('show_on_app')->default(true)->index();
            $table->integer('display_order')->default(0)->index();

            // SEO
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });

        // 15. Menus
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->unique()->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // 16. Menu Items
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('menu_items')->onDelete('cascade');
            $table->json('label');
            $table->string('url');
            $table->enum('type', ['internal', 'external'])->default('internal');
            $table->integer('display_order')->default(0)->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // 17. Homepage Sections
        Schema::create('homepage_sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_key')->unique();
            $table->json('label');
            $table->boolean('is_active')->default(true)->index();
            $table->integer('display_order')->default(0)->index();
            $table->boolean('show_on_website')->default(true)->index();
            $table->boolean('show_on_app')->default(true)->index();
            $table->json('settings')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // 18. Settings
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->index();
            $table->text('value')->nullable();
            $table->string('type')->default('text');
            $table->string('group')->default('general');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('homepage_sections');
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('festivals');
        Schema::dropIfExists('panchangs');
        Schema::dropIfExists('events');
        Schema::dropIfExists('album_photos');
        Schema::dropIfExists('albums');
        Schema::dropIfExists('contents');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('content_types');
        Schema::dropIfExists('vihars');
        Schema::dropIfExists('saints');
        Schema::dropIfExists('media_library');
        Schema::dropIfExists('media_folders');
        Schema::dropIfExists('activity_logs');
    }
};
