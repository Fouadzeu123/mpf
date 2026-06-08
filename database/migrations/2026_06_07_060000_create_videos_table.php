<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // 'predication', 'prieres', 'temoignages'
            $table->string('title');
            $table->string('speaker');
            $table->text('description')->nullable();
            $table->string('bible_reference')->nullable();
            $table->string('video_url')->nullable();
            $table->string('video_path')->nullable();
            $table->unsignedInteger('likes')->default(0);
            $table->unsignedInteger('shares')->default(0);
            $table->unsignedInteger('comments_count')->default(0);
            $table->timestamps();
        });

        // Seed initial demo data matching the new singular 'predication' category
        DB::table('videos')->insert([
            [
                'category' => 'predication',
                'title' => 'La Puissance de la Foi agissante',
                'speaker' => 'Pasteur Moïse',
                'description' => 'Comment activer la foi pour surmonter les obstacles du quotidien. Un message d’édification puissant.',
                'bible_reference' => 'Hébreux 11:1',
                'video_url' => 'https://assets.mixkit.co/videos/preview/mixkit-pastor-reading-from-the-holy-bible-on-a-pulpit-41583-large.mp4',
                'video_path' => null,
                'likes' => 143,
                'shares' => 56,
                'comments_count' => 12,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'category' => 'prieres',
                'title' => 'Prière d’Intercession pour la Communauté',
                'speaker' => 'Ancienne Marie',
                'description' => 'Un moment d’intercession profonde pour les malades, les familles et la croissance spirituelle de notre église.',
                'bible_reference' => 'Jacques 5:16',
                'video_url' => 'https://assets.mixkit.co/videos/preview/mixkit-hands-of-a-man-praying-with-a-bible-41582-large.mp4',
                'video_path' => null,
                'likes' => 289,
                'shares' => 124,
                'comments_count' => 45,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'category' => 'temoignages',
                'title' => 'Témoignage de Guérison Miraculeuse',
                'speaker' => 'Frère Jean',
                'description' => 'Jean partage comment le Seigneur l’a relevé de la maladie après une prière de foi collective le mois dernier.',
                'bible_reference' => 'Psaume 103:3',
                'video_url' => 'https://assets.mixkit.co/videos/preview/mixkit-worship-leader-singing-with-hands-raised-41584-large.mp4',
                'video_path' => null,
                'likes' => 312,
                'shares' => 98,
                'comments_count' => 67,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'predication',
                'title' => 'Demeurer ferme dans la Tempête',
                'speaker' => 'Pasteur Moïse',
                'description' => 'Trouver la paix en Christ au milieu des épreuves de la vie. Analyse textuelle de la tempête apaisée.',
                'bible_reference' => 'Marc 4:39',
                'video_url' => 'https://assets.mixkit.co/videos/preview/mixkit-hands-of-a-man-praying-with-a-bible-41582-large.mp4',
                'video_path' => null,
                'likes' => 98,
                'shares' => 23,
                'comments_count' => 8,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'category' => 'temoignages',
                'title' => 'Reconnaissance et Action de Grâce',
                'speaker' => 'Sœur Chantal',
                'description' => 'Chantal élève sa voix pour célébrer la fidélité de Dieu dans ses études et son nouveau travail.',
                'bible_reference' => 'Psaume 136:1',
                'video_url' => 'https://assets.mixkit.co/videos/preview/mixkit-worship-leader-singing-with-hands-raised-41584-large.mp4',
                'video_path' => null,
                'likes' => 204,
                'shares' => 82,
                'comments_count' => 31,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
