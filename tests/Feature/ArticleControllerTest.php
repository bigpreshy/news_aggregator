<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_filtered_articles()
    {
        // Use factory directly instead of seeder
        \App\Models\Article::factory()->create(['source_name' => 'The Guardian']);
        \App\Models\Article::factory()->create(['source_name' => 'New York Times']);

        // Test source filter
        $response = $this->getJson('/api/articles?sources[]=The Guardian');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.source_name', 'The Guardian');
    }

    /** @test */
    public function it_validates_request_parameters()
    {
        // Test invalid date format
        $this->getJson('/api/articles?start_date=invalid')
            ->assertStatus(422)
            ->assertJsonValidationErrors(['start_date']);

        // Test invalid category
        $this->getJson('/api/articles?categories[]=invalid')
            ->assertStatus(422)
            ->assertJsonValidationErrors(['categories.0']);
    }

    /** @test */
    public function it_handles_empty_results()
    {
        $this->getJson('/api/articles?sources[]=UnknownSource')
            ->assertOk()
            ->assertJsonCount(0, 'data');
    }
}