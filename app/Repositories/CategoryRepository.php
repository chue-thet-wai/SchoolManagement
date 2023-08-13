<?php

namespace App\Repositories;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\AcademicYear;
use App\Models\Branch;
use App\Models\Grade;
use App\Models\Room;
use App\Models\Section;
use App\Models\Subject;

class CategoryRepository implements CategoryRepositoryInterface 
{
    public function getAcademicYear() 
    {
        return AcademicYear::all();
    }

    public function getGrade() 
    {
        return Grade::all();
    }
    public function getBranch() 
    {
        return Branch::all();
    }
    public function getRoom() 
    {
        return Room::all();
    }
    public function getSection() 
    {
        return Section::all();
    }
    public function getSubject() 
    {
        return Subject::all();
    }
   
}