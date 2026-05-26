<?php

namespace App\Enums;

enum ScanMode: string
{
    case Profile = 'profile';
    case Attendance = 'attendance';
    case Communion = 'communion';
}
