<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Contact extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'read_at'
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array
     */
    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Marquer le message comme lu.
     *
     * @return bool
     */
    public function markAsRead()
    {
        return $this->forceFill(['read_at' => $this->freshTimestamp()])->save();
    }

    /**
     * Marquer le message comme non lu.
     *
     * @return bool
     */
    public function markAsUnread()
    {
        return $this->forceFill(['read_at' => null])->save();
    }

    /**
     * Déterminer si le message a été lu.
     *
     * @return bool
     */
    public function read()
    {
        return $this->read_at !== null;
    }

    /**
     * Déterminer si le message n'a pas été lu.
     *
     * @return bool
     */
    public function unread()
    {
        return $this->read_at === null;
    }

    /**
     * Portée pour les messages non lus.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }
}
