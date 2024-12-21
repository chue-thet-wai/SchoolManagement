<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\ChangePasswordRequest;
use App\Http\Resources\API\HomeResource;
use App\Http\Requests\API\EditProfileRequest;
use App\Http\Resources\API\ContactListResource;
use App\Http\Resources\API\EventResource;
use App\Models\AcademicYear;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\StudentGuardian;
use Exception;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    
    public function home(Request $request)
    {
        try {
            $guardian = Auth::user();

            //if (!empty($guardian) && Hash::check($password, $guardian->password)) {
            if (!empty($guardian)) {
                return response()->json([
                    'status'  => '200',
                    'message' => 'Success',
                    'data'    => new HomeResource($guardian),
                ]);
            } else {
                return response()->json([
                    'status'  => '200',
                    'message' => 'Data not found !',
                    'data'    => []
                ]);
            }

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    public function editProfile(EditProfileRequest $request) 
    {
        $guardianId = Auth::id();
        
        $nowDate  = date('Y-m-d H:i:s', time());

        DB::beginTransaction();
        try{
            $updateData = array(
                'email'           =>$request->email,
                'phone'           =>$request->primary_contact,
                'secondary_phone' =>$request->secondary_contact,
                'address'         =>$request->address,
                'nrc'             =>$request->nrc,
                'email'           =>$request->email,
                'updated_by'      =>$guardianId,
                'updated_at'      =>$nowDate

            ); 

            if ($request->has('photo') && $request->photo != '') {
                // Decode the base64 image
                $base64Image = $request->photo;
                $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
            
               // Get the file extension from the MIME type
                $mime = finfo_buffer(finfo_open(), $image, FILEINFO_MIME_TYPE);
                $extensions = [
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/gif' => 'gif',
                ];
                $extension = $extensions[$mime] ?? 'jpg'; // Default to jpg if MIME type is unknown
                $image_name = $guardianId . "_" . time() . "." . $extension;
            
                // Save the image
                file_put_contents(public_path('assets/guardian_images') . '/' . $image_name, $image);
            
                // Delete the previous image
                $guardianData = StudentGuardian::where('id', $guardianId)->first();
                $previous_img = $guardianData->photo;
                if (!empty($previous_img)) {
                    @unlink(public_path('/assets/guardian_images/' . $previous_img));
                }
            
                // Update the photo field in the database
                $updateData['photo'] = $image_name;
            }

            $result=StudentGuardian::where('id',$guardianId)->update($updateData);                      
            if($result){ 
                
                DB::commit(); 
                return response()->json([
                    'status'  => '200',
                    'message' => 'Successfully Edit'
                ]);            
            }else{
                return response()->json([
                    'status'  => '422',
                    'message' => 'Edit Fail'
                ],422); 
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }    
    }

    public function changePassword(ChangePasswordRequest $request) 
    {
        $guardianId = Auth::id();
        $nowDate  = date('Y-m-d H:i:s', time());

        DB::beginTransaction();
        try{
            $updateData = array(
                'password'        =>bcrypt($request->new_password),
                'updated_by'      =>$guardianId,
                'updated_at'      =>$nowDate

            );           
            $result=StudentGuardian::where('id',$guardianId)->update($updateData);                      
            if($result){ 
                DB::commit(); 
                return response()->json([
                    'status'  => '200',
                    'message' => 'Successfully Password Changed.'
                ]);        
            }else{
                return response()->json([
                    'status'  => '422',
                    'message' => 'Password Changed Fail.'
                ],422); 
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }     
    }

    public function contactList(Request $request) {
        try {
            $guardianId = Auth::id();
       
            $student_data  = DB::table('student_info')
                            ->where('guardian_id',$guardianId)
                            ->whereNull('deleted_at')
                            ->first(); 
            
            if (!empty($student_data)) {
                return response()->json([
                    'status'  => '200',
                    'message' => 'Success',
                    'data'    => new ContactListResource($student_data),
                ]);
            } else {
                return response()->json([
                    'status'  => '200',
                    'message' => 'Data not found !',
                    'data'    => []
                ]);
            }

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }        
    }

    public function eventList(Request $request)
    {
        try {

            $currentDate = date("Y-m-d");
            //current academic year
            $currentAcademic = AcademicYear::where('start_date', '<=', $currentDate)
                            ->where('end_date', '>=', $currentDate)
                            ->first();
            $academic_year_id = $currentAcademic ? $currentAcademic->id : null;

            $allEvents   = Event::whereNull('grade_id')
                                ->where('academic_year_id',$academic_year_id)
                                //->where('event_from_date', '<=', $currentDate)
                                ->where('event_to_date', '>=', $currentDate)
                                ->orderBy('event_from_date')
                                ->get();

                
            if (count($allEvents) > 0) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data found!',
                    'data'    => new EventResource($allEvents),
                ]);
            } else {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => []
                ]);
            }

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }
}
