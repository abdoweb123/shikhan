<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Mail;
use Illuminate\Support\Facades\Log;

class SendFuncEmail extends Command
{

    protected $signature = 'send:func_email';
    protected $description = 'send emails from static query from table "emails_to_send_queries_members" ';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
            // $data = DB::Table('emails_to_send_queries')->where('status',1)->where('is_active',1)->first();
            // $utilConn = config('project.db_util_connection');
            $data = DB::Table('emails_to_send_queries')->where('status',1)->where('is_active',1)->first();

            if (!$data){ // no queries in emails_to_send_queries table
              // Log::emergency('a');
              return;
            }

            $functionPath = 'App\\Actions\\Queries\\' . $data->func; // App\Actions\Queries\UsersTestedXCoursesAndSuccessed
            $params = !empty($data->params) ? json_decode($data->params,true) : [];
            $params = $params + [ 'func_id' => $data->id ];
            $currentFunction =  new $functionPath($params);
            $sendTo = $currentFunction->getEmails();

            // Log::emergency(json_encode($sendTo));

            if (! $sendTo){ // finish sending emails to all users of current query. delete current query
              DB::Table('emails_to_send_queries')->where('id',$data->id)->update(['status' => 0]);
            }

            $settings = [
              'email_to' =>  $sendTo->email,
              'message' => $data->message
            ];


            $settings = (new \App\Services\EmailService())->prepareSettings($settings);

            $mailPath = 'App\\Mail\\' . $data->func;
            $email =  new $mailPath($settings);

            try {
                  Mail::to($settings['email_to'])->send($email);

                  if ($data->func != 'SendEmail'){  // for queries
                        DB::Table('emails_to_send_queries_members')->insert([
                          'emails_to_send_queries_id' => $data->id,
                          'user_id' => $sendTo->id
                        ]);
                  } else { // send to spapcific emails
                        DB::table('emails_to_send')->where('id', $sendTo->id)->delete();
                  }
            } catch (\Exception $ex) {

                  if ($data->func != 'SendEmail'){  // for queries
                        DB::Table('emails_to_send_queries_members')->insert([
                          'emails_to_send_queries_id' => $data->id,
                          'user_id' => $sendTo->id,
                          'send_status' => $ex->getMessage()
                    ]);
                  } else { // send to spapcific emails
                        DB::table('emails_to_send')->where('id', $sendTo->id)->delete();
                  }

                  // Log::emergency($ex->getMessage());

                  DB::table('members')->where('email', $sendTo->email)->update([
                    'error_email' => $ex->getMessage()
                  ]);
            }


    }
}
