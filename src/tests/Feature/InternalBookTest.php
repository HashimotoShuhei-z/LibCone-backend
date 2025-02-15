<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Company;
use App\Models\CompanyBook;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InternalBookTest extends TestCase
{
    use RefreshDatabase;

    protected string $adminToken;
    protected string $userToken;
    protected Company $company; // テスト用会社

    protected function setUp(): void
    {
        parent::setUp();

        // テスト用の会社を作成
        $this->company = Company::factory()->create();

        // 管理者ユーザーの作成とトークン生成
        $this->adminToken = User::factory()->create([
            'type_id' => 1, // 管理者
            'company_id' => $this->company->id, // 管理者を会社に紐付け
        ])->createToken('authToken', ['admin'])->plainTextToken;

        // 一般ユーザーの作成とトークン生成
        $this->userToken = User::factory()->create([
            'type_id' => 0, // 一般ユーザー
            'company_id' => $this->company->id, // 一般ユーザーを会社に紐付け
        ])->createToken('authToken', ['user'])->plainTextToken;

        // テストデータ生成
        $this->setUpTestData();
    }

    /**
     * テスト用データ生成
     */
    protected function setUpTestData(): void
    {
        $author = Author::factory()->create();

        $book = Book::factory()->create([
            'isbn' => '9781234567897',
            'author_id' => $author->id,
        ]);

        CompanyBook::factory()->create([
            'company_id' => $this->company->id,
            'book_id' => $book->id,
            'in_office' => true,
        ]);
    }

    public function test_正常系_ユーザーによる社内書籍の一覧取得(): void
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->userToken}")
                        ->getJson('/api/internal-books');

        $response->assertStatus(200);

        // TODO:平均評価のキーカラムについてのテストはまだ未実装
        $response->assertJsonFragment([
            'companyBookId' => CompanyBook::first()->id,
            'bookName' => 'Test Book',
            'bookPublisher' => 'Test Publisher',
            'bookImage' => 'https://example.com/image.jpg',
            'authorName' => 'Test Author',
            'rentalInformation' => true,
        ]);
    }

    public function test_正常系_管理者による社内書籍の一覧取得(): void
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->adminToken}")
                        ->getJson('/api/internal-books');

        $response->assertStatus(200);

        // TODO:平均評価のキーカラムについてのテストはまだ未実装
        $response->assertJsonFragment([
            'companyBookId' => CompanyBook::first()->id,
            'bookName' => 'Test Book',
            'bookPublisher' => 'Test Publisher',
            'bookImage' => 'https://example.com/image.jpg',
            'authorName' => 'Test Author',
            'rentalInformation' => true,
        ]);
    }

    public function test_異常系_トークン無しで社内書籍の一覧取得(): void
    {
        $response = $this->getJson('/api/internal-books');

        $response->assertStatus(401);
    }

    public function test_正常系_ユーザーによる社内書籍の詳細取得(): void
    {
        $companyBook = CompanyBook::first();

        $response = $this->withHeader('Authorization', "Bearer {$this->userToken}")
                         ->getJson("/api/internal-books/{$companyBook->id}");
    
        $response->assertStatus(200);
    
        $response->assertJson([
            [
                'book' => [
                    'companyBookId' => $companyBook->id,
                    'bookName' => 'Test Book',
                    'bookPublisher' => 'Test Publisher',
                    'bookImage' => 'https://example.com/image.jpg',
                    'authorName' => 'Test Author',
                    'rentalInformation' => true,
                ],
                'reviews' => [],
            ],
        ]);
    }

    public function test_正常系_管理者による社内書籍の詳細取得(): void
    {
        $companyBook = CompanyBook::first();

        $response = $this->withHeader('Authorization', "Bearer {$this->adminToken}")
                         ->getJson("/api/internal-books/{$companyBook->id}");
    
        $response->assertStatus(200);
    
        $response->assertJson([
            [
                'book' => [
                    'companyBookId' => $companyBook->id,
                    'bookName' => 'Test Book',
                    'bookPublisher' => 'Test Publisher',
                    'bookImage' => 'https://example.com/image.jpg',
                    'authorName' => 'Test Author',
                    'rentalInformation' => true,
                ],
                'reviews' => [],
            ],
        ]);
    }

    public function test_異常系_トークン無しで社内書籍の詳細取得(): void
    {
        $companyBook = CompanyBook::first();

        $response = $this->getJson("/api/internal-books/{$companyBook->id}");

        $response->assertStatus(401);
    }

    public function test_異常系_存在しない社内書籍の詳細取得(): void
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->userToken}")
                         ->getJson("/api/internal-books/0");

        $response->assertStatus(404);
    }

    public function test_正常系_管理者による社内書籍の作成(): void
    {
        $bookData = ['isbn' => '9784873115658']; // リーダブルコードのisbnを指定

        $response = $this->withHeader('Authorization', "Bearer {$this->adminToken}")
                        ->postJson('/api/internal-books', $bookData);

        $response->assertStatus(201)
                ->assertJson(['message' => 'Book created']);

        $this->assertDatabaseHas('books', [
            'isbn' => '9784873115658',
            'book_title' => 'リーダブルコード',
        ]);
    }

    public function test_正常系_指定したisbnが既に書籍テーブルに存在した場合の社内書籍の作成(): void
    {
        $existed_book = Book::factory()->create([
            'isbn' => '9784873115658',
            'author_id' => Author::factory(),
        ]);

        $bookData = ['isbn' => '9781234567897'];

        $response = $this->withHeader('Authorization', "Bearer {$this->adminToken}")
                        ->postJson('/api/internal-books', $bookData);

        $response->assertStatus(201)
                ->assertJson(['message' => 'Book created']);

        $created_company_book = CompanyBook::where('company_id', $this->company->id)
                                           ->latest()
                                           ->first();

        $this->assertDatabaseHas('companies_books', [
            'company_id' => $this->company->id,
            'book_id' => $created_company_book->id,
            'in_office' => false,
        ]);
    }

    public function test_異常系_ユーザーによる社内書籍の作成(): void
    {
        $bookData = [
            'isbn' => '9781234567897',
        ];

        $response = $this->withHeader('Authorization', "Bearer {$this->userToken}")
                         ->postJson('/api/internal-books', $bookData);

        $response->assertStatus(403);
    }

    public function test_異常系_指定したisbnが楽天BooksAPIにて見つからなかった場合の社内書籍の作成(): void
    {
        // 例外がハンドリングされずにそのままスローされるようにする
        $this->withoutExceptionHandling();
        
        // 例外が発生することを期待する
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('書籍が見つかりませんでした。');

        $bookData = [
            'isbn' => '9999999999999',
        ];

        $this->withHeader('Authorization', "Bearer {$this->adminToken}")
             ->postJson('/api/internal-books', $bookData);
    }

    public function test_異常系_トークン無しで社内書籍の作成(): void
    {
        $bookData = [
            'isbn' => '9781234567897',
        ];

        $response = $this->postJson('/api/internal-books', $bookData);

        $response->assertStatus(401);
    }


    public function test_正常系_管理者による社内書籍の削除(): void
    {
        $companyBook = CompanyBook::first();

        $response = $this->withHeader('Authorization', "Bearer {$this->adminToken}")
                         ->deleteJson("/api/internal-books/{$companyBook->id}");

        $response->assertStatus(204);

        $this->assertSoftDeleted('companies_books', ['id' => $companyBook->id]);
    }

    public function test_異常系_ユーザーによる社内書籍の削除(): void
    {
        $companyBook = CompanyBook::first();

        $response = $this->withHeader('Authorization', "Bearer {$this->userToken}")
                         ->deleteJson("/api/internal-books/{$companyBook->id}");

        $response->assertStatus(403);
    }

    public function test_異常系_トークン無しで社内書籍の削除(): void
    {
        $companyBook = CompanyBook::first();

        $response = $this->deleteJson("/api/internal-books/{$companyBook->id}");

        $response->assertStatus(401);
    }

}
