<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Category;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $all_ads = Ad::where('user_id',Auth::user()->id)->get();
        $all_ads = Auth::user()->ads;
        return view('home', ['all_ads'=>$all_ads]);
    }

    public function addDeposit() {
        return view('home.addDeposit');
    }

    public function updateDeposit(Request $request) {
        
        $user = Auth::user();

        $request->validate([
            "deposit"=>"required|max:4"
        ],
        [
            "deposit.max"=>"Can't add more than 9999 rsd at once"
        ]);

        $user->deposit = $user->deposit + $request->deposit;
        $user->save();

        return redirect(route('home'));
    } 

    public function showAdForm() {
        $allCategories = Category::all();
        return view('home.showAdForm', ['categories'=>$allCategories]);
    }

    public function saveAd(Request $request) {
        $request->validate([
            'title'=>'required | max:255',
            'body'=>'required',
            'price'=>'required',
            'image1'=>'mimes:jpeg,jpg,png',
            'image2'=>'mimes:jpeg,jpg,png',
            'image3'=>'mimes:jpeg,jpg,png',
            'category'=>'required'
        ]);

        if($request->hasFile('image1')) {
            $image1 = $request->file('image1');
            $image1_name = time().'1.'.$image1->extension();
            $image1->move(public_path('ad_images'), $image1_name);
        }
        if($request->hasFile('image2')) {
            $image2 = $request->file('image2');
            $image2_name = time().'2.'.$image2->extension();
            $image2->move(public_path('ad_images'), $image2_name);
        }
        if($request->hasFile('image3')) {
            $image3 = $request->file('image3');
            $image3_name = time().'3.'.$image3->extension();
            $image3->move(public_path('ad_images'), $image3_name);
        }
        Ad::create([
            'title'=>$request->title,
            'body'=>$request->body,
            'price'=>$request->price,
            'image1'=> (isset($image1_name)) ? $image1_name : null,
            'image2'=> (isset($image2_name)) ? $image2_name : null,
            'image3'=> (isset($image3_name)) ? $image3_name : null,
            'user_id'=> Auth::user()->id,
            'category_id'=>$request->category
        ]);

        return redirect(route('home'));
    }

    public function showSingleAd($id) {
        $single_ad = Ad::find($id);

        return view('home.singleAd',['single_ad'=>$single_ad]);
    }

    public function showMessages() {
        $messages = Message::where('receiver_id',auth()->user()->id)->get();

        return view('home.messages',compact('messages'));
    }

    public function replay() {
        $sender_id = request()->sender_id;
        $ad_id = request()->ad_id;

        $messages = Message::where('sender_id',$sender_id)->where('ad_id',$ad_id)->get();

        return view('home.replay',compact('sender_id','ad_id','messages'));
    }

    public function replayStore(Request $request) {
        $sender = User::find($request->sender_id);
        $ad = Ad::find($request->ad_id);

        //nova poruka
        $new_message = new Message();
        $new_message->text = $request->msg;
        $new_message->sender_id = auth()->user()->id;
        $new_message->receiver_id = $sender->id;
        $new_message->ad_id = $ad->id;
        $new_message->save();

        return redirect()->route('home.showMessages')->with('message','Replay sent');
    }
}
