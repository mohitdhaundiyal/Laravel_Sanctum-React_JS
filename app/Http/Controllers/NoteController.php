<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    function store(Request $request)
    {
        $field = $request->validate([
            'title' => 'required',
            'description' => 'required'
        ]);

        $note = Note::create([
            'title' => $field['title'],
            'description' => $field['description'],
            'user_id' => $request->user()->id
        ]);

        return response([
            'Note' => $note,
            'message' => 'Successfully added'
        ], 201);
    }

    function update(Request $request, $id)
    {
        $note = Note::find($id);

        if (!$note) {
            return response([
                'message' => 'Note not found'
            ], 404);
        } else if ($note and ($note->user_id == $request->user()->id)) {
            $note->update($request->all());
            return $note;
        } else {
            return response([
                'message' => 'Not Allowed'
            ], 401);
        }
    }

    function delete($id)
    {
        return Note::destroy($id);
    }

    function getNotes(Request $request)
    {
        return Note::where('user_id', $request->user()->id)->get();
    }
}
