<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = TableConstant::PLAN_TABLE;
    protected $casts = ['settings' => 'array'];
    const NAME = 'name';
    const DESCRIPTION = 'description';
    const CREATED_BY = 'created_by';
    const FIELD_BACKGROUND = 'background_color';
    const FIELD_COLOR = 'color';
    const SETTING_DEFAULT = [
        [
            Plan::FIELD_BACKGROUND => '#fee4cb',
            Plan::FIELD_COLOR => '#ff942e'
        ],
        [
            Plan::FIELD_BACKGROUND => '#e9e7fd',
            Plan::FIELD_COLOR => '#4f3ff0'
        ],
        [
            Plan::FIELD_BACKGROUND => '#dbf6fd',
            Plan::FIELD_COLOR => '#096c86'
        ],
        [
            Plan::FIELD_BACKGROUND => '#ffd3e2',
            Plan::FIELD_COLOR => '#df3670'
        ],
        [
            Plan::FIELD_BACKGROUND => '#c8f7dc',
            Plan::FIELD_COLOR => '#34c471'
        ],
        [
            Plan::FIELD_BACKGROUND => '#d5deff',
            Plan::FIELD_COLOR => '#4067f9'
        ],
    ];
}
