<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\API\Movies;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
// use getID3;

class MoviesController extends Controller
{
    public function index()
    {
        $data = Movies::select('id', 'title', 'description', 'duration', 'artist', 'genres', 'file')->paginate();
        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'artist' => 'required',
            'genres' => 'required',
            'file' => 'required|mimetypes:video/mp4,video/3gp,video/mkv|max:102400',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }

        $file = $request->file;

        $time = number_format(round(microtime(true) * 1000), 0, "", "");
        $ext = $file->extension();
        $file_name = 'Movies - ' . $time . '.' . $ext ?? 'mp4';
        $file->move(public_path('movies/'), $file_name);

        $getID3 = new \getID3;
        $newfile = $getID3->analyze(public_path('movies/') . $file_name);
        $duration = date('H:i:s.v', $newfile['playtime_seconds']);

        $data = Movies::create([
            'title' => $request->title,
            'description' => $request->description,
            'duration' => $duration,
            'artist' => $request->artist,
            'genres' => $request->genres,
            'file' => $file_name,
        ]);

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $data,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'artist' => 'required',
            'genres' => 'required',
            'file' => 'mimetypes:video/mp4,video/3gp,video/mkv|max:102400',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }

        $data = Movies::where('id', $id)->first();
        // dd($request->all());
        $file = $request->file;
        if ($file == null) {
            $update = Movies::where('id', $id)->update([
                'title' => $request->title,
                'description' => $request->description,
                'artist' => $request->artist,
                'genres' => $request->genres,
            ]);
        } else {
            $time = number_format(round(microtime(true) * 1000), 0, "", "");
            $ext = $file->extension();
            $file_name = 'Movies - ' . $time . '.' . $ext ?? 'mp4';
            $file->move(public_path('movies/'), $file_name);
            File::delete(public_path('movies/') . $data->file);

            $getID3 = new \getID3;
            $newfile = $getID3->analyze(public_path('movies/') . $file_name);
            $duration = date('H:i:s.v', $newfile['playtime_seconds']);

            $update = Movies::where('id', $id)->update([
                'title' => $request->title,
                'description' => $request->description,
                'duration' => $duration,
                'artist' => $request->artist,
                'genres' => $request->genres,
                'file' => $file_name,
            ]);
        }
        $data = Movies::where('id', $id)->first();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $data,
        ]);
    }

    public function search(Request $request)
    {
        $query = DB::table('movies')
            ->orderBy('created_at', 'desc');

        if ($request->title != '') {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->artist != '') {
            $query->where('artist', 'like', '%' . $request->artist . '%');
        }

        if ($request->genres != '') {
            $query->where('genres', 'like', '%' . $request->genres . '%');
        }

        if ($request->file != '') {
            $query->where('file', 'like', '%' . $request->file . '%');
        }

        $data = $query->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $data,
        ]);
    }
}
