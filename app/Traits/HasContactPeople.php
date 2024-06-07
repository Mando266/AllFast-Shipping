<?php

namespace App\Traits;

use App\ContactPerson;
use Illuminate\Support\Str;

trait HasContactPeople
{
    protected string $keyName;


    public function contactPeople(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(ContactPerson::class, 'contactable');
    }

    public function storeContactPeople($data): void
    {
        $contactPeople = collect();
        foreach ($data['role'] ?? [] as $k => $v) {
            $item = array_map(fn($values) => $values[$k] ?? null, $data);
            $contactPeople->push($item);
        }

        $this->contactPeople()->delete();
        
        foreach ($contactPeople->toArray() as $contactPerson) {
            if (array_filter($contactPerson, fn($s) => $s !== null)) {
                $this->contactPeople()->create($contactPerson);
            }
        }
    }

    public static function allWithContactEmails(\Closure $callable = null)
    {
        $query = static::query();
        if ($callable) {
            $callable($query);
        }
        $customers = $query->with('contactPeople')->get();
        return $customers->map(fn($s) => collect([
            'emails' => $s->contactPeople->pluck('email')->push(str_replace(' - ', "\r\n", $s->email))->implode("\r\n"),
            'name' => $s->name
        ]));
    }
}