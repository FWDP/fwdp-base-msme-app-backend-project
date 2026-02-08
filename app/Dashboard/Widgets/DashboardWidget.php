<?php

namespace App\Dashboard\Widgets;

interface DashboardWidget
{
    public function id(): string;

    public function title(): string;

    public function type(): string;

    public function position(): int;

    public function icon(): string;

    public function data(): string;
}
