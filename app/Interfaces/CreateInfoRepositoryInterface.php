<?php

namespace App\Interfaces;

interface CreateInfoRepositoryInterface 
{
    public function generateDriverID();
    public function generateTrackNumber();
    public function getClassSetup();
    public function getTeacherList();
    public function getWeekDays();
}