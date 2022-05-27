<?php

namespace App\Http\Controllers\Api\Storage;

use App\Http\Controllers\Controller;
use App\Models\DeTailStorage;
use App\Models\Storage as StorageModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class StorageController extends Controller
{
    private $storage;
    private $detail_storage;
    public function __construct(StorageModel $storage, DeTailStorage $detail_storage)
    {
        $this->storage = $storage;
        $this->detail_storage = $detail_storage;
    }

    public function show($id)
    {
        try {
            $data = $this->detail_storage::where('storage_id', $id)->orderBy('created_at', 'desc')->get();
            return response()->json(['data' => $data], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Serve Error'], 500);
        }
    }

    public function download()
    {
        if (Storage::disk('s3')->exists('files/' . auth()->id() . '/' . request('name'))) {
            return Storage::disk('s3')->download('files/' . auth()->id() . '/' . request('name'));
        };
    }

    public function remove($id)
    {
        try {
            $detail_storage = $this->detail_storage::find($id);

            if (Storage::disk('s3')->exists('files/' . auth()->id() . '/' . $detail_storage->url)) {
                Storage::disk('s3')->delete('files/' . auth()->id() . '/' . $detail_storage->url);
                $detail_storage->delete();
                return response()->json([], 200);
            };
        } catch (\Throwable $th) {
            return response()->json([
                'errors' => 'Server error ',
            ], HttpFoundationResponse::HTTP_BAD_GATEWAY);
        }
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|max:10000|mimes:zip',
            'name' => 'required',
        ]);
        if ($this->storage::where('user_id', auth()->id())->where('id', $request->id)->exists()) {
            $name = \Str::slug($request->name) . '.zip';

            if (Storage::disk('s3')->exists('files/' . auth()->id() . '/' . $name)) return response()->json(['message' => 'Names is isset'], 403);
            $request->file('file')->storeAs('files/' . auth()->id(), $name, 's3');
            $url = $name;
            $size = $request->file('file')->getSize();
            $this->detail_storage::create(
                [
                    'url' => $url,
                    'name' => $name,
                    'size' => $size,
                    'storage_id' => $request->id,
                ]
            );
            return response()->json([], 200);
        };
        return response()->json([], 300);
    }

    public function store(Request $request)
    {
        $this->storage::create(array_merge($request->validate(['name' => 'required|min:3']), [
            'user_id' => auth()->id()
        ]));
        return response()->json([], 200);
    }

    public function index()
    {
        try {
            $data = $this->storage::where('user_id', auth()->id())
                ->withCount('detail_storage')
                ->when(request()->has('detail_storage'), function ($q) {
                    return $q->with('detail_storage');
                })
                ->orderBy('id', 'desc')
                ->get();
            return response()->json([
                'data' => $data,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'errors' => 'Server error ',
            ], HttpFoundationResponse::HTTP_BAD_GATEWAY);
        }
    }

    public function destroy($id)
    {
        $sotrage = $this->storage::find($id);
        foreach ($sotrage->detail_storage as $a) {
            if (Storage::disk('s3')->exists('files/' . auth()->id() . '/' . $a->url)) Storage::disk('s3')->delete('files/' . auth()->id() . '/' . $a->url);
        }
        $sotrage->detail_storage()->delete();
        $sotrage->delete();
        return response()->json([], 200);
    }
}
