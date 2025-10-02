<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Notifications\SystemNotification;

class CreateTestNotifications extends Command
{
    protected $signature = 'notifications:test {--user=admin@booksms.com}';
    protected $description = 'Create test notifications for a user';

    public function handle()
    {
        $email = $this->option('user');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found!");
            return 1;
        }

        $this->info("Creating test notifications for {$user->name}...");

        // Create different types of notifications
        $notifications = [
            [
                'title' => 'New Sale Created',
                'message' => 'A new sale has been processed successfully.',
                'icon' => 'heroicon-o-currency-dollar',
                'color' => 'success'
            ],
            [
                'title' => 'Low Stock Alert',
                'message' => 'Some products are running low on stock.',
                'icon' => 'heroicon-o-exclamation-triangle',
                'color' => 'warning'
            ],
            [
                'title' => 'Payment Received',
                'message' => 'Payment has been successfully processed.',
                'icon' => 'heroicon-o-check-circle',
                'color' => 'success'
            ],
            [
                'title' => 'System Update',
                'message' => 'The system has been updated with new features.',
                'icon' => 'heroicon-o-arrow-path',
                'color' => 'info'
            ]
        ];

        foreach ($notifications as $notification) {
            $user->notify(new SystemNotification(
                $notification['title'],
                $notification['message'],
                $notification['icon'],
                $notification['color']
            ));
        }

        $this->info("Created " . count($notifications) . " test notifications!");
        
        $totalCount = \DB::table('notifications')->where('notifiable_id', $user->id)->count();
        $this->info("Total notifications for {$user->name}: {$totalCount}");

        return 0;
    }
}
