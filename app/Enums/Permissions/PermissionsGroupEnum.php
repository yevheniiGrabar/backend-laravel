<?php

namespace App\Enums\Permissions;

use App\Enums\AbstractEnum;

class PermissionsGroupEnum extends AbstractEnum
{
    public const PERMISSIONS_SETTINGS = 'Permissions Settings';
    public const USER_LIST = 'User list';
    public const CARDS = 'Cards';
    public const LESSONS = 'Lessons';
    public const AFFILIATES = 'Affiliates';
    public const DEPARTMENTS = 'Departments';
    public const CALENDARS = 'Calendars';
    public const EVENTS = 'Events';
    public const COURSES = 'Courses';
    public const QUIZZES = 'Quizzes';
    public const FILES = 'Files';
    public const COMPANY = 'Company';
}
