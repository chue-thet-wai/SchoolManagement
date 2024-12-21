<?php

namespace App\Interfaces;

interface RegistrationRepositoryInterface 
{
    public function generateStudentID();
    public function getClass();
    public function generateRegistrationNo();
    public function generatePaymentInvoiceID();
    public function getStudentInfo();
    public function sendMessage($data);
    public function getHomeworkStatus();
    public function getDailyActivity();
    public function getStudentRequestTypes();
}