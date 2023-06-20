<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Url;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class UrlTest extends TestCase
{
    use RefreshDatabase;

    // URL一覧画面にアクセスすると、URL一覧が表示される
    public function testIndex()
    {
        // Create a new URL instance
        $url = Url::factory()->create();
        $user = User::factory()->create();

        // Call the index method
        $response = $this->actingAs($user)->get("/urls");

        // Assert that the response status is 200
        $response->assertStatus(200);

        // Assert that the response contains the created URL data
        $response->assertSee($url->title);
        $response->assertSee($url->description);
    }

    // URLを作成するとデータベースに保存される
    public function testStore()
    {
        $user = User::factory()->create();
        $urlData = [
            "url" => "https://example.com",
            "title" => "Example Website",
            "description" => "This is an example website.",
            "favicon" => "https://example.com/favicon.ico",
        ];
        
        // $urlDataを使ってPOSTリクエストを送信
        $response = $this->actingAs($user)->post("/urls", [
            "url" => $urlData["url"],
        ]);
        
        $response->assertStatus(302);
        
        $this->assertDatabaseHas("urls", [
            "url" => $urlData["url"],
        ]);
        
    }

    // 何も入力しないで送信した場合にエラーになる
    public function testStoreFailsWithoutUrl()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post("/urls", [
            // URLの入力を空にする
        ]);

        $response->assertSessionHasErrors("url");
    }

    // 不正なURLを入力した場合にエラーになる
    public function testStoreFailsWithInvalidUrl()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post("/urls", [
            "url" => "not a valid url",
        ]);

        $response->assertSessionHasErrors("url");
    }

    // 既に存在するURLを入力した場合にエラーになる
    public function testStoreFailsWithDuplicateUrl()
    {
        $user = User::factory()->create();

        // 既に存在するURLを作成
        $existingUrl = Url::factory()->create([
            "url" => "https://example.com",
        ]);

        $response = $this->actingAs($user)->post("/urls", [
            "url" => "https://example.com",
        ]);

        $response->assertSessionHasErrors("url");
    }

}