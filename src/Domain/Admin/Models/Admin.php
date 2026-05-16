<?php

declare(strict_types=1);

namespace Domain\Admin\Models;

use Carbon\Carbon;
use Domain\Admin\Enums\AdminStatusEnum;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Domain\Auth\Mails\VerifyEmail;

/**
 * @property int            $id
 * @property string         $email
 * @property AdminStatusEnum $status
 * @property string         $password
 * @property string         $name
 * @property string         $remember_token
 * @property ?Carbon        $email_verified_at
 * @property ?Carbon        $created_at
 * @property ?Carbon        $updated_at
 */
class Admin extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return [
            'status' => AdminStatusEnum::class,
        ];
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }
}
