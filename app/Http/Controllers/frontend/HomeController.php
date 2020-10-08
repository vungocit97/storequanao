<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function GetHome()
    {
        $data['product_fe']=Product::where('featured',1)->where('img','<>','no-img.jpg')->take(4)->get();
        $data['product_new']=Product::where('img','<>','no-img.jpg')->orderby('created_at','DESC')->take(4)->get();
       return view('frontend.index',$data);
    }

    public function GetContact()
    {
        return view('frontend.contact');
    }

    public function GetAbout()
    {
        return view('frontend.about');
    }
}
