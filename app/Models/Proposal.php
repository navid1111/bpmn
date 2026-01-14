<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    /** @use HasFactory<\Database\Factories\ProposalFactory> */
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'proposal',
        'category',
        'business_type',
        'status',
        'invitation_date',
    ];

    protected $casts = [
        'invitation_date' => 'date',
    ];
}
