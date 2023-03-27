<?php

namespace App\Repositories;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\AcademicYear;
use App\Models\Branch;
use App\Models\Grade;

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

   
}