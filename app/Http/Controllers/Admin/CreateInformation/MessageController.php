<?php

namespace App\Http\Controllers\Admin\CreateInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\CreateInfoRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Message;

class MessageController extends Controller
{
    private CategoryRepositoryInterface $categoryRepository;
    private CreateInfoRepositoryInterface $createInfoRepository;

    public function __construct(CreateInfoRepositoryInterface $createInfoRepository,CategoryRepositoryInterface $categoryRepository) 
    {
        $this->createInfoRepository = $createInfoRepository;
        $this->categoryRepository = $categoryRepository;
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function MessageList(Request $request)
    {  
        $res = Message::select('message.*');
        if ($request['action'] == 'search') {
            if (request()->has('message_title') && request()->input('message_title') != '') {
                $res->where('title', request()->input('message_title'));
            } 
            if (request()->has('message_classid') && request()->input('message_classid') != '') {
                if (request()->input('message_classid')=='0') {
                    $res->whereNull('class_id');
                } else {
                    $res->where('class_id', request()->input('message_classid'));
                }
            }            
        }else {
            request()->merge([
                'message_title'   => '',
                'message_classid' => ''
            ]);
        }       
    
        $res = $res->paginate(20);
             
        $class_list = $this->createInfoRepository->getClassSetup();
        $classes[0]="All";
        foreach($class_list as $a) {
            $classes[$a->id] = $a->name;
        }  

        return view('admin.createinformation.message.index',[
            'classes'       =>$classes,
            'list_result'   => $res]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $class_list = $this->createInfoRepository->getClassSetup();  
                     

        return view('admin.createinformation.message.create',[
            'classes'       =>$class_list
        ]);
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
            'title'       =>'required',
        ]); 
       
        $errmsg =array();
        if ($request->class_id == '99') {
            array_push($errmsg,'Class');
        } 
       
       
        if (!empty($errmsg)) {
            $errmsg = implode(',',$errmsg);
            return redirect()->back()->with('danger','Please select '.$errmsg.'!');
        }  
                
        DB::beginTransaction();
        try{
            $insertData = array(
                'class_id'           =>$request->class_id !== '0' ? $request->class_id : null,
                'title'              =>$request->title,
                'description'        =>$request->description,
                'remark'             =>$request->remark,
                'created_by'         =>$login_id,
                'updated_by'         =>$login_id,
                'created_at'         =>$nowDate,
                'updated_at'         =>$nowDate
            );
            $result=Message::insert($insertData);
                        
            if($result){      
                DB::commit();
                return redirect(url('admin/message/list'))->with('success','Message Created Successfully!');
            }else{
                return redirect()->back()->with('danger','Message Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Message Created Fail !');
        }    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $class_list = $this->createInfoRepository->getClassSetup();      

        $res = Message::where('id',$id)->get();
        return view('admin.createinformation.message.update',[
            'classes'       =>$class_list,
            'result'        =>$res
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'title'       =>'required',
        ]); 
       
        $errmsg =array();
        if ($request->class_id == '99') {
            array_push($errmsg,'Class');
        } 
        
        if (!empty($errmsg)) {
            $errmsg = implode(',',$errmsg);
            return redirect()->back()->with('danger','Please select '.$errmsg.'!');
        }

        DB::beginTransaction();
        try{
            $updateData = array(
                'class_id'           =>$request->class_id !== '0' ? $request->class_id : null,
                'title'              =>$request->title,
                'description'        =>$request->description,
                'remark'             =>$request->remark,
                'updated_by'         =>$login_id,
                'updated_at'         =>$nowDate

            );
           
            $result=Message::where('id',$id)->update($updateData);
                      
            if($result){ 
                DB::commit();               
                return redirect(url('admin/message/list'))->with('success','Message Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Message Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Message Updared Fail !');
        }  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try{
            $checkData = Message::where('id',$id)->first();

            if (!empty($checkData)) {
                
                $res = Message::where('id',$id)->delete();
                if($res){
                    DB::commit();
                    //To return list
                    return redirect(url('admin/message/list'))->with('success','Message Deleted Successfully!');
                }
            }else{
                return redirect()->back()->with('danger','There is no result with this Message.');
            }
           

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Message Deleted Failed!');
        }
    }
}
