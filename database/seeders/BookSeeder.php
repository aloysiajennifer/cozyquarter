<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // title_book	author_book	isbn_book	synopsis_book	cover_book	status_book	id_category	id_shelf	created_at	updated_at	
            ['title_book' => 'Harry Potter and the Deathly Hallows', 'author_book' => 'J.K. Rowling', 'isbn_book' => '978-1408855713', 
                'synopsis_book' => 'Harry Potter and the Deathly Hallows follows Harry, Ron, and Hermione as they leave Hogwarts to find and destroy Voldemort’s Horcruxes. Along the way, they learn about the powerful Deathly Hallows. The story ends with the Battle of Hogwarts, where Harry sacrifices himself but survives and defeats Voldemort. Peace is restored, and the series concludes with a glimpse into the future, showing the heroes as adults.',
                'cover_book' => 'images/covers/harry potter 7.jpg', 'status_book' => 1, 'id_category' => 4, 'id_shelf' => 1],
            ['title_book' => 'Crazy Rich Asians', 'author_book' => 'Kevin Kwan', 'isbn_book' => '978-0593310908', 
                'synopsis_book' => 'Crazy Rich Asians is about Rachel Chu, who visits Singapore with her boyfriend, Nick Young, only to discover he’s from an ultra-wealthy family. Thrown into a world of luxury and judgment, Rachel must face Nick’s disapproving mother and the pressures of high-society life.',
                'cover_book' => 'images/covers/crazy rich asians.jpg', 'status_book' => 1, 'id_category' => 2, 'id_shelf' => 3],
            ['title_book' => 'Matahari', 'author_book' => 'Tere Liye', 'isbn_book' => '978620332116', 
                'synopsis_book' => 'Matahari karya Tere Liye bercerita tentang Ali dan dua temannya, Raib dan Seli, yang menjelajahi dunia paralel bernama Klan Matahari. Mereka menghadapi berbagai tantangan demi menyelamatkan dunia itu dari ancaman besar.',
                'cover_book' => 'images/covers/matahari.jpg', 'status_book' => 1, 'id_category' => 4, 'id_shelf' => 5],
        ];

        foreach ($data as $item) {
            $book = new Book();
            $book->title_book = $item['title_book'];
            $book->author_book = $item['author_book'];
            $book->isbn_book = $item['isbn_book'];
            $book->synopsis_book = $item['synopsis_book'];
            $book->cover_book = $item['cover_book'];
            $book->status_book = $item['status_book'];
            $book->id_category = $item['id_category'];
            $book->id_shelf = $item['id_shelf'];
            $book->save();
        }
    }
}
