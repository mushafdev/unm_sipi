<?php

namespace App\Http\Controllers\m;

use App\Http\Controllers\Controller;


class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


     
     public function permohonan()
     {
        $d['title']='Dokumen Permohonan';
        return view('u.pages.document.permohonan.index',$d);

     }
     
     public function berkas_pi()
     {
        $d['title']='Berkas Praktik Industri';
        return view('u.pages.document.berkas_pi.index',$d);

     }
     
     public function berkas_seminar()
     {
        $d['title']='Berkas Seminar';
        return view('u.pages.document.berkas_seminar.index',$d);

     }




}
