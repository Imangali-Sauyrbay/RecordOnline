<?php

namespace App\Http\Controllers;

use App\Models\Record;
use Illuminate\Http\Request;

class RecordStatusController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $lang, $id)
    {
        $request->validate([
            'status' => 'required|integer|exists:record_statuses,id'
        ]);

        Record::where('id', $id)->update([
            'record_status_id' => $request->input('status')
        ]);

        return back();
    }
}
