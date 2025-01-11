<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Company;
use App\Models\CompanyBook;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class InternalBookTest extends TestCase
{
    use RefreshDatabase;

    protected string $adminToken;
    protected string $userToken;

    protected function setUp(): void
    {
        parent::setUp();

        // 楽天Books APIのモック
        Http::fake([
            'https://app.rakuten.co.jp/*' => Http::response([
                'Items' => [
                    ['Item' => [
                        'isbn' => '9781234567897',
                        'title' => 'Test Book',
                        'publisherName' => 'Test Publisher',
                        'largeImageUrl' => 'https://example.com/image.jpg',
                        'author' => 'Test Author',
                    ]]
                ]
            ], 200),
        ]);

        // 管理者ユーザーの作成とトークン生成
        $this->adminToken = User::factory()->create(['type_id' => 1])
            ->createToken('authToken', ['admin'])->plainTextToken;

        // 一般ユーザーの作成とトークン生成
        $this->userToken = User::factory()->create(['type_id' => 0])
            ->createToken('authToken', ['user'])->plainTextToken;

        // テストデータ生成
        $this->setUpTestData();
    }

    /**
     * テスト用データ生成
     */
    protected function setUpTestData(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->id]);

        $company = Company::factory()->create();
        CompanyBook::factory()->count(3)->create([
            'company_id' => $company->id,
            'book_id' => $book->id,
            'in_office' => true,
        ]);
    }

    /** @test */
    public function test_internal_book_list_as_authenticated_user(): void
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->userToken}")
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

        $response = $this->withHeader('Authorization', "Bearer {$this->userToken}")
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
                     'reviews',
                 ]);
    }

    /** @test */
    public function test_create_internal_book_as_admin(): void
    {
        $bookData = [
            'isbn' => '9781234567897',
        ];

        $response = $this->withHeader('Authorization', "Bearer {$this->adminToken}")
                         ->postJson('/api/internal-books', $bookData);

        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'Book created',
                 ]);

        $this->assertDatabaseHas('books', ['isbn' => '9781234567897']);
    }

    /** @test */
    public function test_create_internal_book_as_non_admin(): void
    {
        $bookData = [
            'isbn' => '9781234567897',
        ];

        $response = $this->withHeader('Authorization', "Bearer {$this->userToken}")
                         ->postJson('/api/internal-books', $bookData);

        $response->assertStatus(403);
    }

    /** @test */
    public function test_delete_internal_book_as_admin(): void
    {
        $companyBook = CompanyBook::first();

        $response = $this->withHeader('Authorization', "Bearer {$this->adminToken}")
                         ->deleteJson("/api/internal-books/{$companyBook->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('companies_books', ['id' => $companyBook->id]);
    }

    /** @test */
    public function test_delete_internal_book_as_non_admin(): void
    {
        $companyBook = CompanyBook::first();

        $response = $this->withHeader('Authorization', "Bearer {$this->userToken}")
                         ->deleteJson("/api/internal-books/{$companyBook->id}");

        $response->assertStatus(403);
    }
}
