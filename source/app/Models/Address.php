<?php

namespace App\Models;
#add factories and others

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'street', 'city', 'province_state', 'postal_code', 'country', 'email', 'phone'])]

class Address extends Model
{

}
