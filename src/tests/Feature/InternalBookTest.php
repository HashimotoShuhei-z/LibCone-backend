<?php

namespace Tests\Feature;

use App\Models\CompanyBook;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InternalBookTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $normalUser;

    protected function setUp(): void
    {
        parent::setUp();

        // 管理者ユーザーと一般ユーザーを作成
        $this->adminUser = User::factory()->create(['abilities' => 'admin']);
        $this->normalUser = User::factory()->create(['abilities' => 'user']);

        // テスト用の社内書籍を生成
        CompanyBook::factory()->count(3)->create([
            'company_id' => $this->adminUser->company_id,
        ]);
    }

    /** @test */
    public function test_internal_book_list_as_authenticated_user(): void
    {
        $response = $this->actingAs($this->normalUser)
                         ->getJson('/api/internal-books');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => [
                         'companyBookId',
                         'bookName',
                         'bookGenreName',
                         'bookImage',
                         'bookPublisher',
                         'authorId',
                         'authorName',
                         'averageReviewRate',
                         'rentalInformation',
                     ],
                 ]);
    }

    /** @test */
    public function test_internal_book_item_as_authenticated_user(): void
    {
        $companyBook = CompanyBook::first();

        $response = $this->actingAs($this->normalUser)
                         ->getJson("/api/internal-books/{$companyBook->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'book' => [
                         'companyBookId',
                         'bookName',
                         'bookGenreName',
                         'bookImage',
                         'bookPublisher',
                         'authorId',
                         'authorName',
                         'averageReviewRate',
                         'rentalInformation',
                     ],
                     'reviews' => [],
                 ]);
    }

    /** @test */
    public function test_create_internal_book_as_admin(): void
    {
        $bookData = [
            'isbn' => '9781234567897',
        ];

        $response = $this->actingAs($this->adminUser)
                         ->postJson('/api/internal-books', $bookData);

        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'Book created',
                 ]);

        $this->assertDatabaseHas('books', ['isbn' => '9781234567897']);
        $this->assertDatabaseHas('companies_books', [
            'company_id' => $this->adminUser->company_id,
            'in_office' => false,
        ]);
    }

    /** @test */
    public function test_create_internal_book_as_non_admin(): void
    {
        $bookData = [
            'isbn' => '9781234567897',
        ];

        $response = $this->actingAs($this->normalUser)
                         ->postJson('/api/internal-books', $bookData);

        $response->assertStatus(403); // 一般ユーザーにはアクセス権限がない
    }

    /** @test */
    public function test_delete_internal_book_as_admin(): void
    {
        $companyBook = CompanyBook::first();

        $response = $this->actingAs($this->adminUser)
                         ->deleteJson("/api/internal-books/{$companyBook->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('companies_books', [
            'id' => $companyBook->id,
        ]);
    }

    /** @test */
    public function test_delete_internal_book_as_non_admin(): void
    {
        $companyBook = CompanyBook::first();

        $response = $this->actingAs($this->normalUser)
                         ->deleteJson("/api/internal-books/{$companyBook->id}");

        $response->assertStatus(403); // 一般ユーザーにはアクセス権限がない
    }
}
