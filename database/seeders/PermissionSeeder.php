<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nowDate  = date('Y-m-d H:i:s', time());
        $permissions = [
            array(
                'main_menu' =>"User Management",
                "sub_menu"  =>"User",
                "menu_route"=>"user.index",
                "type"      =>"route"
            ),
            array(
                'main_menu' =>"User Management",
                "sub_menu"  =>"Role and Permission",
                "menu_route"=>"role_permission.index",
                "type"      =>"route"
            ),
            array(
                'main_menu' =>"Category",
                "sub_menu"  =>"Academic Year",
                "menu_route"=>"academic_year.index",
                "type"      =>"route"
            ),
            array(
                'main_menu' =>"Category",
                "sub_menu"  =>"Branch",
                "menu_route"=>"branch.index",
                "type"      =>"route"
            ),
            array(
                'main_menu' =>"Category",
                "sub_menu"  =>"Room",
                "menu_route"=>"room.index",
                "type"      =>"route"
            ),
            array(
                'main_menu' =>"Category",
                "sub_menu"  =>"Grade",
                "menu_route"=>"grade.index",
                "type"      =>"route"
            ),
            array(
                'main_menu' =>"Category",
                "sub_menu"  =>"Section",
                "menu_route"=>"section.index",
                "type"      =>"route"
            ),
            array(
                'main_menu' =>"Category",
                "sub_menu"  =>"Grade Level Fee",
                "menu_route"=>"grade_level_fee.index",
                "type"      =>"route"
            ),
            array(
                'main_menu' =>"Category",
                "sub_menu"  =>"Additional Fee",
                "menu_route"=>"additional_fee.index",
                "type"      =>"route"
            ),
            array(
                'main_menu' =>"Category",
                "sub_menu"  =>"Subject",
                "menu_route"=>"subject.index",
                "type"      =>"route"
            ),
            array(
                'main_menu' =>"Create Information",
                "sub_menu"  =>"Teacher Information",
                "menu_route"=>"teacher_info.index",
                "type"      =>"route"
            ),
            array(
                'main_menu' =>"Create Information",
                "sub_menu"  =>"Student Information",
                "menu_route"=>"admin/student_info/list",
                "type"      =>"url"
            ),
            array(
                'main_menu' =>"Create Information",
                "sub_menu"  =>"Class Setup",
                "menu_route"=>"class_setup.index",
                "type"      =>"route"
            ),
            array(
                'main_menu' =>"Create Information",
                "sub_menu"  =>"Driver Information",
                "menu_route"=>"driver_info.index",
                "type"      =>"route"
            ),
            array(
                'main_menu' =>"Create Information",
                "sub_menu"  =>"Schedule",
                "menu_route"=>"schedule.index",
                "type"      =>"route"
            ),
            array(
                'main_menu' =>"Create Information",
                "sub_menu"  =>"Activity",
                "menu_route"=>"activity.index",
                "type"      =>"route"
            ),
            array(
                'main_menu' =>"Registration",
                "sub_menu"  =>"Student Registration",
                "menu_route"=>"student_reg.index",
                "type"      =>"route"
            ),
            array(
                'main_menu' =>"Registration",
                "sub_menu"  =>"Waiting List Registration",
                "menu_route"=>"waitinglist_reg.index",
                "type"      =>"route"
            ),
            array(
                'main_menu' =>"Registration",
                "sub_menu"  =>"Cancel Registration",
                "menu_route"=>"cancel_reg.index",
                "type"      =>"route"
            ),
            array(
                'main_menu' =>"Registration",
                "sub_menu"  =>"Payment Registration",
                "menu_route"=>"payment.index",
                "type"      =>"route"
            ),
            array(
                'main_menu' =>"Registration",
                "sub_menu"  =>"School Bus Track",
                "menu_route"=>"school_bus_track.index",
                "type"      =>"route"
            ),
            array(
                'main_menu' =>"Registration",
                "sub_menu"  =>"Teacher Attendance",
                "menu_route"=>"teacher_attendance.index",
                "type"      =>"route"
            ),
            array(
                'main_menu' =>"Registration",
                "sub_menu"  =>"Student Attendance",
                "menu_route"=>"student_attendance.index",
                "type"      =>"route"
            ),
            array(
                'main_menu' =>"Exam",
                "sub_menu"  =>"Exam Terms",
                "menu_route"=>"admin/exam_terms/list",
                "type"      =>"url"
            ),
            array(
                'main_menu' =>"Exam",
                "sub_menu"  =>"Exam Marks",
                "menu_route"=>"admin/exam_marks/list",
                "type"      =>"url"
            ),
            array(
                'main_menu' =>"Wallet",
                "sub_menu"  =>"Cash Counter",
                "menu_route"=>"admin/cash_counter/list",
                "type"      =>"url"
            ),
            array(
                'main_menu' =>"Wallet",
                "sub_menu"  =>"Cash In History",
                "menu_route"=>"admin/cash_in_history/list",
                "type"      =>"url"
            ),
            array(
                'main_menu' =>"Shop",
                "sub_menu"  =>"Menu",
                "menu_route"=>"admin/menu/list",
                "type"      =>"url"
            ),
            array(
                'main_menu' =>"Shop",
                "sub_menu"  =>"Sale Counter",
                "menu_route"=>"admin/food_order/list",
                "type"      =>"url"
            ),
            array(
                'main_menu' =>"Reporting",
                "sub_menu"  =>"Student Registration Report",
                "menu_route"=>"admin/reporting/studentregistration_report",
                "type"      =>"url"
            ),
            array(
                'main_menu' =>"Reporting",
                "sub_menu"  =>"Payment Report",
                "menu_route"=>"admin/reporting/payment_report",
                "type"      =>"url"
            ),
            array(
                'main_menu' =>"Reporting",
                "sub_menu"  =>"Cancel Report",
                "menu_route"=>"admin/reporting/cancel_report",
                "type"      =>"url"
            ),
            array(
                'main_menu' =>"Reporting",
                "sub_menu"  =>"Ferry Report",
                "menu_route"=>"admin/reporting/ferry_report",
                "type"      =>"url"
            ),
            array(
                'main_menu' =>"Reporting",
                "sub_menu"  =>"Teacher Attendance Report",
                "menu_route"=>"admin/reporting/teacher_attendance_report",
                "type"      =>"url"
            ),
            array(
                'main_menu' =>"Reporting",
                "sub_menu"  =>"Student Attendance Report",
                "menu_route"=>"admin/reporting/student_attendance_report",
                "type"      =>"url"
            ),
            
        ];
    
        foreach ($permissions as $permission) {
            $chkPermission = Permission::where(['main_menu'=>$permission['main_menu'],'sub_menu'    => $permission['sub_menu']])->first();
            if (!empty($chkPermission)) {
                Permission::where(['main_menu'=>$permission['main_menu'],'sub_menu' => $permission['sub_menu']])
                ->update(
                    [
                        'main_menu'   => $permission['main_menu'],
                        'sub_menu'    => $permission['sub_menu'],
                        'menu_route'  => $permission['menu_route'],
                        'type'        => $permission['type'],
                        'created_by'  =>'10001',
                        'updated_by'  =>'10001'
                    ]
                );
            } else {
                Permission::create(
                    [
                        'main_menu'   => $permission['main_menu'],
                        'sub_menu'    => $permission['sub_menu'],
                        'menu_route'  => $permission['menu_route'],
                        'type'        => $permission['type'],
                        'created_by'  =>'10001',
                        'updated_by'  =>'10001',
                        'created_at'  =>$nowDate,
                        'updated_at'  =>$nowDate
                    ]
                );
            }
        }
    }
}
