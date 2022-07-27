<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Profile;

use App\Phistory;

use Carbon\Carbon;

class ProfileController extends Controller
{
    //
    public function add()
    {
        return view('admin.profile.create');
    }
    
    public function create(Request $request)
    {
        $this->validate($request, Profile::$rules);
        
        $profile = new Profile;
        $form = $request->all();
        
        $profile->fill($form);
        $profile->save();

        return redirect('admin/profile/create');
    }
    
    public function edit(Request $request)
    {
      $profile = Profile::find($request->id);
      if (empty($profile)) {
        abort(404);    
      }
      
      return view('admin.profile.edit',['profile_form' => $profile]);
    }
    
    public function update(Request $request)
    {
     // Validationをかける
      $this->validate($request, Profile::$rules);
      // Profile Modelからデータを取得する
      $profile = Profile::find($request->id);
      // 送信されてきたフォームデータを格納する
      $profile_form = $request->all();
      unset($profile_form['_token']);
      // 該当するデータを上書きして保存する
      $profile->fill($profile_form)->save();
      
      $phistory = new Phistory();
      $phistory->profile_id =$profile->id;
      $phistory->edited_at = Carbon::now(); //memo:Carbonは時刻操作ライブラリ
      $phistory->save();
      
      return redirect('admin/profile/edit');
    }
}

//admin/profile/create にアクセスしたら ProfileController の add Action に、admin/profile/edit にアクセスしたら ProfileController の edit Action に割り当てるように設定してください
// Route::group(['prefix' => 'admin'],function(){
//     Route::get('profile/create',
// 'Admin\ProfileController@add');
//     Route::get('profile/edit',
// 'Admin\ProfileController@edit');
// });