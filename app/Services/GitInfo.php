<?php

namespace App\Services;

use Illuminate\Support\Facades\Process;

class GitInfo
{
    public static function getCommitHash(): string
    {
        try {
            return Process::run('git rev-parse --short HEAD')->output();
        } catch (\Throwable) {
            return 'unknown';
        }
    }

    public static function getBranch(): string
    {
        try {
            return Process::run('git rev-parse --abbrev-ref HEAD')->output();
        } catch (\Throwable) {
            return 'unknown';
        }
    }

    public static function getLastCommitMessage(): string
    {
        try {
            return Process::run('git log -1 --pretty=%B')->output();
        } catch (\Throwable) {
            return 'unknown';
        }
    }

    public static function getLastCommitDate(): string
    {
        try {
            return Process::run('git log -1 --pretty=%ci')->output();
        } catch (\Throwable) {
            return 'unknown';
        }
    }
}
