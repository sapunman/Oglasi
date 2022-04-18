<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Category;
use App\Models\Message;
use Illuminate\Http\Request;

class AdController extends Controller
{
   public function index() {
        
        $all_ads = new Ad;

        if(isset(request()->cat)) {
                $all_ads = Ad::whereHas('category',function($query){
                    $query->whereName(request()->cat);
            })->get();

        }
        if(isset(request()->type)) {
            $type = (request()->type == 'lower') ? 'asc' : 'desc';
            $all_ads = $all_ads->orderBy('price',$type);
        }
        
        $all_ads = $all_ads->get();
        $categories = Category::all();
        return view('welcome',compact('all_ads','categories'));
   }

   public function show($id) {
        $single_ad = Ad::find($id);
        if(auth()->check() && auth()->user()->id !== $single_ad->user_id) {
            $single_ad->increment('views');
        }  

        return view('singleAd',compact('single_ad'));
   }

   public function sendMessage(Request $request,$id) {
       $ad = Ad::find($id); //koji oglas
       $ad_owner = $ad->user; //vlasnik oglasa
       //nova poruka
       $new_msg = new Message();
       $new_msg->text = $request->msg;
       $new_msg->sender_id = auth()->user()->id;
       $new_msg->receiver_id = $ad_owner->id;
       $new_msg->ad_id = $ad->id;
       $new_msg->save();

       return redirect()->back()->with('message', 'Message sent');
   }
}
