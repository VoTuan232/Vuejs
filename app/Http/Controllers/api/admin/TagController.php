<?php

namespace App\Http\Controllers\api\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Support\Facades\Hash;

class TagController extends Controller
{
    public function index() {
    	return Tag::paginate(2);		
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255|unique:tags',
        ]);

        $tag = Tag::create($request->only(['name']));

        return $tag;
    }

    public function update(Request $request, Tag $tag) {
    	$this->validate($request, [
            'name' => 'required|string|max:255|unique:tags,name,'.$tag->id,
        ]);

    	$tag->update($request->only(['name']));

    	return $tag;
    }

    public function destroy(Tag $tag) {
    	$tag->delete();
    }
}
