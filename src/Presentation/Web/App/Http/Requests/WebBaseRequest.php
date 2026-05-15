<?php

declare(strict_types=1);

namespace Presentation\Web\App\Http\Requests;

use Infrastructure\Http\Requests\BaseRequest;
use Domain\Admin\Models\Admin;

class WebBaseRequest extends BaseRequest
{
    public function getAuthAdmin(): Admin
    {
        $admin = $this->user();

        if ($admin instanceof Admin === false) {
            abort(401);
        }

        return $admin;
    }
}
