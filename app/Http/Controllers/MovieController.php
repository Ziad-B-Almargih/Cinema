<?php

namespace App\Http\Controllers;

use App\Enums\ConsumableType;
use App\Enums\MovieType;
use App\Enums\Role;
use App\Http\Requests\Movie\StoreMovieRequest;
use App\Http\Requests\Movie\UpdateMovieRequest;
use App\Models\Actor;
use App\Models\Consumable;
use App\Models\Hall;
use App\Models\Movie;
use App\Models\Trailer;
use App\Services\MediaServices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MovieController extends Controller
{
    public function index()
    {
        $search = '%'.request()->input('search').'%';
        return view('movies.index')->with(
            'movies',
            Movie::select(['id', 'name', 'thumbnail', 'showing_date', 'start_time', 'end_time','type'])
                ->where('name', 'like', $search)
                ->when(Auth::user()->role === Role::USER, function ($q){
                    $q->where('showing_date', '>=', now());
                })
                ->paginate()
        );
    }

    public function create()
    {
        return view('movies.create')->with([
            'types' => MovieType::values(),
            'halls' => Hall::select(['id', 'name'])->get()
        ]);
    }

    public function store(StoreMovieRequest $request)
    {
        $data = $request->validated();
        return DB::transaction(function () use ($data){
            //check conflict
            if(
                Movie::where('hall_id', $data['hall_id'])
                    ->whereDate('showing_date',  $data['showing_date'])
                    ->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                    ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                    ->exists()
            ){
                return redirect()->route('movies.create')->with('error', 'There conflict in time');
            }


            if(isset($data['actors'])){
                $actors = $data['actors'];
                unset($data['actors']);
            }
            if(isset($data['trailers'])) {
                $trailers = $data['trailers'];
                unset($data['trailers']);
            }
            $data['thumbnail'] = MediaServices::save($data['thumbnail'], 'image', 'Movies');
            $movie = Movie::create($data);

            if(isset($actors)){
                $actors = array_map(fn($actor) => [
                    'name' => $actor,
                    'movie_id' => $movie->id,
                ], $actors);
                Actor::insert($actors);
            }

            if(isset($trailers)){
                $trailers = array_map(fn($trailer) => [
                    'video' => MediaServices::save($trailer, 'video', 'Trailers'),
                    'movie_id' => $movie->id,
                ], $trailers);
                Trailer::insert($trailers);
            }

            return redirect()->route('movies.show', $movie)->with('success', 'Movie created successfully');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        return view('movies.show')->with([
            'movie' => $movie->load('actors', 'trailers'),
            'consumables' => Consumable::all()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movie $movie)
    {
        if($movie->reservations()->count() > 0)
            return redirect()->back()->with('error', "Can't Edit this movie");
        return view('movies.edit')->with([
            'movie' => $movie,
            'types' => MovieType::values(),
            'halls' => Hall::select(['id', 'name'])->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMovieRequest $request, Movie $movie)
    {

        if ($movie->reservations()->count() > 0) {
            return redirect()->back()->with('error', "Can't update this movie because it has reservations.");
        }

        $data = $request->validated();

        if(
            Movie::where('hall_id', $data['hall_id'])
                ->whereNot('id', $movie->id)
                ->whereDate('showing_date',  $data['showing_date'])
                ->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                ->exists()
        ){
            return redirect()->route('movies.create')->with('error', 'There conflict in time');
        }

        if(isset($data['actors'])){
            $actors = $data['actors'];
        }
        if(isset($data['trailers'])) {
            $trailers = $data['trailers'];
        }
        if(isset($data['removed_videos'])) {
            $removed = $data['removed_videos'];
        }

        $data['thumbnail'] = $request->file('thumbnail') ? MediaServices::update($data['thumbnail'], 'image', $movie->thumbnail, 'Movies') : $movie->thumbnail;

        $movie->update($data);

        $movie->actors()->delete();
        if (isset($actors)) {
           $actors = array_map(fn($actor) => [
               'name' => $actor,
               'movie_id' => $movie->id,
           ], $actors);

           Actor::insert($actors);
        }

        if (isset($trailers)) {
            $trailers = array_map(fn($trailer) => [
                'video' => MediaServices::save($trailer, 'video', 'Trailers'),
                'movie_id' => $movie->id,
            ], $trailers);
            Trailer::insert($trailers);
        }

        if (isset($removed)){
            $movie->trailers()->whereIn('id', $removed)->delete();
        }

        return redirect()->route('movies.show', $movie)->with('success', 'Movie updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie)
    {
        if($movie->reservations()->count())
            return redirect()->back()->with('error', "Can't Delete this movie");

        $movie->delete();

        return redirect()->route('movies.index')->with('success', 'Movie deleted successfully');
    }
}
