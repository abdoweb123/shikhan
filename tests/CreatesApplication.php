<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
<<<<<<< HEAD
use Illuminate\Foundation\Application;
=======
<<<<<<< HEAD
use Illuminate\Foundation\Application;
=======
>>>>>>> origin/Abdelrahman_shikhan-10
>>>>>>> b7cb4e1d80796a98313415ef7a5d8e797d4f6f04

trait CreatesApplication
{
    /**
     * Creates the application.
<<<<<<< HEAD
     */
    public function createApplication(): Application
=======
<<<<<<< HEAD
     */
    public function createApplication(): Application
=======
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
>>>>>>> origin/Abdelrahman_shikhan-10
>>>>>>> b7cb4e1d80796a98313415ef7a5d8e797d4f6f04
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
