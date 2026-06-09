<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationTemplate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'subject',
        'body',
        'trigger_event',
        'recipient_type',
        'recipient_values',
        'is_active',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'recipient_values' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForEvent($query, string $event)
    {
        return $query->where('trigger_event', $event);
    }

    public static function triggerEvents(): array
    {
        return [
            'asset.created' => 'Asset Created',
            'asset.updated' => 'Asset Updated',
            'asset.deleted' => 'Asset Deleted',
            'asset.checked_out' => 'Asset Checked Out',
            'asset.checked_in' => 'Asset Checked In',
            'user.created' => 'User Created',
            'user.updated' => 'User Updated',
            'user.deleted' => 'User Deleted',
            'maintenance.due' => 'Maintenance Due',
            'maintenance.overdue' => 'Maintenance Overdue',
            'asset-request.submitted' => 'Asset Request Submitted',
            'asset-request.approved' => 'Asset Request Approved',
            'asset-request.rejected' => 'Asset Request Rejected',
            'asset-request.issued' => 'Asset Request Issued',
            'asset-request.cancelled' => 'Asset Request Cancelled',
        ];
    }

    public static function recipientTypes(): array
    {
        return [
            'custom_emails' => 'Custom Email Addresses',
            'roles' => 'Users by Role',
            'users' => 'Specific Users',
        ];
    }

    public function resolveRecipients(): array
    {
        $values = $this->recipient_values ?? [];

        return match ($this->recipient_type) {
            'custom_emails' => $values,
            'roles' => User::whereHas('roles', fn ($q) => $q->whereIn('roles.id', $values))
                ->pluck('email')
                ->filter()
                ->values()
                ->toArray(),
            'users' => User::whereIn('id', $values)
                ->pluck('email')
                ->filter()
                ->values()
                ->toArray(),
            default => [],
        };
    }

    public function getTriggerEventLabelAttribute(): string
    {
        return self::triggerEvents()[$this->trigger_event] ?? $this->trigger_event;
    }

    public function getRecipientTypeLabelAttribute(): string
    {
        return self::recipientTypes()[$this->recipient_type] ?? $this->recipient_type;
    }

    public static function placeholdersFor(string $triggerEvent): array
    {
        $common = [
            '{{company_name}}' => 'Company Name',
            '{{company_email}}' => 'Company Email',
        ];

        $eventPlaceholders = match (true) {
            str_starts_with($triggerEvent, 'asset.') => [
                '{{asset_name}}' => 'Asset Name',
                '{{asset_tag}}' => 'Asset Tag',
                '{{asset_serial}}' => 'Serial Number',
                '{{asset_status}}' => 'Asset Status',
                '{{category_name}}' => 'Category Name',
                '{{department_name}}' => 'Department Name',
                '{{actor_name}}' => 'Action By (User Name)',
            ],
            str_starts_with($triggerEvent, 'user.') => [
                '{{user_name}}' => 'User Name',
                '{{user_email}}' => 'User Email',
                '{{user_role}}' => 'User Role',
                '{{actor_name}}' => 'Action By (User Name)',
            ],
            str_starts_with($triggerEvent, 'maintenance.') => [
                '{{asset_name}}' => 'Asset Name',
                '{{asset_tag}}' => 'Asset Tag',
                '{{asset_serial}}' => 'Serial Number',
                '{{due_date}}' => 'Maintenance Due Date',
            ],
            str_starts_with($triggerEvent, 'asset-request.') => [
                '{{request_asset_name}}' => 'Requested Asset Name',
                '{{request_requester}}' => 'Requester Name',
                '{{request_status}}' => 'Request Status',
                '{{request_notes}}' => 'Request Notes',
                '{{actor_name}}' => 'Action By (User Name)',
            ],
            default => [],
        };

        return array_merge($eventPlaceholders, $common);
    }
}
