<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password as RulesPassword;

class ResetAdminAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:reset {userid=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset password of given user ID';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userid = $this->argument('userid');
        $pass = $this->secret('What is the password?');
        $validator = Validator::make(['pass' => $pass], ['pass' => [RulesPassword::min(6)->letters()->numbers()]]);
        if (! empty($validator->errors()->messages())) {
            dd($validator->errors());
            $array = $validator->errors()->messages()['pass'];
            foreach ($array as $elem) {
                $this->line($elem);
            }
        }
        $userService = new UserService;
        $userService->updatePass($userid, $pass);
        $this->line("Done!");
        return Command::SUCCESS;
    }
}
