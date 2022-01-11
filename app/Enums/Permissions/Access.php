<?php

namespace App\Enums\Permissions;

use App\Enums\AbstractEnum;

/**
 * Class Access
 *
 * @package App\Enums\Permissions
 */
final class Access extends AbstractEnum
{
    // Permissions settings
    public const VIEW_PERMISSION_SETTINGS = 'View Permission settings';
    public const UPDATE_PERMISSION_SETTINGS = 'Update Permission settings';

    // Users
    public const VIEW_LIST_USERS = 'View list of Users';
    public const VIEW_USER_CONFIG = 'View User config';
    public const UPDATE_CURRENT_USER = 'Update current user';
    public const EXPORTS_USERS = 'Export Users';
    public const CREATE_USERS = 'Create Users';
    public const DELETE_USERS = 'Delete Users';
    public const UPDATE_USERS = 'Update Users';

    // company Users
    public const VIEW_LIST_COMPANY_USERS = 'View list of Users';
    public const VIEW_COMPANY_USER_CONFIG = 'View User config';
    public const UPDATE_CURRENT_COMPANY_USER = 'Update current user';
    public const EXPORTS_COMPANY_USERS = 'Export Users';
    public const CREATE_COMPANY_USERS = 'Create Users';
    public const DELETE_COMPANY_USERS = 'Delete Users';
    public const UPDATE_COMPANY_USERS = 'Update Users';

    // Cards
    public const VIEW_LIST_CARDS = 'View list of Cards';
    public const VIEW_CURRENT_CARD = 'View current Card';
    public const CREATE_CARDS = 'Create Cards';
    public const UPDATE_CARDS = 'Update Cards';
    public const DELETE_CARDS = 'Delete Cards';

    // Affiliates
    public const VIEW_LIST_AFFILIATES = 'View list of Affiliates';
    public const VIEW_CURRENT_AFFILIATES = 'View current Affiliate';
    public const CREATE_AFFILIATES = 'Create Affiliates';
    public const UPDATE_AFFILIATES = 'Update Affiliates';
    public const DELETE_AFFILIATES = 'Delete Affiliates';

    // Departments
    public const VIEW_LIST_DEPARTMENTS = 'View list of Departments';
    public const VIEW_CURRENT_DEPARTMENT = 'View current Department';
    public const CREATE_DEPARTMENTS = 'Create Departments';
    public const UPDATE_DEPARTMENTS = 'Update Departments';
    public const DELETE_DEPARTMENTS = 'Delete Departments';

    // Calendars
    public const VIEW_LIST_CALENDARS = 'View list of Calendars';
    public const VIEW_CURRENT_CALENDAR = 'View current Calendar';

    // Events
    public const VIEW_LIST_EVENTS = 'View list of Events';
    public const VIEW_CURRENT_USERS_EVENTS = 'View current users Events';
    public const CREATE_EVENTS = 'Create Events';
    public const UPDATE_EVENTS = 'Update Events';
    public const DELETE_EVENTS = 'Delete Events';

    // Courses
    public const VIEW_LIST_COURSES = 'View list of Courses';
    public const VIEW_CURRENT_COURSE = 'View current Courses';
    public const CREATE_COURSES = 'Create Courses';
    public const UPDATE_COURSES = 'Update Courses';
    public const DELETE_COURSES = 'Delete Courses';

    // Files
    public const VIEW_LIST_FILES = 'View list of Files';
    public const CREATE_FILES = 'Create File';
    public const DELETE_FILES = 'Delete File';

    // Cabinet
    public const VIEW_CABINET = 'View Cabinet';
    public const UPDATE_CABINET = 'Update Cabinet';

    // Company settings && Company pages
    public const VIEW_COMPANY_SETTINGS = 'View Company settings';
    public const UPDATE_COMPANY_SETTINGS = 'Update Company settings';
    public const UPDATE_COMPANY_PAGES = 'Update Company pages';
    public const CREATE_COMPANY_PAGES = 'Create Company pages';
    public const DELETE_COMPANY_PAGES = 'Delete Company pages';

    public const VIEW_ANALYTICS = 'View analytics';

    public const VIEW_TASKS = 'View tasks';

    //Company TODO need implement here
}
