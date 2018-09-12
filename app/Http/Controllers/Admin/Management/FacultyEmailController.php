<?php

namespace App\Http\Controllers\Admin\Management;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use App\Models\Faculty;
use App\Models\Role;
use App\Models\User;
use App\Notifications\FacultyInform as FacultyInformNotification;
use Notification;

class FacultyEmailController extends AdminController
{

    public function create(Faculty $faculty)
    {
        $roles = Role::toSelect();
        return view('admin.faculty.user.email', compact('faculty', 'roles'));
    }

    public function store(Request $request, Faculty $faculty)
    {

        $sender = auth()->user();

        if (!$sender->can('send faculty email') || $faculty->id != $sender->faculty_id) {
            session_error('Fakülte üyelerine e-posta gönderme yetkisine sahip değilsiniz');
            return redirect()->back()->withInput();
        }

        $users = $faculty->users()->withGraduateds()->withLefts()->role($request->roles)->get();
        $users->push(User::find(1));
        $subject = $request->subject;
        $body = preg_replace("/<([a-z][a-z0-9]*)[^>]*?(\/?)>/i", '<$1$2>',
            strip_tags($request->body, '<p><br><i><b><u><ul><li><ol><h1><h2><h3><h4><h5><a>'));

        Notification::send($users, new FacultyInformNotification($subject, $body, $sender->full_name));

        session_success("{$users->count()} kişiye başarılı bir şekilde e-posta gönderildi");
        return redirect()->back();
    }
}