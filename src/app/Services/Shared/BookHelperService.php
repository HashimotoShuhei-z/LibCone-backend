<?php

namespace App\Services\Shared;

use App\Models\Author;
use Illuminate\Support\Facades\Http;

class BookHelperService
{
    /**
     * 楽天Books APIから書籍情報を取得
     *
     * @param string $isbn
     * @return array<string, string>|null
     */
    public function fetchBookDataFromRakuten(string $isbn): ?array
    {
        $apiKey = config('services.rakutenBookApi.key'); // .envからAPIキー取得
        $response = Http::get('https://app.rakuten.co.jp/services/api/BooksBook/Search/20170404', [
            'format' => 'json',
            'isbn' => $isbn,
            'applicationId' => $apiKey,
        ]);

        if ($response->successful() && !empty($response['Items'])) {
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
     * @param string $authorName
     * @return int
     */
    public function getOrCreateAuthorId(string $authorName): int
    {
        return Author::firstOrCreate(['author_name' => $authorName])->id;
    }
}
