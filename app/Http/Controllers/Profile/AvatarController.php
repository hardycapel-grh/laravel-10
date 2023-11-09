<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAvatarRequest;
use Illuminate\support\Facades\Storage;
use Illuminate\Http\Request;

class AvatarController extends Controller
{
    public function update(UpdateAvatarRequest $request)
    {
        // dd($request->input('_token'));

        // dd($request->all());
        // auth()->user()->update(['avatar' => 'test']);
        // dd(auth()->user());
        $path = Storage::disk('public')->put('avatars',$request->file('avatar'));
        // dd($path);
        // $path = $request->file('avatar')->store('avatars','public');
        // dd($path);
        if($oldAvatar = $request->user()->avatar){
            Storage::disk('public')->delete($oldAvatar);
        }
        auth()->user()->update(['avatar' => $path]);

        // return response()->redirectTo('/profile');
        // return back()->with('message', 'Avatar is changed.');
        return redirect(route('profile.edit'))->with('message','Avatar is updated');
    }
}
