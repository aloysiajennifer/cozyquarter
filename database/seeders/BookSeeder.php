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
            ['title_book' => 'The Hunger Games', 'author_book' => 'Suzanne Collins', 'isbn_book' => '978-0439023481', 
                'synopsis_book' => 'In a dystopian future, the nation of Panem forces 24 teens to fight to the death in a televised event called the Hunger Games. Katniss Everdeen, a 16-year-old from District 12, volunteers to take her sister’s place. Thrown into a brutal arena, Katniss must rely on her instincts, skills, and alliances to survive—while navigating the politics of rebellion and the unexpected complexities of her feelings for fellow tribute Peeta Mellark.',
                'cover_book' => 'images/covers/the hunger games.jpg', 'status_book' => 1, 'id_category' => 3, 'id_shelf' => 2],
            ['title_book' => 'The Fault in Our Stars', 'author_book' => 'John Green', 'isbn_book' => '978-0142424179', 
                'synopsis_book' => 'Hazel Grace Lancaster, a 17-year-old cancer patient, attends a support group where she meets and falls in love with Augustus Waters, another cancer patient. The story explores their relationship, their individual struggles with cancer, and their journey to find meaning and purpose in a world where they know their time is limited.',
                'cover_book' => 'images/covers/the fault in our stars.jpg', 'status_book' => 1, 'id_category' => 2, 'id_shelf' => 3],
            ['title_book' => 'Harry Potter and the Cursed Child', 'author_book' => 'J.K. Rowling, Jack Thorne, and John Tiffany', 'isbn_book' => '978-1338216660', 
                'synopsis_book' => "The tale picked up 19 years after The Deathly Hallows as Harry Potter's son Albus tries to live up to his father's legacy on a time-hopping adventure with Draco Malfoy's son Scorpius.",
                'cover_book' => 'images/covers/hp cursed child.jpg', 'status_book' => 1, 'id_category' => 4, 'id_shelf' => 1],
            ['title_book' => 'Dilan: Dia Adalah Dilanku Tahun 1990', 'author_book' => 'Pidi Baiq', 'isbn_book' => '9786027870413', 
                'synopsis_book' => 'Sinopsis "Dilan: Dia adalah Dilanku Tahun 1990" oleh Pidi Baiq menceritakan kisah cinta antara Dilan dan Milea di Bandung tahun 1990. Milea, yang pindah dari Jakarta, bertemu dengan Dilan, seorang remaja anggota geng motor yang unik dan penuh kejutan. Hubungan mereka berkembang, menghadapi berbagai konflik dan tantangan, baik dari internal geng motor maupun lingkungan sekolah. ',
                'cover_book' => 'images/covers/dilan 1990.jpeg', 'status_book' => 1, 'id_category' => 2, 'id_shelf' => 7],
            ['title_book' => 'Laskar Pelangi', 'author_book' => 'Andrea Hirata', 'isbn_book' => '979-3062-79-7', 
                'synopsis_book' => 'Sinopsis novel Laskar Pelangi karya Andrea Hirata menceritakan kehidupan sepuluh anak yang berasal dari keluarga kurang mampu di desa Gantung, Pulau Belitung. Mereka tergabung dalam kelompok yang mereka sebut "Laskar Pelangi" dan berusaha keras untuk melanjutkan pendidikan di SD Muhammadiyah Gantung.',
                'cover_book' => 'images/covers/laskar pelangi.jpg', 'status_book' => 1, 'id_category' => 1, 'id_shelf' => 8],
            ['title_book' => 'Murder on the Orient Express', 'author_book' => 'Agatha Christie', 'isbn_book' => '978-0062073501', 
                'synopsis_book' => 'The story revolves around a fictional famous Belgian detective Hercule Poirot as he investigates a murder that occurs on the luxurious Orient Express train during its journey from Istanbul to Calais.',
                'cover_book' => 'images/covers/murder on the orient express.jpg', 'status_book' => 1, 'id_category' => 6, 'id_shelf' => 4],
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
