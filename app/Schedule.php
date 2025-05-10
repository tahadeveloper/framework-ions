<?php

namespace App;

use GO\Scheduler;
use JetBrains\PhpStorm\Pure;

class Schedule
{
    private Scheduler $scheduler;

    #[Pure] public function __construct()
    {
        $this->scheduler = new Scheduler();
    }

    /**
     * @return void
     * call in cron job: host/cron/schedule # every *
     */
    public function boot(): void
    {
        // schedule cron run
    }

    public function __destruct()
    {
        $this->scheduler->run();
    }
}
