<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;//チームモデルを使う
use App\Models\User;//ユーザーモデルを使う
use Auth;//ログイン認証の機能を使う
use Validator;//バリデーション機能を使う

class TeamController extends Controller
{
    //トップページ表示処理
    public function index(){
        
        //チームのデータ全件取得
        $teams = Team::get();
        
        return view('teams',[ 'teams' => $teams ]);
    }
    
    
    //登録処理
    public function store(Request $request){
        
        //バリデーション 
        $validator = Validator::make($request->all(), [
            'team_name' => 'required|max:255'
        ]);
        
        //バリデーション:エラー
        if ($validator->fails()) {
            return redirect('/')
                ->withInput()
                ->withErrors($validator);
        }
        
       //以下に登録処理を記述（Eloquentモデル）
        $teams = new Team;
        $teams->team_name = $request->team_name;
        $teams->user_id = Auth::id();//ここでログインしているユーザidを登録しています
        $teams->save();
        
        //多対多のリレーションもここで登録
        $teams->members()->attach( Auth::user(),['role'=>'owner'] );//<-ここが変わってるよ！！
        
        return redirect('/teams');
        
    }
    
    
    //チーム参加処理
    public function join($team_id){
        
        //ログイン中のユーザーを取得
        $user = Auth::user();
        
        //参加したいチームを取得
        $team = Team::find($team_id);
        
        //中間テーブルに保存（リレーション登録）
        $team->members()->attach($user);
        
        return redirect('/teams');
        
        
    }
    
    //チーム編集詳細画面表示処理
    public function edit(Team $team){
        
        return view('teamsedit',[ 'team' => $team ]);
        
    }
    
    //更新処理（チーム名変更）
    //更新処理
    public function update (Request $request) {
            
             //バリデーション 
            $validator = Validator::make($request->all(), [
                'team_name' => 'required|max:255',
            ]);
            
            //バリデーション:エラー
            if ($validator->fails()) {
                return redirect('/')
                    ->withInput()
                    ->withErrors($validator);
            }
            
            //対象のチームを取得
            $team = Team::find($request->id);
            $team->team_name = $request->team_name;
            $team->save();
            
            return redirect('/teams');
            
    }
    
    
    
}