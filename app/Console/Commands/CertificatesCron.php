<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\course;
use App\language;
use PDF;
use Anam\PhantomMagick\Converter;

class CertificatesCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'certificates:cron {course_id} {count} {languages}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('memory_limit','-1');
        ini_set('max_execution_time', 0);

        $course_id = $this->argument('course_id');
        $count = $this->argument('count');
        $languages = $this->argument('languages');

        \Log::info("Cron is working fine! $count => $languages");

        $languages = explode(',',$languages);
        $course = course::whereIn('lang',$languages)->where('id',$course_id)->firstOrFail();

        $messages = [];foreach ($course->translate($row->locale) as $row){$messages[$row->lang] = $row;}
        $results = $course->test_results()->where([['flag','=',0],['rate','!=',0],['email','!=','']])->whereIn('lang',$languages)->limit($count)->get();

        if($results->count())
        {
            $send = null;
            foreach ($results as $row)
            {
                $message = $messages[$row->lang];
                $row->update(['flag' => 1]);
                $content = view('emails.results', ['data' => $row,'subject' => $message->subject,'content' => $message->content]);

               $conv = new Converter();
               $options = [
                   'format' => $course->format,
                   'orientation' => $course->orientation,
                   'margin' => '.5cm'
               ];
               $conv->setPdfOptions($options)->addPage($content)
               ->setBinary(base_path('vendor/anam/phantomjs-2.1.1-linux-x86_64/bin/phantomjs'))
               ->save(public_path().'/storage/app/certificates/'.$row->lang.'-'.$row->id.'.pdf');

                \Mail::send('emails.results', ['data' => $row,'subject' => $message->subject,'content' => $message->content],
                function ($mail) use ($row,$message)
                {
                    $mail
                    ->from(config('mail.from.address'),config('mail.from.name'))
                    ->to($row->member->email,$row->member->name)->subject($message->subject)->attach(public_path().'/storage/app/certificates/'.$row->lang.'-'.$row->id.'.pdf', [
                        'mime' => 'application/pdf',
                        'as' => 'certificate.pdf',
                    ]);
                });

                if(empty(\Mail::failures()))
                {
                    $row->update(['flag' => 2]);
                }
                else
                {
                    \Log::error("A problem occurred while sending the certificate for[$row->email]!");
                }
            }
        }
        else
        {
            \Log::info("Cron {certificates} is [DONE]");
        }

        $this->info('Certificates:Cron Cummand Run successfully!');
    }
}
