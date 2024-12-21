<?php

namespace App\Http\Controllers\Admin\Operation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\News;

class NewsCommentController extends Controller
{
    
    public function __construct() 
    {
        
    }

    
    public function NewsCommentList($new_id)
    {  
        $news = News::where('news.id',$new_id)
                        ->first()->toArray();
        
        $news_data = [];
        if ($news) {
            $news_data['id']            = $new_id;
            $news_data['title']         = $news['title'];
            $news_data['description']   = $news['description'];
            $news_data['image']         = $news['image'];
        }
        $res = DB::table('news_comment')->where('news_id',$new_id)->select('news_comment.*');
        $res = $res->paginate(20);
        
        
        return view('admin.operation.newscomment.index',[
            'news_data'     =>$news_data,
            'list_result'   => $res]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'comment'         =>'required'
        ]); 
                
        DB::beginTransaction();
        try{
            $insertData = array(
                'news_id'           =>$request->news_id,
                'comment_by_school' =>$login_id,
                'comment'           =>$request->comment,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $result=DB::table('news_comment')->insert($insertData);
                        
            if($result){         
                DB::commit();
                return redirect(url('admin/news/comment/list/'.$request->news_id))->with('success','Comment Added Successfully!');
            }else{
                return redirect()->back()->with('danger','Comment Added Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Comment Added Fail !');
        }       
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try{
            $checkData = DB::table('news_comment')->where('id',$id)->first();

            if (!empty($checkData)) {
                
                $res = DB::table('news_comment')->where('id',$id)->delete();
                if($res){
                    DB::commit();
                    //To return list
                    return redirect(url('admin/news/comment/list/'.$checkData['news_id']))->with('success','Comment deleted Successfully!');        
                }
            }else{
                return redirect()->back()->with('danger','There is no result with this comment.');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Comment Deleted Failed!');
        }
    }

}
