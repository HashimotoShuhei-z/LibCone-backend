<?php

namespace App\Services;

use App\Http\Requests\InternalBook\CreateInternalBookRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\CompanyBook;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;
use Exception;

class InternalBookService
{
    /**
     * 社内書籍の一覧取得
     *
     * @param array<string, mixed> $filters
     * @param int $company_id
     * @return Collection<int, CompanyBook>
     */
    public function getInternalBookList(array $filters, int $company_id): Collection
    {
        $query = CompanyBook::query()
            ->with(['book', 'reviews', 'book.bookGenres', 'book.author'])
            ->where('company_id', $company_id);

        // 書籍名による部分一致検索
        if (! empty($filters['book_name'])) {
            $query->whereHas('book', function ($q) use ($filters) {
                $q->where('book_title', 'like', '%' . $filters['book_name'] . '%');
            });
        }

        // 書籍ジャンルIDによるフィルタリング
        if (! empty($filters['book_genre_id'])) {
            $query->whereHas('book.bookGenres', function ($q) use ($filters) {
                $q->where('book_genre_id', $filters['book_genre_id']);
            });
        }

        return $query->get();
    }

    /**
     * 社内書籍の詳細取得
     *
     * @param CompanyBook $company_book
     * @return Collection<int, CompanyBook>
     */
    public function getInternalBookItem(CompanyBook $company_book): Collection
    {
        return $company_book->with(['book', 'reviews'])->get();
    }

    /**
     * 社内書籍の登録処理
     *
     * @param CreateInternalBookRequest $data
     * @param int $company_id
     * @return CompanyBook
     */
    public function createBook(CreateInternalBookRequest $data, int $company_id)
    {
        //
        // ISBNから既存の書籍を検索
        $existing_book = Book::where('isbn', $data['isbn'])->first();

        if ($existing_book) {
            // 書籍が既に存在する場合、その書籍IDでCompanyBookに登録（同じ組み合わせがない場合のみ）
            return CompanyBook::firstOrCreate([
                'company_id' => $company_id,
                'book_id' => $existing_book->id,
                'in_office' => false,
            ]);
        }

        // 書籍が存在しない場合、楽天Books APIからデータを取得
        $book_data = $this->fetchBookDataFromRakuten($data['isbn']);

        if (! $book_data) {
            throw new Exception('書籍が見つかりませんでした。');
        }

        // 書籍情報をbooksテーブルに新規登録
        $new_book = Book::create([
            'isbn' => $book_data['isbn'],
            'book_title' => $book_data['title'],
            'book_publisher' => $book_data['publisher'],
            'book_image' => $book_data['image_url'],
            'author_id' => $this->getOrCreateAuthorId($book_data['author']), // 著者のID取得
        ]);

        // 新規書籍をcompanies_booksテーブルにも登録
        return CompanyBook::create([
            'company_id' => $company_id,
            'book_id' => $new_book->id,
            'in_office' => false,
        ]);
    }

    /**
     * 楽天Books APIから書籍情報を取得
     *
     * @param string $isbn
     * @return array<string, string>|null
     */
    protected function fetchBookDataFromRakuten(string $isbn): array|null
    {
        $api_key = config('services.rakutenBookApi.key');  // .envからAPIキー取得
        $response = Http::get('https://app.rakuten.co.jp/services/api/BooksBook/Search/20170404', [
            'format' => 'json',
            'isbn' => $isbn,
            'applicationId' => $api_key,
        ]);

        if ($response->successful() && ! empty($response['Items'])) {
            $item = $response['Items'][0]['Item'];
            return [
                'isbn' => $item['isbn'],
                'title' => $item['title'],
                'publisher' => $item['publisherName'],
                'image_url' => $item['largeImageUrl'],
                'author' => $item['author'],
            ];
        }

        return null; // 書籍が見つからなかった場合
    }

    /**
     * 著者名から著者を検索、または新規作成
     *
     * @param string $author_name
     * @return int
     */
    protected function getOrCreateAuthorId($author_name): int
    {
        return Author::firstOrCreate(['author_name' => $author_name])->id;
    }

    /**
     * isbnから社内書籍を検索
     *
     * @param string $isbn
     * @return CompanyBook|null
     */
    public function findBookByIsbn(string $isbn): ?CompanyBook
    {
        return CompanyBook::with(['book.author', 'book.bookGenres'])
            ->whereHas('book', function ($query) use ($isbn) {
                $query->where('isbn', $isbn);
            })
            ->where('in_office', true)
            ->first();
    }
}
