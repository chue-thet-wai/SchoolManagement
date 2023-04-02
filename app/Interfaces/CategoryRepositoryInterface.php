<?php

namespace App\Interfaces;

interface CategoryRepositoryInterface 
{
    public function getAcademicYear();
    public function getGrade();
    public function getBranch();
    public function getSection();
    public function getRoom();
}