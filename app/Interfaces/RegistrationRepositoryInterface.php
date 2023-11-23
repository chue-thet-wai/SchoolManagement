<?php

namespace App\Interfaces;

interface RegistrationRepositoryInterface 
{
    public function generateStudentID();
    public function getClass();
    public function generateRegistrationNo();
    public function generatePaymentInvoiceID();
    public function getStudentInfo();
}