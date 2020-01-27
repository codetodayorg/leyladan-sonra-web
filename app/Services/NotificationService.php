<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;
use Notification;

use App\Notifications\FacultyInform as FacultyInformNotification;
use App\Notifications\MessageReceived as MessageReceivedNotification;
use App\Notifications\ActivateEmail as ActivateEmailNotification;
use App\Notifications\ApprovedUser as ApprovedUserNotification;
use App\Notifications\GiftArrived as GiftArrivedNotification;
use App\Notifications\GiftDelivered as GiftDeliveredNotification;
use App\Notifications\NewUser as NewUserNotification;
use App\Notifications\ResetPassword as ResetPasswordNotification;

class NotificationService extends Service
{
    static function sendEmailActivationNotification($notifiable)
    {
        if (!static::validateEmail($notifiable->email)) return;

        $token = hash_hmac('sha256', str_random(40), $notifiable->email);
        $notifiable->update(['email_token' => $token]);
        $notifiable->notify(new ActivateEmailNotification($token));
    }

    static function sendApprovedUserNotification($notifiable)
    {
        if (!static::validateEmail($notifiable->email)) return;

        $notifiable->notify(new ApprovedUserNotification());
    }

    static function sendNewUserNotification(User $newUser)
    {

        $notifiables = $newUser->faculty->users()->role(UserRole::FacultyManager)->get();

        // If it's a new faculty, there is no manager. So send notification to admins
        if ($notifiables->isEmpty()) {
            $notifiables = User::role(UserRole::Admin)->get();
        }

        $notifiables = static::filterEmails($notifiables);

        Notification::send($notifiables, new NewUserNotification($newUser));

        return $notifiables;
    }

    static function sendGiftDeliveredNotification($child)
    {
        $notifiables = $child->faculty->users()->role(UserRole::Relation)->get();

        $notifiables = static::filterEmails($notifiables);

        Notification::send($notifiables, new GiftDeliveredNotification($child));

        return $notifiables;
    }

    static function sendGiftArrivedNotification($child)
    {
        $notifiables = $child->users;

        $notifiables = static::filterEmails($notifiables);

        Notification::send($notifiables, new GiftArrivedNotification($child));

        return $notifiables;
    }

    static function sendMessageReceivedNotification($chat)
    {
        $notifiables = $chat->faculty->users()->role(UserRole::Relation)->get();
        if ($notifiables->isEmpty()) {
            $notifiables = $chat->faculty->users()->role(UserRole::FacultyManager)->get();
        }

        $notifiables = static::filterEmails($notifiables);

        Notification::send($notifiables, new MessageReceivedNotification($chat));

        return $notifiables;
    }

    static function sendFacultyInformNotification($faculty, $roles, $sender, $subject, $body)
    {
        $notifiables = $faculty->users()->role($roles)->get();

        $notifiables = static::filterEmails($notifiables);

        Notification::send($notifiables, new FacultyInformNotification($subject, $body, $sender->full_name));

        return $notifiables;
    }

    private static function filterEmails($notifiables)
    {
        return $notifiables->filter(function ($notifiable) {
            return filter_var($notifiable->email, FILTER_VALIDATE_EMAIL);
        });
    }

    private static function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
