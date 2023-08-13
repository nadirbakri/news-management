<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Models\News;
use Illuminate\Support\Facades\Storage;

class NewsRepository
{
    public function get($pagination)
    {
        return News::with('comments')->paginate($pagination);
    }

    public function create(array $data)
    {
        return News::create($data);
    }

    public function find($id)
    {
        return News::with('comments')->findOrFail($id);
    }

    public function update($id, array $data)
    {
        $news = $this->find($id);
        $news->update($data);
        return $news;
    }

    public function delete($id)
    {
        $news = $this->find($id);

        $news->comments()->delete();

        if ($news->image) {
            $imagePath = str_replace('/storage', 'public', $news->image);
            Storage::delete($imagePath);
        }

        $news->delete();

        return $news;
    }

    public function comment(array $data)
    {
        return Comment::create($data);
    }
}
