<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Interfaces\MoviesInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Movies;

class MoviesController extends Controller implements MoviesInterface
{
    public function add_movies(): mixed
    {
        return $this->load('add_movie_form', []);
    }

    public function save_movie(Request $request): mixed
    {
        $favourite = 0;
        if(!empty($request->favourite))
            $favourite = $request->favourite;

        $watched = 0;
        if(!empty($request->watched))
            $watched = $request->watched;

        $url = "";
        if(!empty($request->url))
            $url = $request->url;

        $movie = Movies::create([
            'name' => $request->title,
            'url' => $url,
            'favourite' => $favourite,
            'watched' => $watched,       
            'series' => null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
                
        if($movie->id != 0){
            return response()->json([
                'status' => 0,
                'msg' => 'Success.',
                'movieId' => $movie->id
            ]);
        }
        return response()->json([
            'status' => 2,
            'msg' => 'Fail to add.'
        ]);
    }

    public function edit_movie(int $movieId, Request $request): mixed
    {
        $favourite = $request->favourite;
        $watched = $request->watched;
        $url = "";

        if(!empty($request->url))
            $url = $request->url;

        $movie = Movie::find($movieId);
        $movie->name = $request->title;
        $movie->url = $url;
        $movie->favourite = $favourite;
        $movie->watched = $watched;
        $movie->series = $series;
        $movie->updated_at = date('Y-m-d H:i:s');

        if($movie->isDirty()){
            $movie->save();
        }

        if($movie->wasChanged()){
            return response()->json([
                'status' => 0,
                'msg' => 'Success.',
                'movieId' => $movie->id,
            ]);
        }
        return response()->json([
            'status' => 1,
            'msg' => 'Fail to add.'
        ]);
    }

    public function delete_movie(int $movieId, Request $request): mixed
    {
        $delMovie = Movies::find($movieId);
        $delMovie->delete();

        $delMovie = Movies::find($movieId);

        if(empty($delMovie->id)){
            return response()->json([
                'status' => 0,
                'msg' => 'Success.',
                'movieId' => $movie->id,
            ]);
        }
        return response()->json([
            'status' => 1,
            'msg' => 'Deleted fail.'
        ]);
    }

    public function movies(): mixed
    {
        $data['movies'] = Movies::get();
        return $this->load('movies', $data);
    }

    public function get_movie(int $movieId): mixed
    {
        $data['movie'] = Movies::find($movieId);
        return $this->load('edit_movie_form', $data);
    }

    public function list(): mixed
    {
        $listModel = new Movies();
        $data['lists'] = $listsModel->get_all();
        return $this->load('lists', $data);
    }

    public function get_list(int $listId): mixed
    {
        $listModel = new Movies();
        $data['list'] = $moviesModel->get($listId);
        return $this->load('edit_list_form', $data);
    }
}