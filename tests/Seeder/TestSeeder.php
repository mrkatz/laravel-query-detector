<?php

namespace Mrkatz\QueryDetector\Tests\Seeder;

use Illuminate\Database\Seeder;
use Mrkatz\QueryDetector\Tests\Models\Post;
use Mrkatz\QueryDetector\Tests\Models\Author;
use Mrkatz\QueryDetector\Tests\Models\Profile;
use Mrkatz\QueryDetector\Tests\Models\Comment;

class TestSeeder extends Seeder
{
    public function run()
    {
        $authors = factory(Author::class, 5)->create();

        $authors->each(function ($author) {
            factory(Profile::class)->create([
                'author_id' => $author->id
            ]);

            $posts = factory(Post::class, 5)->create([
                'author_id' => $author->id
            ]);

            $posts->each(function ($post) {
                factory(Comment::class, 2)->create([
                    'commentable_id' => $post->id,
                    'commentable_type' => Post::class,
                ]);
            });
        });
    }
}
