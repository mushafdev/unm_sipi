<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Spatie\Image\Image;

class SettingController extends Controller
{
    public function index()
    {
        $d['title']='Pengaturan';
        $d['data']=Setting::first();
        return view('a.pages.setting',$d);
    }

    public function update(Request $request)
    {
        $rules = [
            'nama_web'=>'required',
            'email'=>'nullable|email:rfc,dns',
            'favicon'=>'max:500|mimes:jpeg,png',
            'logo'=>'max:1048|mimes:jpeg,png',
            'telp'=>'nullable',
            'wa'=>'nullable',
            'alamat'=>'nullable',
            'deskripsi'=>'nullable',
            'tax'=>'nullable',
        ];

        $messages = [
        ];

        $request->validate($rules,$messages);

        try {
            $field = Setting::find('setting');
            if(!empty($request->logo)){
                $imgName = 'logo.png';
                Storage::disk('images')->put($imgName, file_get_contents($request->logo));
                $field->logo = $imgName;
            }

            $field->nama_web = $request->nama_web;
            $field->email = $request->email;
            $field->alamat = $request->alamat;
            $field->deskripsi = $request->deskripsi;
            $field->wa = $request->wa;
            $field->telp = $request->telp;
            $field->maps = $request->maps;
            $field->tax = $request->tax;

            $field->save();
            return response()->json([
                'text' => 'Data sukses diupdate',
                'status' => 200,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' =>$e->getMessage()
            ],500);
        }
        
    }

}
