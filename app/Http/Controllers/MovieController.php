<?php

namespace App\Http\Controllers;

use App\Enums\MovieType;
use App\Enums\Role;
use App\Http\Requests\Movie\StoreMovieRequest;
use App\Http\Requests\Movie\UpdateMovieRequest;
use App\Models\Consumable;
use App\Models\Hall;
use App\Models\Movie;
use App\Models\Schedule;
use App\Models\Trailer;
use App\Services\MediaServices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MovieController extends Controller
{
    public function index()
    {
        $search = '%'.request()->input('search').'%';
        $types = MovieType::values();
        $data = [];
        foreach ($types as $type) {
            $data[$type] = Movie::select(['id', 'name', 'thumbnail', 'type', 'schedule_id'])
                ->where('name', 'like', $search)
                ->where('type', $type)
                ->with('schedule')
                ->paginate();
        }
        return view('movies.index')->with('data', $data);
    }

    public function create()
    {
        return view('movies.create')->with([
            'types' => MovieType::values(),
            'halls' => Hall::select(['id', 'name'])->get(),
            'schedules' => Schedule::all(),
        ]);
    }

    public function store(StoreMovieRequest $request)
    {
        $data = $request->validated();
        return DB::transaction(function () use ($data){
            //check conflict
            if(
                Movie::where('hall_id', $data['hall_id'])
                    ->where('schedule_id', $data['schedule_id'])
                    ->exists()
            ){
                return redirect()->route('movies.create')
                    ->withInput()
                    ->with('error', 'There conflict in time');
            }
            if(isset($data['trailers'])) {
                $trailers = $data['trailers'];
                unset($data['trailers']);
            }
            $data['thumbnail'] = MediaServices::save($data['thumbnail'], 'image', 'Movies');
            $movie = Movie::create($data);

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
            'movie' => $movie->load('trailers'),
            'consumables' => Consumable::all()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movie $movie)
    {
        return view('movies.edit')->with([
            'movie' => $movie,
            'types' => MovieType::values(),
            'halls' => Hall::select(['id', 'name'])->get(),
            'schedules' => Schedule::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMovieRequest $request, Movie $movie)
    {
        $data = $request->validated();
        if(
            Movie::where('hall_id', $data['hall_id'])
                ->where('id', '!=', $movie->id)
                ->where('schedule_id', $data['schedule_id'])
                ->exists()
        ){
            return redirect()->back()->with('error', 'There conflict in time');
        }
        if(isset($data['trailers'])) {
            $trailers = $data['trailers'];
        }
        if(isset($data['removed_videos'])) {
            $removed = $data['removed_videos'];
        }

        $data['thumbnail'] = $request->file('thumbnail') ? MediaServices::update($data['thumbnail'], 'image', $movie->thumbnail, 'Movies') : $movie->thumbnail;

        if ($movie->reservations()->count() > 0) {
            if(
                $data['start_time'] != $movie->start_time ||
                $data['end_time'] != $movie->end_time ||
                $data['hall_id'] != $movie->hall_id ||
                $data['showing_date'] != $movie->showing_date ||
                $data['standard_price'] != $movie->standard_price ||
                $data['vip_price'] != $movie->vip_price
            )
            return redirect()->back()->with('error', "Can't update schedule information of this movie because it has reservations.");
        }
        $movie->update($data);

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
