<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookPurchaseRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Exception;

class BookPurchaseRequestTest extends TestCase
{
    use RefreshDatabase;

    protected string $adminToken;
    protected string $userToken;
    protected Company $company;

    protected function setUp(): void
    {
        parent::setUp();

        // テスト用の会社を作成
        $this->company = Company::factory()->create();

        // 管理者ユーザーの作成とトークン生成
        $admin = User::factory()->create([
            'type_id' => 1, // 管理者
            'company_id' => $this->company->id,
        ]);
        $this->adminToken = $admin->createToken('authToken', ['admin'])->plainTextToken;

        // 一般ユーザーの作成とトークン生成
        $user = User::factory()->create([
            'type_id' => 0, // 一般ユーザー
            'company_id' => $this->company->id,
        ]);
        $this->userToken = $user->createToken('authToken', ['user'])->plainTextToken;
    }

    /**
     * 書籍購入リクエスト一覧取得（管理者のみ）
     */
    public function test_正常系_書籍購入リクエスト一覧取得(): void
    {
        // 事前に購入リクエストを作成しておく
        $user = User::factory()->create(['company_id' => $this->company->id]);
        $book = Book::factory()->create([
            'isbn' => '1234567890123',
            'author_id' => Author::factory(),
        ]);
        $purchase_request = BookPurchaseRequest::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'purchase_type' => 0,
            'hope_deliver_at' => Carbon::now()->addDays(7)->toDateString(),
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->adminToken}")
                         ->getJson('/api/book-purchase-requests');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'title'          => 'Test Book',
            'itemPrice'      => 1000,
            'itemUrl'        => null,
            'userId'         => $user->id,
            'userName'       => $user->name,
            'userIcon'       => null,
            'purchaseType'   => 0,
            'hopeDeliverAt'  => $purchase_request->hope_deliver_at,
            'existInOffice'  => false,
            'purchaseStatus' => 0,
        ]);
    }

    /**
     * 書籍購入リクエスト作成（既存の書籍を利用）
     */
    public function test_正常系_書籍購入リクエスト作成_when_book_exists(): void
    {
        // 既存の書籍を作成
        $book = Book::factory()->create([
            'isbn' => '9781111111111',
            'author_id' => Author::factory(),
        ]);

        $data = [
            'isbn' => '9781111111111',
            'purchaseType' => 'online',
            'hopeDeliveryAt' => Carbon::now()->addDays(5)->toDateString(),
        ];

        $response = $this->withHeader('Authorization', "Bearer {$this->userToken}")
                         ->postJson('/api/book-purchase-requests', $data);

        $response->assertStatus(201)
                 ->assertJson(['message' => 'Made a purchase-request']);

        $this->assertDatabaseHas('book_purchase_requests', [
            'book_id' => $book->id,
            'purchase_type' => 'online',
        ]);
    }

    public function test_正常系_書籍テーブルに存在しない書籍の購入リクエスト作成(): void
    {
        $isbn = '9782222222222';

        $data = [
            'isbn' => $isbn,
            'purchaseType' => 0,
            'hopeDeliveryAt' => Carbon::now()->addDays(10)->toDateString(),
        ];

        $response = $this->withHeader('Authorization', "Bearer {$this->userToken}")
                         ->postJson('/api/book-purchase-requests', $data);

        $response->assertStatus(201)
                 ->assertJson(['message' => 'Made a purchase-request']);

        // 書籍が作成され、購入リクエストがDBに登録されていることを確認
        $book = Book::where('isbn', $isbn)->first();
        $this->assertNotNull($book);
        $this->assertDatabaseHas('book_purchase_requests', [
            'book_id' => $book->id,
            'purchase_type' => 'offline',
        ]);
    }

    /**
     * 書籍購入リクエスト作成時、外部APIで書籍が見つからない場合（例外）
     */
    public function test_異常系_書籍購入リクエスト作成_when_book_not_found_in_external_api(): void
    {
        $isbn = '9783333333333';

        $this->withoutExceptionHandling();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Book not found in external API');

        $data = [
            'isbn' => $isbn,
            'purchaseType' => 0,
            'hopeDeliveryAt' => Carbon::now()->addDays(10)->toDateString(),
        ];

        $this->withHeader('Authorization', "Bearer {$this->userToken}")
             ->postJson('/api/book-purchase-requests', $data);
    }

    /**
     * 書籍購入リクエスト削除
     */
    public function test_正常系_書籍購入リクエスト削除(): void
    {
        // 購入リクエストを事前に作成
        $book = Book::factory()->create([
            'isbn' => '9784444444444',
            'author_id' => Author::factory(),
        ]);
        $purchaseRequest = BookPurchaseRequest::factory()->create([
            'user_id' => User::factory()->create(['company_id' => $this->company->id])->id,
            'book_id' => $book->id,
            'purchase_type' => 'online',
            'hope_deliver_at' => Carbon::now()->addDays(7)->toDateString(),
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->userToken}")
                         ->deleteJson("/api/book-purchase-requests/{$purchaseRequest->id}");

        $response->assertStatus(204);
        $this->assertSoftDeleted('book_purchase_requests', ['id' => $purchaseRequest->id]);
    }

    /**
     * 一括購入確定処理（管理者のみ）
     */
    public function test_正常系_一括購入確定(): void
    {
        $book = Book::factory()->create([
            'isbn' => '9785555555555',
            'author_id' => Author::factory(),
        ]);
        $purchaseRequest1 = BookPurchaseRequest::factory()->create([
            'user_id' => User::factory()->create(['company_id' => $this->company->id])->id,
            'book_id' => $book->id,
            'purchase_type' => 0,
            'hope_deliver_at' => Carbon::now()->addDays(5)->toDateString(),
            'purchase_status' => 0,
        ]);
        $purchaseRequest2 = BookPurchaseRequest::factory()->create([
            'user_id' => User::factory()->create(['company_id' => $this->company->id])->id,
            'book_id' => $book->id,
            'purchase_type' => 0,
            'hope_deliver_at' => Carbon::now()->addDays(6)->toDateString(),
            'purchase_status' => 0,
        ]);

        $data = ['request_ids' => [$purchaseRequest1->id, $purchaseRequest2->id]];
        $response = $this->withHeader('Authorization', "Bearer {$this->adminToken}")
                         ->postJson('/api/book-purchase-requests/confirm', $data);

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Purchase requests confirmed'])
                 ->assertJsonFragment(['updated_count' => 2]);

        $this->assertDatabaseHas('book_purchase_requests', [
            'id' => $purchaseRequest1->id,
            'purchase_status' => 1,
        ]);
        $this->assertDatabaseHas('book_purchase_requests', [
            'id' => $purchaseRequest2->id,
            'purchase_status' => 1,
        ]);
    }

    /**
     * トークン無しの場合、書籍購入リクエスト作成で401が返る
     */
    public function test_異常系_トークン無しで書籍購入リクエスト作成(): void
    {
        $data = [
            'isbn' => '9786666666666',
            'purchaseType' => 'online',
            'hopeDeliveryAt' => Carbon::now()->addDays(7)->toDateString(),
        ];
        $response = $this->postJson('/api/book-purchase-requests', $data);
        $response->assertStatus(401);
    }

    /**
     * トークン無しの場合、書籍購入リクエスト一覧取得で401が返る
     */
    public function test_異常系_トークン無しで書籍購入リクエスト一覧取得(): void
    {
        $response = $this->getJson('/api/book-purchase-requests');
        $response->assertStatus(401);
    }
}
