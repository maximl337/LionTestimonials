<?php

namespace App\Services;

use Mail;
use App\User;
use App\Contact;
use App\Invitation;

class ContactService
{

    /**
     * [sendEmailInvitation description]
     * @param  Contact    $contact [description]
     * @param  User       $user    [description]
     * @param  [type]     $message [description]
     * @param  Video|null $video   [description]
     * @return [type]              [description]
     */
    public function sendEmailInvite(Contact $contact, User $user, $message="", Video $video=null)
    {
        try {
            
            // create token
            $token = md5(uniqid($user->email . env('APP_KEY'), true));

            // build url params
            $params = [
                'token' => $token,
                'id' => $contact->id
            ];

            //make url
            $url = env('APP_URL') . 'testimonials/create?' . http_build_query($params);

            // build email payload
            $data = [
                'url' => $url,
                'body' => $message ?: "",
                'video' => $video ?: false,
                'user' => $user,
                'contact' => $contact
            ];

            // send mail
            Mail::send('emails.invite', $data, function($m) use ($contact, $user) {

                $m->from('robot@sellwithreviews.com', $user->getName());

                $m->to($contact->email, $contact->first_name)->subject('Testimonial Request');
            });

            $invitation = new Invitation([
                    'email' => true,
                    'token' => $token
                ]);

            $contact->invitation()->save($invitation);

        } catch (Exception $e) {

            throw $e;
            
        }
    }

}