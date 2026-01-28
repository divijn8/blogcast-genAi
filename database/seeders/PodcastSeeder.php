namespace Database\Seeders;

use App\Models\Podcast;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PodcastSeeder extends Seeder
{
    public function run()
    {
        // Podcast specific category
        $category = Category::updateOrCreate(
            ['slug' => 'tech-talks'],
            ['name' => 'Tech Talks', 'type' => 'podcast']
        );

        $user = User::first() ?? User::factory()->create();

        Podcast::create([
            'title' => 'AI Revolution in Mumbai',
            'slug' => Str::slug('AI Revolution in Mumbai'),
            'description' => 'A deep discussion on how AI is changing local businesses.',
            'category_id' => $category->id,
            'author_id' => $user->id,
            'audio_path' => 'podcasts/sample.mp3',
            'thumbnail' => 'podcasts/thumb.jpg',
            'duration' => 300,
            'published_at' => now(),
            'script_json' => [
                ['speaker' => 'Aryan (Host)', 'text' => 'Namaste! Welcome to BlogCast. Today we are talking about AI.'],
                ['speaker' => 'Sara (Expert)', 'text' => 'Hi Aryan, glad to be here. AI in India is growing rapidly.'],
                ['speaker' => 'Aryan (Host)', 'text' => 'Sahi baat hai. But what about small startups?'],
                ['speaker' => 'Sara (Expert)', 'text' => 'Startups are leveraging Groq and ElevenLabs to scale fast!'],
            ],
        ]);
    }
}
