<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

class ProgressBarHelper
{
    private $itemsCount;
    private $persentStep = 1;
    private $progressBarPart = 1;
    private $startMessage = "Process started";
    private $finishMessage = "Process finished";
    private $itemsCounter = 0;
    private $status = 0;

    public function __construct($itemsCount, $persentStep)
    {
        $this->itemsCount = $itemsCount;
        $this->persentStep = $persentStep;
        $this->progressBarPart = round($this->itemsCount / (100 / $this->persentStep));
    }

    public function start($message = false)
    {
        if ($message) {
            $this->startMessage = $message;
        }
        if ($this->itemsCount > 1) {
            echo $this->startMessage."\n";
        }
    }

    public function update()
    {
        $this->itemsCounter++;
        if ($this->itemsCounter == $this->progressBarPart) {
            if ($this->itemsCount > 1) {
                echo "Progress " . ($this->status + $this->persentStep) . "%\n";
            }
            $this->status += $this->persentStep;
            $this->itemsCounter = 0;
        }
    }

    public function finish($message = false)
    {
        if ($message) {
            $this->finishMessage = $message;
        }
        if ($this->itemsCount > 1) {
            echo $this->finishMessage . "\n";
        }
    }
}
