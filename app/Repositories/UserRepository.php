<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserRepository
{
    public function get($pagination)
    {
        return User::with('newses')->with('comments')->paginate($pagination);
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function find($id)
    {
        return User::with('newses')->with('comments')->findOrFail($id);
    }

    public function update($id, array $data)
    {
        $user = $this->find($id);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        $user = $this->find($id);

        $user->comments()->delete();

        foreach ($user->newses as $news) {
            if ($news->image) {
                $imagePath = str_replace('/storage', 'public', $news->image);
                Storage::delete($imagePath);
            }

            $news->delete();
        }

        $user->delete();

        return $user;
    }
}
